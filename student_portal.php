<?php
session_start();
require_once __DIR__ . '/db.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit;
}

$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'];
$program_id = $_SESSION['program_id'];
$student_course = $_SESSION['student_course'];

// Get student details
$stmt = $conn->prepare('SELECT * FROM students WHERE student_id = ?');
$stmt->bind_param('s', $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

// Get program details
$stmt = $conn->prepare('SELECT * FROM programs WHERE id = ?');
$stmt->bind_param('i', $program_id);
$stmt->execute();
$program = $stmt->get_result()->fetch_assoc();

// Get subjects for the student's program
$stmt = $conn->prepare('SELECT * FROM subjects WHERE program_id = ? ORDER BY year_level, semester, subject_code');
$stmt->bind_param('i', $program_id);
$stmt->execute();
$subjects = $stmt->get_result();


// Get available sections for the student's program and year level
$stmt = $conn->prepare('SELECT * FROM sections WHERE program_id = ? AND year_level = ? ORDER BY section_name');
$stmt->bind_param('is', $program_id, $student['year_level']);
$stmt->execute();
$available_sections = $stmt->get_result();

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: student_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Portal - <?php echo htmlspecialchars($student_name); ?></title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="shortcut icon" href="faviconn.ico" type="image/x-icon">
  <style>
body, html {
  font-family: 'Poppins', 'Roboto', Arial, sans-serif;
  background: #f4f7fa;
}
.btn-primary, .btn-warning, .form-select, .form-control, .form-label, .fw-semibold, .text-primary {
  color: #003399 !important;
}
.btn-warning, .bg-warning {
  background-color: #ffcd00 !important;
  border-color: #ffcd00 !important;
  color: #003399 !important;
}
.btn-warning:hover, .btn-warning:focus {
  background-color: #ffb300 !important;
  border-color: #ffb300 !important;
  color: #003399 !important;
}
.fs-7 {
    font-size: 0.75rem;
}
.fs-xsmall {
  font-size: 0.75rem !important;
}
.status-passed {
  color: #28a745;
  font-weight: bold;
}
.status-failed {
  color: #dc3545;
  font-weight: bold;
}
.status-pending {
  color: #ffc107;
  font-weight: bold;
}
.can-enroll-yes {
  color: #28a745;
  font-weight: bold;
}
.can-enroll-no {
  color: #6c757d;
  font-weight: bold;
}
  </style>
</head>
<body class="bg-light min-vh-100">
  <nav class="navbar navbar-expand-lg navbar-dark" style="background:#003399;">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="images/ncst-logo.png" alt="NCST Logo" width="40" height="40" class="me-2">
        Student Portal
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#portalNavbar" aria-controls="portalNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="portalNavbar">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <span class="nav-link text-light">
              <i class="bi bi-person-circle me-1"></i>
              <?php echo htmlspecialchars($student_name); ?>
            </span>
          </li>
          <li class="nav-item">
            <a class="nav-link text-warning" href="?logout=1">
              <i class="bi bi-box-arrow-right me-1"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card shadow-sm mb-4">
          <div class="card-body text-center">
            <img src="images/ncst-logo.png" alt="NCST Logo" width="70" class="mb-3">
            <h3 class="fw-bold mb-2" style="color:#003399;">Welcome, <?php echo htmlspecialchars($student_name); ?>!</h3>
            <p class="mb-1">Student ID: <strong><?php echo htmlspecialchars($student_id); ?></strong></p>
            <p class="mb-1">Program: <strong><?php echo htmlspecialchars($student_course); ?></strong></p>
            <p class="mb-0">Year Level: <strong><?php echo htmlspecialchars($student['year_level']); ?></strong></p>
          </div>
        </div>
        
        <!-- Bootstrap Tabs -->
        <ul class="nav nav-tabs mb-4" id="portalTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="enrollment-tab" data-bs-toggle="tab" data-bs-target="#enrollment" type="button" role="tab" aria-controls="enrollment" aria-selected="true">
              <i class="bi bi-pencil-square me-1"></i> Enrollment
            </button>
          </li>
        </ul>
        
        <div class="tab-content" id="portalTabContent">
          <!-- Enrollment Tab -->
          <div class="tab-pane fade show active" id="enrollment" role="tabpanel" aria-labelledby="enrollment-tab">
            <h4 class="fw-bold mb-3 mt-2" style="color:#003399;">
              <i class="bi bi-pencil-square me-2"></i><?php echo htmlspecialchars($student_course); ?> Curriculum
            </h4>
            <div class="table-responsive mb-3">
              <table class="table table-bordered table-hover align-middle small fs-xsmall">
                <thead class="table-light">
                  <tr>
                    <th>Year</th>
                    <th>Semester</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Units</th>
                    <th>Status</th>
                    <th>PreReq</th>
                    <th>Status</th>
                    <th>Can Enroll</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($subject = $subjects->fetch_assoc()): ?>
                    <?php
                    $grade_info = $grades_lookup[$subject['subject_code']] ?? null;
                    $status = $grade_info ? ($grade_info['status'] == 'Passed' ? 'Passed' : 'Failed') : '';
                    $prereq_status = '';
                    $can_enroll = 'No';
                    
                    // Check prerequisite status
                    if ($subject['prerequisite']) {
                        $prereq_grade = $grades_lookup[$subject['prerequisite']] ?? null;
                        $prereq_status = $prereq_grade ? ($prereq_grade['status'] == 'Passed' ? 'Passed' : 'Failed') : '';
                        
                        // Determine if can enroll
                        if ($status == 'Passed') {
                            $can_enroll = 'Completed';
                        } elseif ($subject['prerequisite'] && $prereq_status == 'Passed') {
                            $can_enroll = 'Yes';
                        } elseif (!$subject['prerequisite']) {
                            $can_enroll = 'Yes';
                        }
                    } else {
                        if ($status == 'Passed') {
                            $can_enroll = 'Completed';
                        } else {
                            $can_enroll = 'Yes';
                        }
                    }
                    ?>
                    <tr>
                      <td><?php echo htmlspecialchars($subject['year_level']); ?></td>
                      <td><?php echo htmlspecialchars($subject['semester']); ?></td>
                      <td><?php echo htmlspecialchars($subject['subject_code']); ?></td>
                      <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                      <td><?php echo htmlspecialchars($subject['units']); ?></td>
                      <td class="<?php echo $status == 'Passed' ? 'status-passed' : ($status == 'Failed' ? 'status-failed' : 'status-pending'); ?>">
                        <?php echo $status ?: ''; ?>
                      </td>
                      <td><?php echo htmlspecialchars($subject['prerequisite'] ?: ''); ?></td>
                      <td class="<?php echo $prereq_status == 'Passed' ? 'status-passed' : ($prereq_status == 'Failed' ? 'status-failed' : ''); ?>">
                        <?php echo $prereq_status; ?>
                      </td>
                      <td class="<?php echo $can_enroll == 'Yes' ? 'can-enroll-yes' : ($can_enroll == 'Completed' ? 'status-passed' : 'can-enroll-no'); ?>">
                        <?php echo $can_enroll; ?>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
            <div class="text-end">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#enrollModal">Proceed to Enrollment</button>
            </div>
          </div>

  <!-- Proceed to Enrollment Modal -->
  <div class="modal fade" id="enrollModal" tabindex="-1" aria-labelledby="enrollModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="enrollModalLabel">Confirm Enrollment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to proceed with your enrollment?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="var sModal = new bootstrap.Modal(document.getElementById('sectionModal')); sModal.show();">Yes, Proceed</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Section Selection Modal -->
  <div class="modal fade" id="sectionModal" tabindex="-1" aria-labelledby="sectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="sectionModalLabel">Select Section</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="sectionForm">
            <div class="mb-3">
              <label for="sectionSelect" class="form-label">Choose your section:</label>
              <select class="form-select" id="sectionSelect" required>
                <option selected disabled>Select Section</option>
                <?php while ($section = $available_sections->fetch_assoc()): ?>
                  <option value="<?php echo htmlspecialchars($section['id']); ?>">
                    <?php echo htmlspecialchars($section['section_name']); ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div id="sectionSchedule" class="mt-3"></div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="proceedToEnrollment()">Confirm Section</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Enrollment Form Modal -->
  <div class="modal fade" id="enrollFormModal" tabindex="-1" aria-labelledby="enrollFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title" id="enrollFormModalLabel">Enrollment Form</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <h6 class="fw-bold">Student Information</h6>
              <p><strong>Name:</strong> <?php echo htmlspecialchars($student_name); ?></p>
              <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student_id); ?></p>
              <p><strong>Course:</strong> <?php echo htmlspecialchars($student_course); ?></p>
              <p><strong>Section:</strong> <span id="enrollFormSection" class="fw-bold text-primary"></span></p>
            </div>
            <div class="col-md-6">
              <h6 class="fw-bold">Schedule</h6>
              <div id="enrollFormSchedule"></div>
            </div>
          </div>
          <div class="mt-3">
            <h6 class="fw-bold">Assessment of Fees</h6>
            <table class="table table-sm">
              <tr>
                <td>Tuition Fee</td>
                <td class="text-end">₱15,000.00</td>
              </tr>
              <tr>
                <td>Miscellaneous Fee</td>
                <td class="text-end">₱2,500.00</td>
              </tr>
              <tr class="table-active fw-bold">
                <td>Total</td>
                <td class="text-end">₱17,500.00</td>
              </tr>
            </table>
          </div>
          <div class="text-end mt-3">
            <button type="button" class="btn btn-success" onclick="getQueueNumber()">Get Queue Number</button>
            <a href="queue_display.php" class="btn btn-info ms-2" target="_blank">
              <i class="bi bi-display me-1"></i>View Queue Status
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Queue Number Modal -->
  <div class="modal fade" id="queueModal" tabindex="-1" aria-labelledby="queueModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title" id="queueModalLabel">Queue Number for Evaluation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <i class="bi bi-ticket-detailed display-1 text-warning mb-3"></i>
          <h3 class="mb-3">Your Queue Number</h3>
          <div class="display-4 fw-bold text-primary mb-3" id="queueNumber">E-001</div>
          <p class="mb-3">Please wait for your number to be called for evaluation.</p>
          <div class="alert alert-info">
            <small><i class="bi bi-info-circle me-1"></i>Queue numbers are only valid until 5:00 PM today.</small>
          </div>
          <div class="text-end mt-3">
            <button type="button" class="btn btn-primary" onclick="closeQueueModal()">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
let queueCounter = 1;

function proceedToEnrollment() {
  const sectionSelect = document.getElementById('sectionSelect');
  const selectedSection = sectionSelect.value;
  const selectedSectionText = sectionSelect.options[sectionSelect.selectedIndex].text;
  
  if (!selectedSection) {
    alert('Please select a section first.');
    return;
  }
  
  // Show enrollment status
  alert('You\'re pre-enrolled in this schedule!');
  
  // Redirect to registration form
  window.location.href = 'print_enrollment.php?section_id=' + selectedSection;
}



function closeQueueModal() {
  var queueModal = bootstrap.Modal.getInstance(document.getElementById('queueModal'));
  queueModal.hide();
}

// Section selection change handler
document.getElementById('sectionSelect').addEventListener('change', function() {
  const selected = this.value;
  if (selected) {
    document.getElementById('sectionSchedule').innerHTML = '<div class="alert alert-info">Loading schedule...</div>';
    
    // Fetch schedule from database using AJAX
    fetch('get_section_schedule.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'section_id=' + encodeURIComponent(selected)
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        document.getElementById('sectionSchedule').innerHTML = data.html;
      } else {
        document.getElementById('sectionSchedule').innerHTML = '<div class="alert alert-warning">' + data.message + '</div>';
      }
    })
    .catch(error => {
      document.getElementById('sectionSchedule').innerHTML = '<div class="alert alert-danger">Error loading schedule</div>';
    });
  } else {
    document.getElementById('sectionSchedule').innerHTML = '';
  }
});

function getQueueNumber() {
  // Get queue number from server
  fetch('queue_system.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'action=get_queue_number'
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      document.getElementById('queueNumber').textContent = data.queue_number;
      
      // Close enrollment form and show queue modal
      var eFormModal = bootstrap.Modal.getInstance(document.getElementById('enrollFormModal'));
      eFormModal.hide();
      var queueModal = new bootstrap.Modal(document.getElementById('queueModal'));
      queueModal.show();
    } else {
      alert(data.message);
    }
  })
  .catch(error => {
    alert('Error getting queue number');
  });
}
  </script>
</body>
</html> 