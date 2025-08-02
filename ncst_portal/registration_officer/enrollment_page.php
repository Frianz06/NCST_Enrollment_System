<?php
session_start();
require_once '../../db.php';

// Check if user is logged in and is either admission officer or registration officer
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'registration' && $_SESSION['role'] !== 'admission')) {
    header('Location: ../ncst_login.php');
    exit();
}

// Get search parameters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$enrollment_type = isset($_GET['type']) ? $_GET['type'] : '';

// Get approved students for enrollment
$query = "SELECT * FROM student_applications WHERE status = 'approved'";
if (!empty($search)) {
    if ($_SESSION['role'] === 'admission') {
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
    if ($_SESSION['role'] === 'admission') {
        $stmt->bind_param("ss", $search_param, $search_param);
    } else {
        $stmt->bind_param("sss", $search_param, $search_param, $search_param);
    }
}
$stmt->execute();
$result = $stmt->get_result();
$students = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="../admin/admin.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../../faviconn.ico">
    <style>
        body { 
            font-family: 'Poppins', Arial, sans-serif; 
            background: #f4f6fb; 
        }
        .ncst-header {
            background: #003399;
            color: #fff;
            padding: 1.5rem 0 1rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 1.5rem 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        }
        .ncst-card {
            border-radius: 1.2rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            border: none;
        }
        .btn-ncst {
            background: #FFD600;
            color: #003399;
            font-weight: bold;
            border: none;
        }
        .btn-ncst:hover {
            background: #e6c200;
            color: #003399;
        }
        .search-box {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            margin-bottom: 2rem;
        }
        .student-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .student-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
        }


        .form-control:focus {
            border-color: #003399;
            box-shadow: 0 0 0 0.2rem rgba(0, 51, 153, 0.25);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="ncst-header text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <img src="../../images/ncst-logo.png" alt="NCST Logo" style="height: 60px; margin-right: 1.5rem;">
                        <h1 class="mb-0">Enrollment System</h1>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <!-- Search Box -->
                        <div class="search-box">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label for="search" class="form-label fw-bold">Search Students:</label>
                                    <input type="text" class="form-control form-control-lg" id="search" name="search" 
                                           value="<?php echo htmlspecialchars($search); ?>" 
                                           placeholder="<?php echo $_SESSION['role'] === 'admission' ? 'Search by full name...' : 'Search by Student ID or full name...'; ?>"
                                           autocomplete="off">
                                    <div class="form-text">
                                        <i class="bi bi-info-circle"></i> 
                                        Start typing to search instantly...
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="button" class="btn btn-outline-secondary" onclick="clearSearch()">
                                            <i class="bi bi-arrow-clockwise"></i> Clear Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>



                                                <!-- Students List -->
                        <div class="card ncst-card" id="studentsContainer">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 class="card-title mb-0">Available Students</h4>
                                    <span class="badge bg-primary fs-6" id="studentCount"><?php echo count($students); ?> students found</span>
                                </div>

                                <?php if (empty($students)): ?>
                                    <div class="text-center py-5">
                                        <i class="bi bi-search" style="font-size: 3rem; color: #ccc;"></i>
                                        <h5 class="text-muted mt-3">No students found</h5>
                                        <p class="text-muted">
                                            <?php if ($_SESSION['role'] === 'admission'): ?>
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
                            </div>
                        </div>

                        <!-- Back Button -->
                        <div class="text-center mt-4">
                            <a href="dashboard.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let searchTimeout;
        
        $(document).ready(function() {
            // Instant search functionality
            $('#search').on('input', function() {
                const searchTerm = $(this).val().trim();
                
                // Clear previous timeout
                clearTimeout(searchTimeout);
                
                // Set new timeout for search (300ms delay)
                searchTimeout = setTimeout(function() {
                    performInstantSearch(searchTerm);
                }, 300);
            });
        });
        
        function performInstantSearch(searchTerm) {
            // Show loading state
            $('#studentsContainer .card-body').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Searching students...</p>
                </div>
            `);
            
            // Perform AJAX search
            $.ajax({
                url: 'search_students.php',
                method: 'POST',
                data: {
                    search: searchTerm,
                    role: '<?php echo $_SESSION['role']; ?>'
                },
                success: function(response) {
                    $('#studentsContainer .card-body').html(response);
                },
                error: function() {
                    $('#studentsContainer .card-body').html(`
                        <div class="text-center py-5">
                            <i class="bi bi-exclamation-triangle" style="font-size: 3rem; color: #dc3545;"></i>
                            <h5 class="text-danger mt-3">Search Error</h5>
                            <p class="text-muted">Please try again or refresh the page.</p>
                        </div>
                    `);
                }
            });
        }
        
        function clearSearch() {
            $('#search').val('');
            performInstantSearch('');
        }
        
        function enrollStudent(studentId, enrollmentType) {
            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="bi bi-hourglass-split"></i> Loading...';
            button.disabled = true;
            
            // Redirect to specific enrollment page based on type
            if (enrollmentType === 'manual') {
                window.location.href = `manual_enrollment.php?student_id=${studentId}`;
            } else if (enrollmentType === 'section') {
                window.location.href = `section_enrollment.php?student_id=${studentId}`;
            } else {
                // Reset button if error
                button.innerHTML = originalText;
                button.disabled = false;
                alert('Invalid enrollment type selected.');
            }
        }
    </script>
</body>
</html> 