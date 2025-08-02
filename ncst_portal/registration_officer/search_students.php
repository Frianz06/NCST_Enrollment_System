<?php
session_start();
require_once '../../db.php';

// Check if user is logged in and is either admission officer or registration officer
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'registration' && $_SESSION['role'] !== 'admission')) {
    http_response_code(403);
    exit('Unauthorized');
}

// Get search parameters
$search = isset($_POST['search']) ? $_POST['search'] : '';
$role = $_SESSION['role'];

// Get approved students for enrollment
$query = "SELECT * FROM student_applications WHERE status = 'approved'";
if (!empty($search)) {
    if ($role === 'admission') {
        // Admission officers can only search by full name
        $query .= " AND (CONCAT(first_name, ' ', last_name) LIKE ? OR CONCAT(last_name, ' ', first_name) LIKE ?)";
    } else {
        // Registration officers can search by student ID and full name
        $query .= " AND (student_id LIKE ? OR CONCAT(first_name, ' ', last_name) LIKE ? OR CONCAT(last_name, ' ', first_name) LIKE ?)";
    }
}
$query .= " ORDER BY last_name, first_name";

$stmt = $conn->prepare($query);
if (!empty($search)) {
    $search_param = "%$search%";
    if ($role === 'admission') {
        $stmt->bind_param("ss", $search_param, $search_param);
    } else {
        $stmt->bind_param("sss", $search_param, $search_param, $search_param);
    }
}
$stmt->execute();
$result = $stmt->get_result();
$students = $result->fetch_all(MYSQLI_ASSOC);

// Update header and count
$title = empty($search) ? 'Available Students' : 'Search Results';
$count = count($students);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="card-title mb-0"><?php echo $title; ?></h4>
    <span class="badge bg-primary fs-6"><?php echo $count; ?> students found</span>
</div>

<?php if (empty($students)): ?>
    <div class="text-center py-5">
        <i class="bi bi-search" style="font-size: 3rem; color: #ccc;"></i>
        <h5 class="text-muted mt-3">No students found</h5>
        <p class="text-muted">
            <?php if ($role === 'admission'): ?>
                Try searching by the student's full name or check if there are approved students.
            <?php else: ?>
                Try searching by Student ID or full name, or check if there are approved students.
            <?php endif; ?>
        </p>
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($students as $student): ?>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card student-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px;">
                                <span class="text-white fw-bold">
                                    <?php echo strtoupper(substr($student['first_name'] ?? '', 0, 1) . substr($student['last_name'] ?? '', 0, 1)); ?>
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold">
                                    <?php echo htmlspecialchars($student['first_name'] ?? '') . ' ' . htmlspecialchars($student['last_name'] ?? ''); ?>
                                </h6>
                                <small class="text-muted">
                                    <?php echo htmlspecialchars($student['course'] ?? $student['track'] ?? 'N/A'); ?>
                                </small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($student['email'] ?? 'N/A'); ?></p>
                            <p class="mb-1"><strong>Type:</strong> 
                                <?php
                                $student_type = strtolower($student['student_type'] ?? $student['admission_type'] ?? 'new');
                                $is_transferee = stripos($student_type, 'transferee') !== false;
                                $is_irregular = stripos($student_type, 'irregular') !== false;
                                $is_old_student = stripos($student_type, 'old') !== false;
                                
                                if ($is_transferee || $is_irregular || $is_old_student) {
                                    $badge_class = 'bg-warning';
                                    $icon = 'bi-gear';
                                } else {
                                    $badge_class = 'bg-info';
                                    $icon = 'bi-people';
                                }
                                ?>
                                <span class="badge <?php echo $badge_class; ?>">
                                    <i class="bi <?php echo $icon; ?>"></i>
                                    <?php echo htmlspecialchars($student['student_type'] ?? $student['admission_type'] ?? 'New'); ?>
                                </span>
                            </p>
                            <p class="mb-0"><strong>Status:</strong> 
                                <span class="badge bg-warning">Approved</span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <?php
                            // Determine enrollment type based on student type
                            $student_type = strtolower($student['student_type'] ?? $student['admission_type'] ?? 'new');
                            $is_transferee = stripos($student_type, 'transferee') !== false;
                            $is_irregular = stripos($student_type, 'irregular') !== false;
                            $is_old_student = stripos($student_type, 'old') !== false;
                            
                            // Auto-determine enrollment type
                            if ($is_transferee || $is_irregular || $is_old_student) {
                                $enrollment_type = 'manual';
                                $enrollment_label = 'Manual Enrollment';
                                $enrollment_description = 'Individual subject selection';
                                $button_class = 'btn-warning';
                                $icon = 'bi-gear';
                            } else {
                                $enrollment_type = 'section';
                                $enrollment_label = 'Section Enrollment';
                                $enrollment_description = 'Block section assignment';
                                $button_class = 'btn-success';
                                $icon = 'bi-people';
                            }
                            ?>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i> 
                                    <strong>Recommended:</strong> <?php echo $enrollment_description; ?>
                                </small>
                                <span class="badge bg-secondary small">
                                    <?php echo ucfirst($student_type); ?> Student
                                </span>
                            </div>
                            
                            <div class="d-grid">
                                <button type="button" 
                                        class="btn <?php echo $button_class; ?> btn-sm"
                                        onclick="enrollStudent(<?php echo $student['id']; ?>, '<?php echo $enrollment_type; ?>')">
                                    <i class="bi <?php echo $icon; ?>"></i> 
                                    <?php echo $enrollment_label; ?>
                                </button>
                            </div>
                            
                            <!-- Alternative option for manual override -->
                            <?php if (!$is_transferee && !$is_irregular && !$is_old_student): ?>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <a href="#" onclick="enrollStudent(<?php echo $student['id']; ?>, 'manual')" class="text-decoration-none">
                                            <i class="bi bi-gear"></i> Use Manual Enrollment instead
                                        </a>
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?> 