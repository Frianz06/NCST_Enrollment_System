<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'registration') {
    header('Location: ../ncst_login.php'); exit;
}
require_once '../../db.php';

// Handle queue actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'next_queue':
            // Get the next waiting queue
            $stmt = $conn->prepare('SELECT * FROM queue_system WHERE status = "waiting" AND queue_date = CURDATE() ORDER BY created_at ASC LIMIT 1');
            $stmt->execute();
            $next_queue = $stmt->get_result()->fetch_assoc();
            
            if ($next_queue) {
                // Update status to processing
                $stmt = $conn->prepare('UPDATE queue_system SET status = "processing", processed_at = NOW() WHERE id = ?');
                $stmt->bind_param('i', $next_queue['id']);
                $stmt->execute();
                
                echo json_encode(['success' => true, 'queue_number' => $next_queue['queue_number']]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No more students in queue']);
            }
            break;
            
        case 'validate_enrollment':
            $student_id = $_POST['student_id'] ?? '';
            $section_id = $_POST['section_id'] ?? '';
            
            if ($student_id && $section_id) {
                // Mark student as enrolled
                $stmt = $conn->prepare('UPDATE student_applications SET status = "enrolled" WHERE student_id = ?');
                $stmt->bind_param('s', $student_id);
                $stmt->execute();
                
                // Update student record
                $stmt = $conn->prepare('UPDATE students SET section_id = ? WHERE student_id = ?');
                $stmt->bind_param('is', $section_id, $student_id);
                $stmt->execute();
                
                echo json_encode(['success' => true, 'message' => 'Student enrolled successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing required data']);
            }
            break;
    }
    exit;
}

// Fetch applicants with status 'approved' (ready for registration)
$for_reg = $conn->query("SELECT * FROM student_applications WHERE status = 'approved' ORDER BY id DESC");

// Fetch enrolled students
$enrolled_students = $conn->query("SELECT * FROM student_applications WHERE status = 'enrolled' ORDER BY id DESC");

// Get current queue status
$stmt = $conn->prepare('
    SELECT q.*, s.name as student_name, s.course 
    FROM queue_system q 
    JOIN students s ON q.student_id = s.student_id 
    WHERE q.queue_date = CURDATE() 
    ORDER BY q.created_at ASC
');
$stmt->execute();
$queues = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get current processing queue
$stmt = $conn->prepare('SELECT * FROM queue_system WHERE status = "processing" AND queue_date = CURDATE() LIMIT 1');
$stmt->execute();
$current_queue = $stmt->get_result()->fetch_assoc();

// Calculate counts for display
$approved_count = $for_reg->num_rows;
$enrolled_count = $enrolled_students->num_rows;
$waiting_count = count(array_filter($queues, function($q) { return $q['status'] === 'waiting'; }));
$processing_count = count(array_filter($queues, function($q) { return $q['status'] === 'processing'; }));

function renderViewModal($row) {
    $id = $row['id'];
    ?>
    <div class="modal fade" id="viewModal<?php echo $id; ?>" tabindex="-1" aria-labelledby="viewModalLabel<?php echo $id; ?>" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header ncst-theme">
            <div class="d-flex align-items-center">
              <img src="../../images/ncst-logo.png" alt="NCST Logo" style="height: 30px; margin-right: 10px;">
              <h5 class="modal-title mb-0 fw-bold" id="viewModalLabel<?php echo $id; ?>">Applicant Details</h5>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(38%) sepia(99%) saturate(749%) hue-rotate(1deg) brightness(104%) contrast(104%);"></button>
          </div>
          <div class="modal-body" style="font-size: 0.95rem;">
            <!-- Personal Information -->
            <h6 class="fw-bold text-primary mb-2">Personal Information</h6>
            <ul class="list-group mb-3">
              <li class="list-group-item border-0 p-1">Full Name: <?php echo htmlspecialchars($row['last_name'] . ', ' . $row['first_name'] . ' ' . ($row['middle_name'] ?? '')); ?></li>
              <li class="list-group-item border-0 p-1">Birthday: <?php echo !empty($row['dob']) ? htmlspecialchars($row['dob']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Gender: <?php echo !empty($row['gender']) ? htmlspecialchars($row['gender']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Civil Status: <?php echo !empty($row['civil_status']) ? htmlspecialchars($row['civil_status']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Nationality: <?php echo !empty($row['nationality']) ? htmlspecialchars($row['nationality']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Religion: <?php echo !empty($row['religion']) ? htmlspecialchars($row['religion']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Place of Birth: <?php echo !empty($row['pob']) ? htmlspecialchars($row['pob']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Contact Number: <?php echo !empty($row['mobile']) ? htmlspecialchars($row['mobile']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Email Address: <?php echo !empty($row['email']) ? htmlspecialchars($row['email']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Address: <?php echo !empty($row['address']) ? htmlspecialchars($row['address']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Location: <?php 
                $location = array_filter([$row['barangay'] ?? '', $row['city'] ?? '', $row['province'] ?? '']);
                echo !empty($location) ? htmlspecialchars(implode(', ', $location)) : 'Not Specified';
              ?></li>
            </ul>

            <!-- Course Information -->
            <h6 class="fw-bold text-primary mb-2">Course Information</h6>
            <ul class="list-group mb-3">
              <li class="list-group-item border-0 p-1">Preferred Course/Strand: <?php echo !empty($row['course_or_track']) ? htmlspecialchars($row['course_or_track']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Student Type: <?php echo !empty($row['student_type']) ? htmlspecialchars($row['student_type']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Type: <?php echo !empty($row['type']) ? htmlspecialchars($row['type']) : 'Not Specified'; ?></li>
            </ul>

            <!-- Educational Background -->
            <h6 class="fw-bold text-primary mb-2">Educational Background</h6>
            <ul class="list-group mb-3">
              <li class="list-group-item border-0 p-1">Elementary School: <?php echo !empty($row['elementary_school']) ? htmlspecialchars($row['elementary_school']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Elementary Year Graduated: <?php echo !empty($row['elementary_year_grad']) ? htmlspecialchars($row['elementary_year_grad']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">High School: <?php echo !empty($row['high_school']) ? htmlspecialchars($row['high_school']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">High School Year Graduated: <?php echo !empty($row['high_year_grad']) ? htmlspecialchars($row['high_year_grad']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Grade 10 Section: <?php echo !empty($row['grade10_section']) ? htmlspecialchars($row['grade10_section']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Tertiary School: <?php echo !empty($row['tertiary_school']) ? htmlspecialchars($row['tertiary_school']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Tertiary Year Graduated: <?php echo !empty($row['tertiary_year_grad']) ? htmlspecialchars($row['tertiary_year_grad']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Course Graduated: <?php echo !empty($row['course_graduated']) ? htmlspecialchars($row['course_graduated']) : 'Not Specified'; ?></li>
            </ul>

            <!-- Academic Performance -->
            <h6 class="fw-bold text-primary mb-2">Academic Performance</h6>
            <ul class="list-group mb-3">
              <li class="list-group-item border-0 p-1">Academic Achievement: <?php echo !empty($row['academic_achievement']) ? htmlspecialchars($row['academic_achievement']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Educational Plan: <?php echo !empty($row['educational_plan']) ? htmlspecialchars($row['educational_plan']) : 'Not Specified'; ?></li>
            </ul>

            <!-- Work Experience -->
            <h6 class="fw-bold text-primary mb-2">Work Experience</h6>
            <ul class="list-group mb-3">
              <li class="list-group-item border-0 p-1">Is Working: <?php echo !empty($row['is_working']) ? ($row['is_working'] == '1' ? 'Yes' : 'No') : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Employer: <?php echo !empty($row['employer']) ? htmlspecialchars($row['employer']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Work Position: <?php echo !empty($row['work_position']) ? htmlspecialchars($row['work_position']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Work in Shifts: <?php echo !empty($row['work_in_shifts']) ? ($row['work_in_shifts'] == '1' ? 'Yes' : 'No') : 'Not Specified'; ?></li>
            </ul>

            <!-- Family Background -->
            <h6 class="fw-bold text-primary mb-2">Family Background</h6>
            <ul class="list-group mb-3">
              <li class="list-group-item border-0 p-1">Number of Siblings: <?php echo !empty($row['no_of_siblings']) ? htmlspecialchars($row['no_of_siblings']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Family Connected to NCST: <?php echo !empty($row['family_connected_ncst']) ? ($row['family_connected_ncst'] == '1' ? 'Yes' : 'No') : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">NCST Relationship: <?php echo !empty($row['ncst_relationship']) ? htmlspecialchars($row['ncst_relationship']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">How Did You Know NCST: <?php echo !empty($row['how_did_you_know_ncst']) ? htmlspecialchars($row['how_did_you_know_ncst']) : 'Not Specified'; ?></li>
            </ul>

            <!-- Guardian Information -->
            <h6 class="fw-bold text-primary mb-2">Guardian Information</h6>
            <ul class="list-group mb-3">
              <li class="list-group-item border-0 p-1">Guardian Name: <?php
                $guardianName = array_filter([$row['guardian_family_name'] ?? '', $row['guardian_given_name'] ?? '', $row['guardian_middle_name'] ?? '']);
                echo !empty($guardianName) ? htmlspecialchars(implode(' ', $guardianName)) : 'Not Specified';
              ?></li>
              <li class="list-group-item border-0 p-1">Guardian Relationship: <?php echo !empty($row['guardian_relationship']) ? htmlspecialchars($row['guardian_relationship']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Guardian Address: <?php echo !empty($row['guardian_address']) ? htmlspecialchars($row['guardian_address']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Guardian Contact: <?php echo !empty($row['guardian_mobile']) ? htmlspecialchars($row['guardian_mobile']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Guardian Occupation: <?php echo !empty($row['guardian_occupation']) ? htmlspecialchars($row['guardian_occupation']) : 'Not Specified'; ?></li>
            </ul>

            <!-- Father Information -->
            <h6 class="fw-bold text-primary mb-2">Father Information</h6>
            <ul class="list-group mb-3">
              <li class="list-group-item border-0 p-1">Father Name: <?php
                $fatherName = array_filter([$row['father_family_name'] ?? '', $row['father_given_name'] ?? '', $row['father_middle_name'] ?? '']);
                echo !empty($fatherName) ? htmlspecialchars(implode(' ', $fatherName)) : 'Not Specified';
              ?></li>
              <li class="list-group-item border-0 p-1">Father Deceased: <?php echo !empty($row['father_deceased']) ? ($row['father_deceased'] == '1' ? 'Yes' : 'No') : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Father Address: <?php echo !empty($row['father_address']) ? htmlspecialchars($row['father_address']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Father Contact: <?php echo !empty($row['father_mobile']) ? htmlspecialchars($row['father_mobile']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Father Occupation: <?php echo !empty($row['father_occupation']) ? htmlspecialchars($row['father_occupation']) : 'Not Specified'; ?></li>
            </ul>

            <!-- Mother Information -->
            <h6 class="fw-bold text-primary mb-2">Mother Information</h6>
            <ul class="list-group mb-3">
              <li class="list-group-item border-0 p-1">Mother Name: <?php
                $motherName = array_filter([$row['mother_family_name'] ?? '', $row['mother_given_name'] ?? '', $row['mother_middle_name'] ?? '']);
                echo !empty($motherName) ? htmlspecialchars(implode(' ', $motherName)) : 'Not Specified';
              ?></li>
              <li class="list-group-item border-0 p-1">Mother Deceased: <?php echo !empty($row['mother_deceased']) ? ($row['mother_deceased'] == '1' ? 'Yes' : 'No') : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Mother Maiden Name: <?php
                $motherMaidenName = array_filter([$row['mother_maiden_family_name'] ?? '', $row['mother_maiden_given_name'] ?? '', $row['mother_maiden_middle_name'] ?? '']);
                echo !empty($motherMaidenName) ? htmlspecialchars(implode(' ', $motherMaidenName)) : 'Not Specified';
              ?></li>
              <li class="list-group-item border-0 p-1">Mother Address: <?php echo !empty($row['mother_address']) ? htmlspecialchars($row['mother_address']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Mother Contact: <?php echo !empty($row['mother_mobile']) ? htmlspecialchars($row['mother_mobile']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Mother Occupation: <?php echo !empty($row['mother_occupation']) ? htmlspecialchars($row['mother_occupation']) : 'Not Specified'; ?></li>
            </ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-ncst" onclick="validateEnrollment('<?php echo $row['student_id']; ?>')">Validate Enrollment</button>
          </div>
        </div>
      </div>
    </div>
    <?php
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Officer Dashboard | NCST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="../admin/admin.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../../faviconn.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Poppins', Arial, sans-serif; background: #f4f6fb; }
        .ncst-header {
            background: #003399;
            color: #fff;
            padding: 1.5rem 0 1rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 1.5rem 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        }
        .ncst-logo {
            height: 60px;
            margin-right: 1.5rem;
        }
        .dashboard-title {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: #ffd700;
            margin-bottom: 0;
        }
        .ncst-card {
            border-radius: 1.2rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            border: none;
        }
        .table thead {
            background: #003399;
            color: #fff;
        }
        .btn-info {
            background: #003399;
            border: none;
        }
        .btn-warning {
            background: #ffd700;
            color: #003399;
            border: none;
        }
        .btn-info:hover, .btn-warning:hover {
            opacity: 0.85;
        }
        .modal-header.ncst-theme {
            background: #003399;
            color: #fff;
            border-bottom: 3px solid #FFD600;
        }
        .modal-footer .btn-ncst {
            background: #FFD600;
            color: #003399;
            font-weight: bold;
            border: none;
        }
        .modal-footer .btn-ncst:hover {
            background: #e6c200;
            color: #003399;
        }
        .modal-content {
            border-radius: 1.2rem;
        }
        .modal-body h6 {
            color: #003399;
            border-bottom: 2px solid #FFD600;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .modal-body .list-group-item {
            border-left: 3px solid #003399;
            margin-bottom: 2px;
        }
        .nav-link {
            transition: all 0.3s ease;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }
        .nav-link:hover {
            background: rgba(255, 215, 0, 0.1);
            color: #FFD600 !important;
        }
        .nav-link.active {
            background: #FFD600 !important;
            color: #003399 !important;
            font-weight: 600;
        }
        .table-success thead {
            background: #28a745 !important;
        }
        .queue-display {
            background: #003399;
            color: white;
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 2rem;
        }
        .queue-number {
            font-size: 4rem;
            font-weight: bold;
            color: #ffcd00;
        }
        .queue-status {
            font-size: 1.5rem;
            margin-top: 1rem;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .stats-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #dee2e6;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="ncst-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <img src="../../images/ncst-logo.png" alt="NCST Logo" class="ncst-logo">
                    <div>
                        <h1 class="dashboard-title">Registration Officer Dashboard</h1>
                        <p class="mb-0" style="color: #ffd700;">Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Registration Officer'); ?></p>
                    </div>
                </div>
                <div>
                    <a href="../logout.php" class="btn btn-outline-warning">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Queue Management Section -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="queue-display">
                    <h3>Current Queue</h3>
                    <?php if ($current_queue): ?>
                        <div class="queue-number"><?php echo htmlspecialchars($current_queue['queue_number']); ?></div>
                        <div class="queue-status">Currently Processing</div>
                        <p class="mt-3">Student: <?php echo htmlspecialchars($current_queue['student_name'] ?? 'Unknown'); ?></p>
                        <p>Course: <?php echo htmlspecialchars($current_queue['course'] ?? 'Unknown'); ?></p>
                    <?php else: ?>
                        <div class="queue-number">--</div>
                        <div class="queue-status">No Active Queue</div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-grid gap-3">
                    <button class="btn btn-warning btn-lg" onclick="nextQueue()">
                        <i class="bi bi-arrow-right-circle"></i> Next Queue
                    </button>
                    <button class="btn btn-success btn-lg" onclick="completeCurrentQueue()">
                        <i class="bi bi-check-circle"></i> Complete Current
                    </button>
                    <button class="btn btn-danger btn-lg" onclick="cancelCurrentQueue()">
                        <i class="bi bi-x-circle"></i> Cancel Current
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $waiting_count; ?></div>
                    <div class="stats-label">Waiting in Queue</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $processing_count; ?></div>
                    <div class="stats-label">Currently Processing</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $approved_count; ?></div>
                    <div class="stats-label">Ready for Enrollment</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $enrolled_count; ?></div>
                    <div class="stats-label">Enrolled Students</div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4" id="dashboardTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="queue-tab" data-bs-toggle="tab" data-bs-target="#queue-section" type="button" role="tab">
                    <i class="bi bi-list-ol"></i> Queue Management
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="enrollment-tab" data-bs-toggle="tab" data-bs-target="#enrollment-section" type="button" role="tab">
                    <i class="bi bi-person-check"></i> Enrollment Validation
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="enrolled-tab" data-bs-toggle="tab" data-bs-target="#enrolled-section" type="button" role="tab">
                    <i class="bi bi-graduation-cap"></i> Enrolled Students
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="dashboardTabContent">
            <!-- Queue Management Tab -->
            <div class="tab-pane fade show active" id="queue-section" role="tabpanel">
                <div class="card-ncst p-4">
                    <h3 class="fw-bold mb-3" style="color:#003399;">Queue Management</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>Queue #</th>
                                    <th>Student Name</th>
                                    <th>Course</th>
                                    <th>Status</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($queues as $queue): ?>
                                <tr class="<?php echo $queue['status'] === 'processing' ? 'table-warning' : ''; ?>">
                                    <td><?php echo htmlspecialchars($queue['queue_number']); ?></td>
                                    <td><?php echo htmlspecialchars($queue['student_name'] ?? 'Unknown'); ?></td>
                                    <td><?php echo htmlspecialchars($queue['course'] ?? 'Unknown'); ?></td>
                                    <td>
                                        <?php
                                        $statusClass = '';
                                        $statusText = '';
                                        switch ($queue['status']) {
                                            case 'waiting':
                                                $statusClass = 'bg-warning';
                                                $statusText = 'Waiting';
                                                break;
                                            case 'processing':
                                                $statusClass = 'bg-info';
                                                $statusText = 'Processing';
                                                break;
                                            case 'completed':
                                                $statusClass = 'bg-success';
                                                $statusText = 'Completed';
                                                break;
                                            case 'cancelled':
                                                $statusClass = 'bg-danger';
                                                $statusText = 'Cancelled';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                    </td>
                                    <td><?php echo date('H:i', strtotime($queue['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($queues)): ?>
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="empty-state">
                                            <i class="bi bi-list-ol"></i>
                                            <h5>No Queue Today</h5>
                                            <p>No students are currently in the queue.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Enrollment Validation Tab -->
            <div class="tab-pane fade" id="enrollment-section" role="tabpanel">
                <div class="card-ncst p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h3 class="fw-bold mb-0" style="color:#003399;">Enrollment Validation</h3>
                            <small class="text-muted"><?php echo $approved_count; ?> student(s) ready for enrollment</small>
                        </div>
                        <div class="input-group" style="max-width: 320px;">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search by Student ID, Name, Course, or Type...">
                            <span class="input-group-text bg-primary text-white"><i class="bi bi-search"></i></span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="applicationsTable">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Course/Track</th>
                                    <th>Student Type</th>
                                    <th>Requirement Status</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php while ($row = $for_reg->fetch_assoc()):
                                $fullName = $row['last_name'] . ', ' . $row['first_name'] . ' ' . ($row['middle_name'] ?? '');
                                $studentType = $row['student_type'] ?? '';
                                $requirements = [
                                    "Properly accomplished admission form",
                                    "Four (4) 2x2 recent, identical color pictures in white background with name tag",
                                    "Five (5) 1x1 recent, identical color pictures in white background with name tag",
                                    "Submit original and photocopied Form 138 / Report Card",
                                    "Submit original Good Moral Character certificate with dry seal and Photocopied",
                                    "If married, two (2) photocopies of marriage certificate duly signed by a priest / minister",
                                    "1pc. Long Brown Envelope"
                                ];
                                $savedChecklist = [];
                                if (!empty($row['requirements_checklist'])) {
                                    $decoded = json_decode($row['requirements_checklist'], true);
                                    if (is_array($decoded)) $savedChecklist = $decoded;
                                }
                                $missing = array_filter($requirements, function($i) use ($savedChecklist) {
                                    return empty($savedChecklist[$i]);
                                }, ARRAY_FILTER_USE_KEY);
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($fullName); ?></td>
                                    <td><?php echo htmlspecialchars($row['course_or_track']); ?></td>
                                    <td><?php echo htmlspecialchars($studentType); ?></td>
                                    <td><?php echo empty($missing) ? '<span class="badge bg-success">Complete</span>' : '<span class="badge bg-danger">Incomplete</span>'; ?></td>
                                    <td>
                                        <span class="badge bg-primary">Ready for Enrollment</span>
                                        <button class="btn btn-info btn-sm ms-1" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $row['id']; ?>">View</button>
                                        <button class="btn btn-success btn-sm ms-1" onclick="validateEnrollment('<?php echo $row['student_id']; ?>')">Validate</button>
                                    </td>
                                </tr>
                                <?php renderViewModal($row); ?>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Enrolled Students Tab -->
            <div class="tab-pane fade" id="enrolled-section" role="tabpanel">
                <div class="card-ncst p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h3 class="fw-bold mb-0" style="color:#28a745;">Enrolled Students</h3>
                            <small class="text-muted"><?php echo $enrolled_count; ?> student(s) officially enrolled</small>
                        </div>
                        <div class="input-group" style="max-width: 320px;">
                            <input type="text" id="searchEnrolledInput" class="form-control" placeholder="Search by Student ID, Name, Course, or Type...">
                            <span class="input-group-text bg-success text-white"><i class="bi bi-search"></i></span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="enrolledTable">
                            <thead class="table-success">
                                <tr>
                                    <th>Student ID</th>
                                    <th>Full Name</th>
                                    <th>Course/Track</th>
                                    <th>Student Type</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $enrolled_count_display = 0;
                            while ($row = $enrolled_students->fetch_assoc()):
                                $enrolled_count_display++;
                                $fullName = $row['last_name'] . ', ' . $row['first_name'] . ' ' . ($row['middle_name'] ?? '');
                                $studentType = $row['student_type'] ?? '';
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['student_id'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($fullName); ?></td>
                                    <td><?php echo htmlspecialchars($row['course_or_track']); ?></td>
                                    <td><?php echo htmlspecialchars($studentType); ?></td>
                                    <td>
                                        <span class="badge bg-success">Enrolled</span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <?php if ($enrolled_count_display == 0): ?>
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <div class="empty-state">
                                            <i class="bi bi-graduation-cap"></i>
                                            <h5>No Enrolled Students</h5>
                                            <p>No students have been officially enrolled yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const table = document.getElementById('applicationsTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < cells.length; j++) {
                    const cellText = cells[j].textContent.toLowerCase();
                    if (cellText.includes(searchTerm)) {
                        found = true;
                        break;
                    }
                }

                row.style.display = found ? '' : 'none';
            }
        });

        document.getElementById('searchEnrolledInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const table = document.getElementById('enrolledTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < cells.length; j++) {
                    const cellText = cells[j].textContent.toLowerCase();
                    if (cellText.includes(searchTerm)) {
                        found = true;
                        break;
                    }
                }

                row.style.display = found ? '' : 'none';
            }
        });

        // Queue management functions
        function nextQueue() {
            fetch('dashboard.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=next_queue'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'No more students in queue');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error processing queue');
            });
        }

        function completeCurrentQueue() {
            if (confirm('Mark current queue as completed?')) {
                // Implementation for completing current queue
                location.reload();
            }
        }

        function cancelCurrentQueue() {
            if (confirm('Cancel current queue?')) {
                // Implementation for canceling current queue
                location.reload();
            }
        }

        function validateEnrollment(studentId) {
            if (confirm('Validate enrollment for this student?')) {
                fetch('dashboard.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=validate_enrollment&student_id=' + studentId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Student enrolled successfully!');
                        location.reload();
                    } else {
                        alert(data.message || 'Error enrolling student');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error enrolling student');
                });
            }
        }
    </script>
</body>
</html>