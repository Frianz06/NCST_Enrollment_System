<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'registration') {
    header('Location: ../ncst_login.php'); exit;
}
require_once '../../db.php';

// Fetch applicants with status 'approved' (ready for registration)
$for_reg = $conn->query("SELECT * FROM student_applications WHERE status = 'approved' ORDER BY id DESC");

// Fetch enrolled students
$enrolled_students = $conn->query("SELECT * FROM student_applications WHERE status = 'enrolled' ORDER BY id DESC");

// Calculate counts for display
$approved_count = $for_reg->num_rows;
$enrolled_count = $enrolled_students->num_rows;

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
              <li class="list-group-item border-0 p-1">Full Name: <?php echo !empty($row['name']) ? htmlspecialchars($row['name']) : 'Not Specified'; ?></li>
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
                $guardianName = trim(($row['guardian_family_name'] ?? '') . ' ' . ($row['guardian_given_name'] ?? ''));
                echo !empty($guardianName) ? htmlspecialchars($guardianName) : 'Not Specified';
              ?></li>
              <li class="list-group-item border-0 p-1">Guardian Contact: <?php echo !empty($row['guardian_mobile']) ? htmlspecialchars($row['guardian_mobile']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Guardian Address: <?php echo !empty($row['guardian_address']) ? htmlspecialchars($row['guardian_address']) : 'Not Specified'; ?></li>
              <li class="list-group-item border-0 p-1">Guardian Occupation: <?php echo !empty($row['guardian_occupation']) ? htmlspecialchars($row['guardian_occupation']) : 'Not Specified'; ?></li>
            </ul>
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
        .btn-info:hover {
            opacity: 0.85;
        }
        
        /* NCST-themed modal */
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
        
        /* Enhanced modal styling */
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

        /* Tab styling */
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

        /* Table styling for different sections */
        .table-success thead {
            background: #28a745 !important;
            color: #fff;
        }

        /* Badge styling */
        .badge.bg-success {
            font-size: 0.8rem;
            padding: 0.4rem 0.6rem;
        }

        /* Empty state styling */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <!-- Sidebar: visible only on md and up -->
    <div class="sidebar d-none d-md-flex flex-column align-items-center">
        <img src="../../images/ncst-logo.png" alt="NCST Logo" class="logo">
        <h5 class="mb-4">NCST Registration</h5>
        <nav class="nav flex-column w-100">
            <a class="nav-link active" href="#registration-section" data-bs-toggle="tab">For Registration</a>
            <a class="nav-link" href="#enrolled-section" data-bs-toggle="tab">Enrolled Students</a>
        </nav>
    </div>
    <div class="topbar d-flex align-items-center justify-content-end">
        <span class="me-3">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="../logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
    </div>
    <div class="main-content">
        <div class="tab-content">
            <!-- For Registration Tab -->
            <div class="tab-pane fade show active" id="registration-section">
                <div class="card-ncst p-4">
                                <?php if (isset($_SESSION['enrollment_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-check-circle"></i> <?php echo $_SESSION['enrollment_success']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['enrollment_success']); ?>
            <?php endif; ?>
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="fw-bold mb-0" style="color:#003399;">For Registration</h3>
                    <small class="text-muted"><?php echo $approved_count; ?> student(s) ready for registration</small>
                </div>
                        <div class="d-flex gap-2">
                            <div class="input-group" style="max-width: 250px;">
                                <input type="text" id="searchRegistrationInput" class="form-control" placeholder="Search by Student ID, Name, Course, or Type...">
                                <span class="input-group-text bg-primary text-white"><i class="bi bi-search"></i></span>
                            </div>
                            <button class="btn btn-primary" onclick="enrollAllStudents()">
                                <i class="bi bi-graduation-cap"></i> Enroll Students
                            </button>
                        </div>
                    </div>

            <!-- Hamburger Button for Mobile Only (top left) -->
            <button class="hamburger-btn d-md-none position-fixed top-0 start-0 m-3 z-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
              <div class="menu-icon">
                <span></span>
                <span></span>
                <span></span>
              </div>
            </button>
            <!-- Offcanvas Sidebar for Mobile Only -->
            <div class="offcanvas offcanvas-start offcanvas-ncst d-md-none" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
              <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="sidebarMenuLabel">NCST Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                <div class="text-center mb-4">
                  <div class="logo d-inline-block">
                    <img src="../../images/ncst-logo.png" alt="NCST Logo" style="max-width: 60px;">
                  </div>
                </div>
                <nav class="nav flex-column">
                  <a class="nav-link active" href="#registration-section" data-bs-toggle="tab">For Registration</a>
                  <a class="nav-link" href="#enrolled-section" data-bs-toggle="tab">Enrolled Students</a>
                </nav>
              </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="forRegTable">
                    <thead class="table-light">
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
                        $fullName = $row['name'];
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
    <div class="tab-pane fade" id="enrolled-section">
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
                        $fullName = $row['name'];
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

<!-- Reusable NCST Modal -->
<div class="modal fade" id="ncstModal" tabindex="-1" aria-labelledby="ncstModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header ncst-theme">
        <div class="d-flex align-items-center">
          <img src="../../images/ncst-logo.png" alt="NCST Logo" style="height: 30px; margin-right: 10px;">
          <h5 class="modal-title mb-0" id="ncstModalLabel">Modal Title</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(38%) sepia(99%) saturate(749%) hue-rotate(1deg) brightness(104%) contrast(104%);"></button>
      </div>
      <div class="modal-body text-center">
        <div id="ncstModalContent" style="font-size:1.1rem; background: white; padding: 20px; border-radius: 8px; border: 1px solid #dee2e6;">
          Modal content goes here
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-ncst" id="ncstModalConfirm">Confirm</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>
<script>
// Search bar filter logic
$(document).ready(function() {
    // Registration search
    $('#searchRegistrationInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#forRegTable tbody tr').filter(function() {
            var name = $(this).find('td').eq(0).text().toLowerCase();
            var course = $(this).find('td').eq(1).text().toLowerCase();
            var type = $(this).find('td').eq(2).text().toLowerCase();
            $(this).toggle(
                name.indexOf(value) > -1 ||
                course.indexOf(value) > -1 ||
                type.indexOf(value) > -1
            );
        });
    });

    // Enrolled students search
    $('#searchEnrolledInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#enrolledTable tbody tr').filter(function() {
            var name = $(this).find('td').eq(0).text().toLowerCase();
            var course = $(this).find('td').eq(1).text().toLowerCase();
            var type = $(this).find('td').eq(2).text().toLowerCase();
            $(this).toggle(
                name.indexOf(value) > -1 ||
                course.indexOf(value) > -1 ||
                type.indexOf(value) > -1
            );
        });
    });

    // Tab navigation
    $('.nav-link').on('click', function() {
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
    });
});

// Reusable NCST Modal Functions
function showNcstModal(title, content, confirmText = 'Confirm', showCancel = true, onConfirm = null) {
  document.getElementById('ncstModalLabel').textContent = title;
  document.getElementById('ncstModalContent').innerHTML = content;
  document.getElementById('ncstModalConfirm').textContent = confirmText;
  
  const cancelBtn = document.querySelector('#ncstModal .btn-secondary');
  if (showCancel) {
    cancelBtn.style.display = 'block';
  } else {
    cancelBtn.style.display = 'none';
  }
  
  // Clear previous event listeners
  const confirmBtn = document.getElementById('ncstModalConfirm');
  const newConfirmBtn = confirmBtn.cloneNode(true);
  confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
  
  // Add new event listener
  if (onConfirm) {
    newConfirmBtn.addEventListener('click', onConfirm);
  }
  
  const modal = new bootstrap.Modal(document.getElementById('ncstModal'));
  modal.show();
}

// Enrollment function
function enrollStudent(studentId, studentType) {
    // Redirect to universal enrollment page
    window.location.href = 'enrollment_page.php';
}

// Enroll all students
function enrollAllStudents() {
    // Redirect to universal enrollment page
    window.location.href = 'enrollment_page.php';
}
</script>
</body>
</html>