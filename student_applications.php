<?php
session_start();
require_once 'db.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (headers_sent($file, $line)) {
    die("Headers already sent in $file on line $line");
}

function naIfEmptyOrSelect($val) {
    return (empty($val) || $val === '-- Select --') ? null : $val;
}

$active_section = 'requirements';
$error = '';
$success = '';

// Define required fields for SHS
$required_fields = [
    // Common required fields
    'last_name', 'first_name', 'dob', 'gender', 'civil_status', 'region', 'province', 'city', 'barangay', 'address', 'zip_code',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Log POST data
    error_log("POST data received: " . print_r($_POST, true));
    
    // Validate required fields
    $missing = [];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '' || $_POST[$field] === '-- Select --') {
            $missing[] = $field;
        }
    }
    

    
    // Guardian required if no parent info
    $guardian_required = false;
    if ((empty($_POST['father_family_name']) && empty($_POST['mother_family_name'])) || (isset($_POST['guardian_required']) && $_POST['guardian_required'] == '1')) {
        $guardian_required = true;
        foreach(['guardian_family_name','guardian_given_name','guardian_relationship','guardian_address','guardian_mobile','guardian_occupation'] as $gfield) {
            if (!isset($_POST[$gfield]) || trim($_POST[$gfield]) === '') {
                $missing[] = $gfield;
            }
        }
    }
    
    if (count($missing) > 0) {
        $error = 'Please fill in all required fields: ' . implode(', ', $missing);
    } else {
        // List of all columns in your table that can be filled by the form
        $columns = [
            'name','email','course_or_track','gender','civil_status','type','admission_type','status','nationality','religion','region','province','city','barangay',
            'last_name','first_name','middle_name','suffix','address','zip_code','mobile','landline','dob','pob','dialect',
            'elementary_school','elementary_year_grad','high_school','high_year_grad','grade10_section',
            'father_family_name','father_given_name','father_middle_name','father_deceased','father_address','father_mobile','father_landline','father_occupation',
            'mother_family_name','mother_given_name','mother_middle_name','mother_deceased','mother_maiden_family_name','mother_maiden_given_name','mother_maiden_middle_name','mother_address','mother_mobile','mother_landline','mother_occupation',
            'guardian_family_name','guardian_given_name','guardian_middle_name','guardian_relationship','guardian_address','guardian_mobile','guardian_landline','guardian_occupation',
            'requirements_status','student_type','tertiary_school','tertiary_year_grad','course_graduated','educational_plan','academic_achievement',
            'is_working','employer','work_in_shifts','work_position','family_connected_ncst','ncst_relationship','no_of_siblings','how_did_you_know_ncst'
        ];
        
        // Compose name for legacy column
        $name = trim(($_POST['last_name'] ?? '') . ', ' . ($_POST['first_name'] ?? '') . ' ' . ($_POST['middle_name'] ?? '') . ' ' . ($_POST['suffix'] ?? ''));
        $_POST['name'] = $name;
        
        // Set type for SHS applications
        $_POST['type'] = 'SHS';
        
        // Handle checkboxes and defaults
        foreach(['is_working','work_in_shifts','family_connected_ncst','father_deceased','mother_deceased'] as $cb) {
            if (!isset($_POST[$cb])) $_POST[$cb] = 0;
        }
        
        // Build insert arrays
        $db_fields = [];
        $placeholders = [];
        $values = [];
        
        foreach ($columns as $col) {
            if (isset($_POST[$col]) && $_POST[$col] !== '') {
                $db_fields[] = $col;
                $placeholders[] = '?';
                $values[] = $_POST[$col];
            }
        }
        
        // Add required static fields
        $db_fields[] = 'status';
        $placeholders[] = '?';
        $values[] = 'new';
        
        $sql = "INSERT INTO student_applications (" . implode(",", $db_fields) . ") VALUES (" . implode(",", $placeholders) . ")";
        
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param(str_repeat('s', count($values)), ...$values);
            if ($stmt->execute()) {
                $new_applicant_id = $conn->insert_id;
                $tracking_number = 'NCST-' . date('Y') . '-' . str_pad($new_applicant_id, 5, '0', STR_PAD_LEFT);
                $conn->query("UPDATE student_applications SET tracking_number='$tracking_number' WHERE id=$new_applicant_id");
                $_SESSION['tracking_number'] = $tracking_number;
                $success = 'Your application has been <strong>submitted successfully</strong>!<br>'
                    . 'Your <strong>Tracking/Reference Number</strong> is:<br>'
                    . '<span style="font-size:1.3rem; color:#003399; font-weight:bold;">' . htmlspecialchars($tracking_number) . '</span><br>'
                    . '<strong>Please keep this number and present it when submitting your requirements at NCST campus.</strong>';
                
                // Debug: Log success
                error_log("Application submitted successfully with tracking number: " . $tracking_number);
                
            } else {
                $error = 'Database error: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = 'Database error: ' . $conn->error;
        }
        
        // Debug: Log any errors
        if (isset($error)) {
            error_log("Form submission error: " . $error);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCST Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" type="image/x-icon" href="faviconn.ico">

    <style>
        body, html {
            height: 100%;
            font-family: 'Poppins', Arial, sans-serif;
            background: #f4f7fa;
        }
        /* Replace sidebar CSS to match admission dashboard */
        .sidebar {
            background: linear-gradient(180deg, #003399 0%, #0055cc 100%);
            min-height: 100vh;
            color: #fff;
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-top-right-radius: 40px;
            border-bottom-right-radius: 30px;
            box-shadow: 2px 0 8px rgba(0,0,0,0.07);
            z-index: 1051;
        }
        .sidebar .logo {
            width: 90px;
            margin-top: 32px;
            margin-bottom: 1.5rem;
        }
        .sidebar h5 {
            margin-bottom: 2rem;
            font-size: 1.4rem;
            font-weight: 600;
            text-align: center;
        }
        .sidebar .nav-link {
            color: #fff;
            font-weight: 500;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: background 0.15s, color 0.15s;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background: #FFD600;
            color: #003399;
        }
        .main-content {
            padding: 2.5rem 1.5rem;
        }
        .section-cards {
            margin-top: 1.5rem;
        }
        .section-cards .card {
            border-radius: 1.2rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            margin-bottom: 2rem;
        }
        .section-cards .card-title {
            font-weight: 700;
            letter-spacing: 1px;
        }
        @media (max-width: 991px) {
            .sidebar {
                border-radius: 0 0 30px 30px;
                min-height: auto;
            }
        }
        .custom-hamburger {
          display: flex;
          flex-direction: column;
          justify-content: center;
          align-items: center;
          height: 24px;
          width: 28px;
        }
        .custom-hamburger span {
          display: block;
          height: 3px;
          width: 70%;
          background: #fff;
          border-radius: 2px;
          margin: 4px auto;
          transition: all 0.3s;
        }
        .navbar-toggler-icon {
          background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255,255,255,0.95)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
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
        .offcanvas-ncst {
            background: linear-gradient(180deg, #003399 0%, #0055cc 100%) !important;
            color: #fff !important;
            min-height: 100vh;
            padding-top: 2rem;
            border-top-right-radius: 30px;
            border-bottom-right-radius: 30px;
        }
        .offcanvas-ncst .logo {
            width: 60px;
            margin-bottom: 1rem;
        }
        .offcanvas-ncst .nav-link {
            color: #fff !important;
            font-weight: 500;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: background 0.15s, color 0.15s;
        }
        .offcanvas-ncst .nav-link.active, .offcanvas-ncst .nav-link:hover {
            background: #FFD600 !important;
            color: #003399 !important;
        }
        .offcanvas-ncst .offcanvas-title {
            color: #fff !important;
        }
        .offcanvas-ncst .btn-close {
            filter: invert(1) grayscale(1) brightness(2);
        }
        .hamburger-btn {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1051;
            background: #FFD600;
            border: none;
            border-radius: 16px;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            transition: background 0.2s;
        }
        .hamburger-btn .menu-icon {
            width: 28px;
            height: 28px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .hamburger-btn .menu-icon span {
            display: block;
            height: 4px;
            width: 100%;
            background: #fff;
            border-radius: 2px;
            margin: 3px 0;
            transition: 0.3s;
            position: relative;
        }
        .hamburger-btn.open .menu-icon span:nth-child(1) {
            transform: translateY(9px) rotate(45deg);
        }
        .hamburger-btn.open .menu-icon span:nth-child(2) {
            opacity: 0;
        }
        .hamburger-btn.open .menu-icon span:nth-child(3) {
            transform: translateY(-9px) rotate(-45deg);
        }
        @media (max-width: 991px) {
            .hamburger-btn {
                display: flex;
            }
        }
        .plain-navbar {
            position: relative;
            top: 0;
            left: 0;
            width: 100vw;
            height: 64px;
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            border-bottom-right-radius: 0;
            border-top-left-radius: 0;
        }
        @media (max-width: 767px) {
            .plain-navbar {
                height: 56px;
            }
            .sidebar {
                border-top-right-radius: 30px;
                border-bottom-right-radius: 20px;
            }
        }
        .main-content, .container-fluid {
            padding-top: 0 !important;
            /* Remove height and overflow so the whole page scrolls */
        }
        .is-invalid {
          border: 2.5px solid #dc3545 !important;
          background: #ffeaea !important;
          box-shadow: 0 0 0 2px #f8d7da;
        }
    </style>
</head>
<body>
    <div class="plain-navbar"></div>
    <!-- Hamburger Button for Mobile Only (top left, with more margin) -->
    <button class="hamburger-btn d-md-none position-fixed top-0 start-0 m-4 z-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
      <div class="menu-icon">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </button>
    <div class="container-fluid">
      <div class="row min-vh-100">
        <!-- Sidebar: visible only on md and up -->
        <div class="sidebar d-none d-md-flex flex-column align-items-center">
            <img src="images/ncst-logo.png" alt="NCST Logo" class="logo">
            <h5 class="mb-4">NCST Registration</h5>
            <ul class="nav flex-column w-100">
                <li class="nav-item mb-2">
                    <a class="nav-link active" id="nav-req" href="#" onclick="showSidebarSection('requirements')"><i class="bi bi-list-check"></i> Requirements</a>
                </li>
                <li class="nav-item mb-2">
    
                </li>
            </ul>
        </div>
        <!-- Offcanvas Sidebar for Mobile Only -->
        <div class="offcanvas offcanvas-start offcanvas-ncst d-md-none" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarMenuLabel">NCST Registration</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">
            <div class="text-center mb-4">
              <div class="logo d-inline-block">
                <img src="images/ncst-logo.png" alt="NCST Logo" style="max-width: 60px;">
              </div>
            </div>
            <ul class="nav flex-column">
              <li class="nav-item mb-2">
                <a class="nav-link active" id="nav-req-mobile" href="#" onclick="showSidebarSection('requirements'); var offcanvas = bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('sidebarMenu')); offcanvas.hide();"><i class="bi bi-list-check"></i> Requirements</a>
              </li>
              <li class="nav-item mb-2">

              </li>
            </ul>
          </div>
        </div>
        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 main-content">
                <!-- Requirements Section -->
                <div id="section-requirements">
                    <h2 class="fw-bold mb-4" style="color:#003399;">Enrollment Requirements</h2>
                    <div class="row section-cards">
                        <div class="col-md-6">
                            <div class="card border-primary h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">SHS Students</h5>
                                    <ul class="mb-0">
                                        <li>Properly accomplished admission form</li>
                                        <li>Four (4) 2x2 recent, identical color pictures in white background with name tag</li>
                                        <li>Five (5) 1x1 recent, identical color pictures in white background with name tag</li>
                                        <li>Submit original and photocopied Form 138 / Report Card</li>
                                        <li>Submit original Good Moral Character certificate with dry seal and Photocopied</li>
                                        <li>If married, two (2) photocopies of marriage certificate duly signed by a priest / minister</li>
                                        <li>1pc. Long Brown Envelope</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-info h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-info">Transferees</h5>
                                    <ul class="mb-0">
                                        <li>Certificate of Transfer (Original & Photocopied)</li>
                                        <li>Certificate of Grades (Original & Photocopied)</li>
                                        <li>Good Moral Certificate (Original with Dry Seal & Photocopied)</li>
                                        <li>2pcs. Photocopied Birth Certificate (PSA)</li>
                                        <li>2pcs. Photocopied Marriage Contract (PSA), if married</li>
                                        <li>4pcs. 2x2 Picture (White background with name tag)</li>
                                        <li>2pcs. 1x1 Picture (White background)</li>
                                        <li>1pc. Long Brown Envelope</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </main>
        </div>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-9 offset-md-3 col-lg-10 offset-lg-2">
          <div class="d-flex justify-content-end mb-4">
            <a href="index.php" class="btn btn-warning fw-bold" style="color: #003399; min-width: 220px;"><i class="bi bi-arrow-left-circle me-2"></i>Go Back to NCST Main Page</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showSidebarSection(section) {
            document.getElementById('section-requirements').style.display = (section === 'requirements') ? 'block' : 'none';
    
            document.getElementById('nav-req').classList.toggle('active', section === 'requirements');

        }
        // Default to requirements, but override if PHP says so
        <?php if (isset($active_section)): ?>
          showSidebarSection('<?php echo $active_section; ?>');
        <?php else: ?>
          showSidebarSection('requirements');
        <?php endif; ?>
    </script>
    <script>

</script>
<script>


function toggleEducationalFields() {
  var studentType = document.getElementById('student_type');
  var elementarySchool = document.getElementById('elementary_school');
  var elementaryYearGrad = document.getElementById('elementary_year_grad');
  var highSchool = document.getElementById('high_school');
  var highYearGrad = document.getElementById('high_year_grad');
  var tertiarySchool = document.getElementById('tertiary_school');
  var tertiaryYearGrad = document.getElementById('tertiary_year_grad');
  var courseGraduated = document.getElementById('course_graduated');
  
  // Reset all fields first
  if (elementarySchool) elementarySchool.required = false;
  if (elementaryYearGrad) elementaryYearGrad.required = false;
  if (highSchool) highSchool.required = false;
  if (highYearGrad) highYearGrad.required = false;
  if (tertiarySchool) tertiarySchool.required = false;
  if (tertiaryYearGrad) tertiaryYearGrad.required = false;
  if (courseGraduated) courseGraduated.required = false;
  
  // Remove all visual indications
  if (elementarySchool) elementarySchool.classList.remove('border-warning');
  if (elementaryYearGrad) elementaryYearGrad.classList.remove('border-warning');
  if (highSchool) highSchool.classList.remove('border-warning');
  if (highYearGrad) highYearGrad.classList.remove('border-warning');
  if (tertiarySchool) tertiarySchool.classList.remove('border-warning');
  if (tertiaryYearGrad) tertiaryYearGrad.classList.remove('border-warning');
  if (courseGraduated) courseGraduated.classList.remove('border-warning');
  
  if (studentType && studentType.value === 'New') {
    // For new students: elementary and high school education required
    if (elementarySchool) {
      elementarySchool.required = true;
      elementarySchool.classList.add('border-warning');
    }
    if (elementaryYearGrad) {
      elementaryYearGrad.required = true;
      elementaryYearGrad.classList.add('border-warning');
    }
    if (highSchool) {
      highSchool.required = true;
      highSchool.classList.add('border-warning');
    }
    if (highYearGrad) {
      highYearGrad.required = true;
      highYearGrad.classList.add('border-warning');
    }
  } else if (studentType && studentType.value === 'Transferee') {
    // For transferees: elementary, high school, and tertiary school required (but not year and course)
    if (elementarySchool) {
      elementarySchool.required = true;
      elementarySchool.classList.add('border-warning');
    }
    if (elementaryYearGrad) {
      elementaryYearGrad.required = true;
      elementaryYearGrad.classList.add('border-warning');
    }
    if (highSchool) {
      highSchool.required = true;
      highSchool.classList.add('border-warning');
    }
    if (highYearGrad) {
      highYearGrad.required = true;
      highYearGrad.classList.add('border-warning');
    }
    if (tertiarySchool) {
      tertiarySchool.required = true;
      tertiarySchool.classList.add('border-warning');
    }
  }
  // For other student types (Returnee, Foreign, etc.), all fields remain optional
}

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

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
  toggleEducationalFields();
});
</script>
    <!-- Reusable NCST Modal -->
    <div class="modal fade" id="ncstModal" tabindex="-1" aria-labelledby="ncstModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header ncst-theme">
            <div class="d-flex align-items-center">
              <img src="images/ncst-logo.png" alt="NCST Logo" style="height: 30px; margin-right: 10px;">
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
    
    <!-- Legacy Success Modal (for backward compatibility) -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header ncst-theme">
            <div class="d-flex align-items-center">
              <img src="images/ncst-logo.png" alt="NCST Logo" style="height: 30px; margin-right: 10px;">
              <h5 class="modal-title mb-0" id="successModalLabel">Application Submitted</h5>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(38%) sepia(99%) saturate(749%) hue-rotate(1deg) brightness(104%) contrast(104%);"></button>
          </div>
          <div class="modal-body text-center">
            <div id="modalSuccessMessage" style="font-size:1.1rem; background: white; padding: 20px; border-radius: 8px; border: 1px solid #dee2e6;">
              <?php if (isset($success) && !empty($success)): ?>
                <?= $success ?>
              <?php endif; ?>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-ncst" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Hidden success message div for AJAX to find -->
    <div id="successMessage" style="display: none;">
      <?php if (isset($success) && !empty($success)): ?>
        <?= $success ?>
      <?php endif; ?>
    </div>
<script>
  // Hamburger animation toggle
  document.addEventListener('DOMContentLoaded', function() {
    var btn = document.querySelector('.hamburger-btn');
    var offcanvas = document.getElementById('sidebarMenu');
    if (btn && offcanvas) {
      offcanvas.addEventListener('show.bs.offcanvas', function() {
        btn.classList.add('open');
      });
      offcanvas.addEventListener('hide.bs.offcanvas', function() {
        btn.classList.remove('open');
      });
    }
  });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var invalid = document.querySelector('.is-invalid');
  if (invalid) {
    invalid.focus();
    invalid.scrollIntoView({behavior: 'smooth', block: 'center'});
  }
});
</script>
<?php
?>
<script>
const regionProvinceMap = {
  'NCR - National Capital Region': ['Metro Manila'],
  'Region IV-A - CALABARZON': ['Cavite', 'Laguna', 'Batangas'],
  'Region III - Central Luzon': ['Pampanga', 'Bulacan'],
};
const provinceCityMap = {
  'Metro Manila': ['Manila', 'Quezon City', 'Makati'],
  'Cavite': ['Dasmariñas', 'Imus', 'Bacoor'],
  'Laguna': ['Calamba', 'San Pablo'],
  'Batangas': ['Batangas City', 'Lipa'],
  'Pampanga': ['Angeles', 'San Fernando'],
  'Bulacan': ['Malolos', 'Meycauayan'],
};
const cityBarangayMap = {
  'Manila': ['Barangay 1', 'Barangay 2'],
  'Quezon City': ['Commonwealth', 'Batasan Hills'],
  'Makati': ['Bel-Air', 'Poblacion'],
  'Dasmariñas': ['Salitran', 'San Agustin'],
  'Imus': ['Buhay na Tubig', 'Medicion'],
  'Bacoor': ['Mambog', 'Salinas'],
  'Calamba': ['Canlubang', 'Real'],
  'San Pablo': ['San Rafael', 'Sta. Monica'],
  'Batangas City': ['Poblacion', 'Kumintang Ibaba'],
  'Lipa': ['Sabang', 'Antipolo del Norte'],
  'Angeles': ['Balibago', 'Pulung Maragul'],
  'San Fernando': ['San Agustin', 'Sto. Rosario'],
  'Malolos': ['Bulihan', 'Sto. Rosario'],
  'Meycauayan': ['Bahay Pare', 'Calvario'],
};


function updateProvince(regionSelectId, provinceSelectId, citySelectId, barangaySelectId) {
  const region = document.getElementById(regionSelectId).value;
  const provinceSelect = document.getElementById(provinceSelectId);
  const citySelect = document.getElementById(citySelectId);
  const barangaySelect = document.getElementById(barangaySelectId);
  provinceSelect.innerHTML = '<option value="" disabled selected>-- Select --</option>';
  citySelect.innerHTML = '<option value="" disabled selected>-- Select --</option>';
  barangaySelect.innerHTML = '<option value="" disabled selected>-- Select --</option>';
  if (regionProvinceMap[region]) {
    regionProvinceMap[region].forEach(function(prov) {
      provinceSelect.innerHTML += `<option value="${prov}">${prov}</option>`;
    });
  }
}
function updateCity(provinceSelectId, citySelectId, barangaySelectId) {
  const province = document.getElementById(provinceSelectId).value;
  const citySelect = document.getElementById(citySelectId);
  const barangaySelect = document.getElementById(barangaySelectId);
  citySelect.innerHTML = '<option value="" disabled selected>-- Select --</option>';
  barangaySelect.innerHTML = '<option value="" disabled selected>-- Select --</option>';
  if (provinceCityMap[province]) {
    provinceCityMap[province].forEach(function(city) {
      citySelect.innerHTML += `<option value="${city}">${city}</option>`;
    });
  }
}
function updateBarangay(citySelectId, barangaySelectId) {
  const city = document.getElementById(citySelectId).value;
  const barangaySelect = document.getElementById(barangaySelectId);
  barangaySelect.innerHTML = '<option value="" disabled selected>-- Select --</option>';
  if (cityBarangayMap[city]) {
    cityBarangayMap[city].forEach(function(brgy) {
      barangaySelect.innerHTML += `<option value="${brgy}">${brgy}</option>`;
    });
  }
}
function populateRegionSelect(selectId) {
  const select = document.getElementById(selectId);
  select.innerHTML = '<option value="" disabled selected>-- Select --</option>';
  Object.keys(regionProvinceMap).forEach(function(region) {
    select.innerHTML += `<option value="${region}">${region}</option>`;
  });
}

function resetAllDropdowns(form) {
  // Reset all select elements in the form to their first option (-- Select --)
  const selects = form.querySelectorAll('select');
  selects.forEach(function(select) {
    if (select.options.length > 0) {
      select.selectedIndex = 0; // Select the first option (-- Select --)
    }
  });
  
  // Also reset any checkboxes
  const checkboxes = form.querySelectorAll('input[type="checkbox"]');
  checkboxes.forEach(function(checkbox) {
    checkbox.checked = false;
  });
  
  // Reset any radio buttons
  const radios = form.querySelectorAll('input[type="radio"]');
  radios.forEach(function(radio) {
    radio.checked = false;
  });
}

// Repopulate selects after POST

</script>
<script>

</script>
<script>

</script>
