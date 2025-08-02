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

// Define required fields for College and SHS
$required_fields = [
    // Common required fields
    'course', 'last_name', 'first_name', 'dob', 'gender', 'civil_status', 'region', 'province', 'city', 'barangay', 'address', 'zip_code',
];

// Educational background fields (only for SHS)
$shs_required_fields = [
    'elementary_school', 'elementary_year_grad', 'high_school', 'high_year_grad', 'grade10_section',
];

// Additional required fields for College only
$college_required_fields = [
    'student_type',
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
    
    // Check if it's a college form and validate college-specific fields
    $is_college_form = isset($_POST['course']) && !in_array($_POST['course'], ['STEM', 'ABM', 'HUMSS', 'GAS']);
    $is_shs_form = isset($_POST['course']) && in_array($_POST['course'], ['STEM', 'ABM', 'HUMSS', 'GAS']);
    
    if ($is_college_form) {
        foreach ($college_required_fields as $field) {
            if (!isset($_POST[$field]) || trim($_POST[$field]) === '' || $_POST[$field] === '-- Select --') {
                $missing[] = $field;
            }
        }
        
        // For college form, email and mobile are required
        $college_contact_fields = ['email', 'mobile'];
        foreach ($college_contact_fields as $field) {
            if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                $missing[] = $field;
            }
        }
        
        // For college form, educational background fields are required based on student type
        if (isset($_POST['student_type'])) {
            if ($_POST['student_type'] === 'New') {
                // For new students: primary and secondary education required
                $new_required_fields = ['elementary_school', 'elementary_year_grad', 'high_school', 'high_year_grad'];
                foreach ($new_required_fields as $field) {
                    if (!isset($_POST[$field]) || trim($_POST[$field]) === '' || $_POST[$field] === '-- Select --') {
                        $missing[] = $field;
                    }
                }
            } elseif ($_POST['student_type'] === 'Transferee') {
                // For transferees: primary, secondary, and tertiary school required (but not year and course)
                $transferee_required_fields = ['elementary_school', 'elementary_year_grad', 'high_school', 'high_year_grad', 'tertiary_school'];
                foreach ($transferee_required_fields as $field) {
                    if (!isset($_POST[$field]) || trim($_POST[$field]) === '' || $_POST[$field] === '-- Select --') {
                        $missing[] = $field;
                    }
                }
            }
        }
    }
    
    if ($is_shs_form) {
        foreach ($shs_required_fields as $field) {
            if (!isset($_POST[$field]) || trim($_POST[$field]) === '' || $_POST[$field] === '-- Select --') {
                $missing[] = $field;
            }
        }
        
        // For SHS form, email and mobile are still required but are in the additional info section
        $shs_contact_fields = ['email', 'mobile'];
        foreach ($shs_contact_fields as $field) {
            if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                $missing[] = $field;
            }
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
        $active_section = isset($_POST['course']) && in_array($_POST['course'], ['STEM', 'ABM', 'HUMSS', 'GAS']) ? 'shs' : 'college';
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
        
        // Set course_or_track based on form type
        if (isset($_POST['course'])) {
            if (in_array($_POST['course'], ['STEM', 'ABM', 'HUMSS', 'GAS'])) {
                $_POST['course_or_track'] = $_POST['course'];
                $_POST['type'] = 'SHS';
            } else {
                $_POST['course_or_track'] = $_POST['course'];
                $_POST['type'] = 'College';
            }
        }
        
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
                
                // Set active section based on form type
                $active_section = isset($_POST['course']) && in_array($_POST['course'], ['STEM', 'ABM', 'HUMSS', 'GAS']) ? 'shs' : 'college';
            } else {
                $error = 'Database error: ' . $stmt->error;
                $active_section = isset($_POST['course']) && in_array($_POST['course'], ['STEM', 'ABM', 'HUMSS', 'GAS']) ? 'shs' : 'college';
            }
            $stmt->close();
        } else {
            $error = 'Database error: ' . $conn->error;
            $active_section = isset($_POST['course']) && in_array($_POST['course'], ['STEM', 'ABM', 'HUMSS', 'GAS']) ? 'shs' : 'college';
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
                    <a class="nav-link" id="nav-shs" href="#" onclick="showSidebarSection('shs')"><i class="bi bi-person-lines-fill"></i> Senior High School</a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link" id="nav-college" href="#" onclick="showSidebarSection('college')"><i class="bi bi-mortarboard"></i> College</a>
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
                <a class="nav-link" id="nav-shs-mobile" href="#" onclick="showSidebarSection('shs'); var offcanvas = bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('sidebarMenu')); offcanvas.hide();"><i class="bi bi-person-lines-fill"></i> Senior High School</a>
              </li>
              <li class="nav-item mb-2">
                <a class="nav-link" id="nav-college-mobile" href="#" onclick="showSidebarSection('college'); var offcanvas = bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('sidebarMenu')); offcanvas.hide();"><i class="bi bi-mortarboard"></i> College</a>
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
                        <div class="col-md-4">
                            <div class="card border-warning h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-warning">Senior High School</h5>
                                    <ul class="mb-0">
                                        <li>F138/Report Card (Original & Photocopied)</li>
                                        <li>Good Moral Character (Original with Dry Seal & Photocopied)</li>
                                        <li>Moving up Certificate (Photocopy of Diploma)</li>
                                        <li>2pcs. Photocopied Birth Certificate (PSA)</li>
                                        <li>2pcs. Photocopied Marriage Contract (PSA), if married</li>
                                        <li>4pcs. 2x2 Picture (White background with name tag)</li>
                                        <li>1pc. Long Brown Envelope</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-primary h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">College Freshmen</h5>
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
                        <div class="col-md-4">
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
                <!-- Senior High Form Section -->
                <div id="section-shs" style="display:none;">
                  <div class="row justify-content-center">
                    <div class="col-lg-10">
                      <div class="card border-warning mb-4">
                        <div class="card-body">
                          <h4 class="card-title text-warning mb-4">Senior High School Registration</h4>
                          <!-- Above SHS form -->
                          <div id="shsFormError" class="alert alert-danger" style="display:none;"></div>
                          <form id="shsForm" method="POST" action="">
                            <h5 class="bg-info text-white p-2 rounded">Desired Track/Strand</h5>
                            <div class="mb-3">
                              <label class="form-label">Desired Track/Strand</label>
                              <select class="form-select" name="course" required>
                                <option value="" selected disabled>-- Select --</option>
                                <option>STEM</option>
                                <option>ABM</option>
                                <option>HUMSS</option>
                                <option>GAS</option>
                              </select>
                            </div>
                            <h5 class="bg-info text-white p-2 rounded">Personal Information</h5>
                            <div class="row mb-3">
                              <div class="col-md-3"><label class="form-label">Family Name</label><input type="text" class="form-control" name="last_name" required></div>
                              <div class="col-md-3"><label class="form-label">Given Name</label><input type="text" class="form-control" name="first_name" required></div>
                              <div class="col-md-3"><label class="form-label">Middle Name</label><input type="text" class="form-control" name="middle_name"></div>
                              <div class="col-md-3"><label class="form-label">Suffix</label><input type="text" class="form-control" name="suffix"></div>
                            </div>
                            <div class="row mb-3">
                              <div class="col-md-3"><label class="form-label">Date Of Birth</label><input type="date" class="form-control" name="dob" required value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Place of Birth</label><input type="text" class="form-control" name="pob" value="<?= htmlspecialchars($_POST['pob'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Gender</label>
                                <select class="form-select" name="gender" required>
                                  <option value="" disabled <?= !isset($_POST['gender']) ? 'selected' : '' ?>>-- Select --</option>
                                  <option value="Male" <?= (isset($_POST['gender']) && $_POST['gender']=='Male') ? 'selected' : '' ?>>Male</option>
                                  <option value="Female" <?= (isset($_POST['gender']) && $_POST['gender']=='Female') ? 'selected' : '' ?>>Female</option>
                                </select>
                              </div>
                              <!-- For Senior High Civil Status Dropdown -->
                              <div class="col-md-3"><label class="form-label">Civil Status</label><select class="form-select" name="civil_status" required>
                                <option value="" disabled <?= !isset($_POST['civil_status']) ? 'selected' : '' ?>>-- Select --</option>
                                <option value="Single" <?= (isset($_POST['civil_status']) && $_POST['civil_status']=='Single') ? 'selected' : '' ?>>Single</option>
                                <option value="Married" <?= (isset($_POST['civil_status']) && $_POST['civil_status']=='Married') ? 'selected' : '' ?>>Married</option>
                                <option value="Widowed" <?= (isset($_POST['civil_status']) && $_POST['civil_status']=='Widowed') ? 'selected' : '' ?>>Widowed</option>
                                <option value="Separated" <?= (isset($_POST['civil_status']) && $_POST['civil_status']=='Separated') ? 'selected' : '' ?>>Separated</option>
                                <option value="Annulled" <?= (isset($_POST['civil_status']) && $_POST['civil_status']=='Annulled') ? 'selected' : '' ?>>Annulled</option>
                                <option value="Divorced" <?= (isset($_POST['civil_status']) && $_POST['civil_status']=='Divorced') ? 'selected' : '' ?>>Divorced</option>
                              </select></div>
                            </div>
                            <div class="row mb-3">
                              <div class="col-md-3"><label class="form-label">Nationality</label>
                                <select class="form-select" name="nationality" id="shs_nationality" required onchange="toggleOtherNationality('shs_nationality', 'shs_other_nationality')">
                                  <option value="" disabled <?= !isset($_POST['nationality']) ? 'selected' : '' ?>>-- Select --</option>
                                  <option value="Filipino" <?= (isset($_POST['nationality']) && $_POST['nationality']=='Filipino') ? 'selected' : '' ?>>Filipino</option>
                                  <option value="American" <?= (isset($_POST['nationality']) && $_POST['nationality']=='American') ? 'selected' : '' ?>>American</option>
                                  <option value="Chinese" <?= (isset($_POST['nationality']) && $_POST['nationality']=='Chinese') ? 'selected' : '' ?>>Chinese</option>
                                  <option value="Japanese" <?= (isset($_POST['nationality']) && $_POST['nationality']=='Japanese') ? 'selected' : '' ?>>Japanese</option>
                                  <option value="Korean" <?= (isset($_POST['nationality']) && $_POST['nationality']=='Korean') ? 'selected' : '' ?>>Korean</option>
                                  <option value="Indian" <?= (isset($_POST['nationality']) && $_POST['nationality']=='Indian') ? 'selected' : '' ?>>Indian</option>
                                  <option value="British" <?= (isset($_POST['nationality']) && $_POST['nationality']=='British') ? 'selected' : '' ?>>British</option>
                                  <option value="Australian" <?= (isset($_POST['nationality']) && $_POST['nationality']=='Australian') ? 'selected' : '' ?>>Australian</option>
                                  <option value="Canadian" <?= (isset($_POST['nationality']) && $_POST['nationality']=='Canadian') ? 'selected' : '' ?>>Canadian</option>
                                  <option value="German" <?= (isset($_POST['nationality']) && $_POST['nationality']=='German') ? 'selected' : '' ?>>German</option>
                                  <option value="French" <?= (isset($_POST['nationality']) && $_POST['nationality']=='French') ? 'selected' : '' ?>>French</option>
                                  <option value="Spanish" <?= (isset($_POST['nationality']) && $_POST['nationality']=='Spanish') ? 'selected' : '' ?>>Spanish</option>
                                  <option value="Italian" <?= (isset($_POST['nationality']) && $_POST['nationality']=='Italian') ? 'selected' : '' ?>>Italian</option>
                                  <option value="Vietnamese" <?= (isset($_POST['nationality']) && $_POST['nationality']=='Vietnamese') ? 'selected' : '' ?>>Vietnamese</option>
                                  <option value="Thai" <?= (isset($_POST['nationality']) && $_POST['nationality']=='Thai') ? 'selected' : '' ?>>Thai</option>
                                  <option value="Malaysian" <?= (isset($_POST['nationality']) && $_POST['nationality']=='Malaysian') ? 'selected' : '' ?>>Malaysian</option>
                                  <option value="Indonesian" <?= (isset($_POST['nationality']) && $_POST['nationality']=='Indonesian') ? 'selected' : '' ?>>Indonesian</option>
                                  <option value="Singaporean" <?= (isset($_POST['nationality']) && $_POST['nationality']=='Singaporean') ? 'selected' : '' ?>>Singaporean</option>
                                  <option value="Saudi" <?= (isset($_POST['nationality']) && $_POST['nationality']=='Saudi') ? 'selected' : '' ?>>Saudi</option>
                                  <option value="UAE" <?= (isset($_POST['nationality']) && $_POST['nationality']=='UAE') ? 'selected' : '' ?>>UAE</option>
                                  <option value="Other" <?= (isset($_POST['nationality']) && $_POST['nationality']=='Other') ? 'selected' : '' ?>>Other</option>
                                </select>
                                <input type="text" class="form-control mt-2" name="other_nationality" id="shs_other_nationality" placeholder="Please specify nationality" style="display:none;" value="<?= htmlspecialchars($_POST['other_nationality'] ?? '') ?>">
                              </div>
                              <!-- For Senior High Religion Dropdown -->
                              <div class="col-md-3">
                                <label class="form-label">Religion</label>
                                <select class="form-select" name="religion" id="shs_religion" required onchange="toggleOtherReligion('shs_religion', 'shs_other_religion')">
                                  <option value="" disabled <?= !isset($_POST['religion']) ? 'selected' : '' ?>>-- Select --</option>
                                  <option value="Catholic" <?= (isset($_POST['religion']) && $_POST['religion']=='Catholic') ? 'selected' : '' ?>>Catholic</option>
                                  <option value="Christian" <?= (isset($_POST['religion']) && $_POST['religion']=='Christian') ? 'selected' : '' ?>>Christian</option>
                                  <option value="Muslim" <?= (isset($_POST['religion']) && $_POST['religion']=='Muslim') ? 'selected' : '' ?>>Muslim</option>
                                  <option value="Iglesia ni Cristo" <?= (isset($_POST['religion']) && $_POST['religion']=='Iglesia ni Cristo') ? 'selected' : '' ?>>Iglesia ni Cristo</option>
                                  <option value="Buddhist" <?= (isset($_POST['religion']) && $_POST['religion']=='Buddhist') ? 'selected' : '' ?>>Buddhist</option>
                                  <option value="Hindu" <?= (isset($_POST['religion']) && $_POST['religion']=='Hindu') ? 'selected' : '' ?>>Hindu</option>
                                  <option value="Born Again" <?= (isset($_POST['religion']) && $_POST['religion']=='Born Again') ? 'selected' : '' ?>>Born Again</option>
                                  <option value="Seventh-day Adventist" <?= (isset($_POST['religion']) && $_POST['religion']=='Seventh-day Adventist') ? 'selected' : '' ?>>Seventh-day Adventist</option>
                                  <option value="Jehovah's Witnesses" <?= (isset($_POST['religion']) && $_POST['religion']=='Jehovah\'s Witnesses') ? 'selected' : '' ?>>Jehovah's Witnesses</option>
                                  <option value="None" <?= (isset($_POST['religion']) && $_POST['religion']=='None') ? 'selected' : '' ?>>None</option>
                                  <option value="Other" <?= (isset($_POST['religion']) && $_POST['religion']=='Other') ? 'selected' : '' ?>>Other</option>
                                </select>
                                <input type="text" class="form-control mt-2" name="other_religion" id="shs_other_religion" placeholder="Please specify religion" style="display:none;" value="<?= htmlspecialchars($_POST['other_religion'] ?? '') ?>">
                              </div>
                              <div class="col-md-3"><label class="form-label">Dialect/Language</label><input type="text" class="form-control" name="dialect" value="<?= htmlspecialchars($_POST['dialect'] ?? '') ?>"></div>
                            </div>
                            <h5 class="bg-info text-white p-2 rounded">Address/Contact Information</h5>
                            <div class="row mb-3">
                              <div class="col-md-10"><label class="form-label">Complete Address</label><input type="text" class="form-control" name="address" required value="<?= htmlspecialchars($_POST['address'] ?? '') ?>"></div>
                              <div class="col-md-2"><label class="form-label">Zip Code</label><input type="text" class="form-control" name="zip_code" required value="<?= htmlspecialchars($_POST['zip_code'] ?? '') ?>"></div>
                            </div>
                          
                            <!-- Senior High Form Dynamic Dropdowns -->
                            <div class="row mb-3">
                              <div class="col-md-3">
                                <label class="form-label">Region</label>
                                <select class="form-select" name="region" id="shs_region" required onchange="updateProvince('shs_region','shs_province','shs_city','shs_barangay')">
                                  <option value="" disabled selected>-- Select --</option>
                                </select>                                  
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Province</label>
                                <select class="form-select" name="province" id="shs_province" required onchange="updateCity('shs_province','shs_city','shs_barangay')">
                                  <option value="" disabled>-- Select --</option>
                                </select>
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Town/Municipality/City</label>
                                <select class="form-select" name="city" id="shs_city" required onchange="updateBarangay('shs_city','shs_barangay')">
                                  <option value="" disabled>-- Select --</option>
                                </select>
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Barangay</label>
                                <select class="form-select" name="barangay" id="shs_barangay" required>
                                  <option value="" disabled>-- Select --</option>
                                </select>
                              </div>
                            </div>
                            <div class="row mb-3">
                              <div class="col-md-4"><label class="form-label">Email Address</label><input type="email" class="form-control" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"></div>
                              <div class="col-md-4"><label class="form-label">Mobile No</label><input type="text" class="form-control" name="mobile" required value="<?= htmlspecialchars($_POST['mobile'] ?? '') ?>"></div>
                              <div class="col-md-4"><label class="form-label">Landline</label><input type="text" class="form-control" name="landline" value="<?= htmlspecialchars($_POST['landline'] ?? '') ?>"></div>
                            </div>
                            <!-- Educational Background Section (Senior High, styled as screenshot) -->
                            <h5 class="bg-info text-white p-2 rounded">Educational Background</h5>
                            <div class="row mb-3">
                              <div class="col-md-8">
                                <label class="form-label">Elementary School</label>
                                <select class="form-select" name="elementary_school">
                                  <option value="" selected>-- Select --</option>
                                  <option value="Dasmariñas Elementary School" <?= (isset($_POST['elementary_school']) && $_POST['elementary_school']=='Dasmariñas Elementary School') ? 'selected' : '' ?>>Dasmariñas Elementary School</option>
                                  <option value="Imus Central School" <?= (isset($_POST['elementary_school']) && $_POST['elementary_school']=='Imus Central School') ? 'selected' : '' ?>>Imus Central School</option>
                                  <option value="Bacoor Elementary School" <?= (isset($_POST['elementary_school']) && $_POST['elementary_school']=='Bacoor Elementary School') ? 'selected' : '' ?>>Bacoor Elementary School</option>
                                  <option value="Other" <?= (isset($_POST['elementary_school']) && $_POST['elementary_school']=='Other') ? 'selected' : '' ?>>Other</option>
                                </select>
                              </div>
                              <div class="col-md-4">
                                <label class="form-label">Year Graduated</label>
                                <input type="text" class="form-control" name="elementary_year_grad" value="<?= htmlspecialchars($_POST['elementary_year_grad'] ?? '') ?>">
                              </div>
                            </div>
                            <div class="row mb-3">
                              <div class="col-md-8">
                                <label class="form-label">High School</label>
                                <select class="form-select" name="high_school">
                                  <option value="" selected>-- Select --</option>
                                  <option value="Dasmariñas National High School" <?= (isset($_POST['high_school']) && $_POST['high_school']=='Dasmariñas National High School') ? 'selected' : '' ?>>Dasmariñas National High School</option>
                                  <option value="Imus National High School" <?= (isset($_POST['high_school']) && $_POST['high_school']=='Imus National High School') ? 'selected' : '' ?>>Imus National High School</option>
                                  <option value="Bacoor National High School" <?= (isset($_POST['high_school']) && $_POST['high_school']=='Bacoor National High School') ? 'selected' : '' ?>>Bacoor National High School</option>
                                  <option value="Other" <?= (isset($_POST['high_school']) && $_POST['high_school']=='Other') ? 'selected' : '' ?>>Other</option>
                                </select>
                              </div>
                              <div class="col-md-2">
                                <label class="form-label">Year Graduated</label>
                                <input type="text" class="form-control" name="high_year_grad" value="<?= htmlspecialchars($_POST['high_year_grad'] ?? '') ?>">
                              </div>
                              <div class="col-md-2">
                                <label class="form-label">Grade 10 Section</label>
                                <input type="text" class="form-control" name="grade10_section" value="<?= htmlspecialchars($_POST['grade10_section'] ?? '') ?>">
                              </div>
                            </div>
                            <!-- End Educational Background Section -->
                            <h5 class="bg-info text-white p-2 rounded">Father Information</h5>
                            <div class="row mb-3">
                              <div class="col-md-3"><label class="form-label">Family Name</label><input type="text" class="form-control" name="father_family_name" value="<?= htmlspecialchars($_POST['father_family_name'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Given Name</label><input type="text" class="form-control" name="father_given_name" value="<?= htmlspecialchars($_POST['father_given_name'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Middle Name</label><input type="text" class="form-control" name="father_middle_name" value="<?= htmlspecialchars($_POST['father_middle_name'] ?? '') ?>"></div>
                              <div class="col-md-3 d-flex align-items-end">
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" name="father_deceased" id="father_deceased" <?= (isset($_POST['father_deceased']) && $_POST['father_deceased']) ? 'checked' : '' ?>>
                                  <label class="form-check-label" for="father_deceased">Deceased?</label>
                                </div>
                              </div>
                            </div>
                            <div class="mb-3"><label class="form-label">Complete Address</label><input type="text" class="form-control" name="father_address" value="<?= htmlspecialchars($_POST['father_address'] ?? '') ?>"></div>
                            <div class="row mb-3">
                              <div class="col-md-3"><label class="form-label">Mobile No</label><input type="text" class="form-control" name="father_mobile" value="<?= htmlspecialchars($_POST['father_mobile'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Landline</label><input type="text" class="form-control" name="father_landline" value="<?= htmlspecialchars($_POST['father_landline'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Occupation</label><input type="text" class="form-control" name="father_occupation" value="<?= htmlspecialchars($_POST['father_occupation'] ?? '') ?>"></div>
                            </div>
                            <hr>
                            <h5 class="bg-info text-white p-2 rounded">Mother Information</h5>
                            <div class="row mb-3">
                              <div class="col-md-3"><label class="form-label">Mother Family Name</label><input type="text" class="form-control" name="mother_family_name" value="<?= htmlspecialchars($_POST['mother_family_name'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Mother Given Name</label><input type="text" class="form-control" name="mother_given_name" value="<?= htmlspecialchars($_POST['mother_given_name'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Mother Middle Name</label><input type="text" class="form-control" name="mother_middle_name" value="<?= htmlspecialchars($_POST['mother_middle_name'] ?? '') ?>"></div>
                              <div class="col-md-3 d-flex align-items-end">
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" name="mother_deceased" id="mother_deceased" <?= (isset($_POST['mother_deceased']) && $_POST['mother_deceased']) ? 'checked' : '' ?>>
                                  <label class="form-check-label" for="mother_deceased">Deceased?</label>
                                </div>
                              </div>
                            </div>
                            <div class="row mb-3">
                              <div class="col-md-4"><label class="form-label">Mother Maiden Family Name</label><input type="text" class="form-control" name="mother_maiden_family_name" value="<?= htmlspecialchars($_POST['mother_maiden_family_name'] ?? '') ?>"></div>
                              <div class="col-md-4"><label class="form-label">Mother Maiden Given Name</label><input type="text" class="form-control" name="mother_maiden_given_name" value="<?= htmlspecialchars($_POST['mother_maiden_given_name'] ?? '') ?>"></div>
                              <div class="col-md-4"><label class="form-label">Mother Maiden Middle Name</label><input type="text" class="form-control" name="mother_maiden_middle_name" value="<?= htmlspecialchars($_POST['mother_maiden_middle_name'] ?? '') ?>"></div>
                            </div>
                            <div class="mb-3"><label class="form-label">Complete Address</label><input type="text" class="form-control" name="mother_address" value="<?= htmlspecialchars($_POST['mother_address'] ?? '') ?>"></div>
                            <div class="row mb-3">
                              <div class="col-md-3"><label class="form-label">Mobile No</label><input type="text" class="form-control" name="mother_mobile" value="<?= htmlspecialchars($_POST['mother_mobile'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Landline</label><input type="text" class="form-control" name="mother_landline" value="<?= htmlspecialchars($_POST['mother_landline'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Occupation</label><input type="text" class="form-control" name="mother_occupation" value="<?= htmlspecialchars($_POST['mother_occupation'] ?? '') ?>"></div>
                            </div>
                            <hr>
                            <h5 class="bg-info text-white p-2 rounded">Guardian Information</h5>
                            <div class="row mb-3">
                              <div class="col-md-3">
                                <label class="form-label">Guardian Family Name</label>
                                <input type="text" class="form-control <?php if(isset($missing) && in_array('guardian_family_name', $missing)) echo 'is-invalid'; ?>" name="guardian_family_name" value="<?= htmlspecialchars($_POST['guardian_family_name'] ?? '') ?>">
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Guardian Given Name</label>
                                <input type="text" class="form-control <?php if(isset($missing) && in_array('guardian_given_name', $missing)) echo 'is-invalid'; ?>" name="guardian_given_name" value="<?= htmlspecialchars($_POST['guardian_given_name'] ?? '') ?>">
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Guardian Middle Name</label>
                                <input type="text" class="form-control" name="guardian_middle_name" value="<?= htmlspecialchars($_POST['guardian_middle_name'] ?? '') ?>">
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Relationship</label>
                                <select class="form-select <?php if(isset($missing) && in_array('guardian_relationship', $missing)) echo 'is-invalid'; ?>" name="guardian_relationship">
                                  <option value="" disabled <?= !isset($_POST['guardian_relationship']) ? 'selected' : '' ?>>-- Select --</option>
                                  <option value="Father" <?= (isset($_POST['guardian_relationship']) && $_POST['guardian_relationship']=='Father') ? 'selected' : '' ?>>Father</option>
                                  <option value="Mother" <?= (isset($_POST['guardian_relationship']) && $_POST['guardian_relationship']=='Mother') ? 'selected' : '' ?>>Mother</option>
                                  <option value="Sibling" <?= (isset($_POST['guardian_relationship']) && $_POST['guardian_relationship']=='Sibling') ? 'selected' : '' ?>>Sibling</option>
                                  <option value="Relative" <?= (isset($_POST['guardian_relationship']) && $_POST['guardian_relationship']=='Relative') ? 'selected' : '' ?>>Relative</option>
                                  <option value="Other" <?= (isset($_POST['guardian_relationship']) && $_POST['guardian_relationship']=='Other') ? 'selected' : '' ?>>Other</option>
                                </select>
                              </div>
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Complete Address</label>
                              <input type="text" class="form-control <?php if(isset($missing) && in_array('guardian_address', $missing)) echo 'is-invalid'; ?>" name="guardian_address" value="<?= htmlspecialchars($_POST['guardian_address'] ?? '') ?>">
                            </div>
                            <div class="row mb-3">
                              <div class="col-md-3">
                                <label class="form-label">Mobile No</label>
                                <input type="text" class="form-control <?php if(isset($missing) && in_array('guardian_mobile', $missing)) echo 'is-invalid'; ?>" name="guardian_mobile" value="<?= htmlspecialchars($_POST['guardian_mobile'] ?? '') ?>">
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Landline</label>
                                <input type="text" class="form-control" name="guardian_landline" value="<?= htmlspecialchars($_POST['guardian_landline'] ?? '') ?>">
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Occupation</label>
                                <input type="text" class="form-control <?php if(isset($missing) && in_array('guardian_occupation', $missing)) echo 'is-invalid'; ?>" name="guardian_occupation" value="<?= htmlspecialchars($_POST['guardian_occupation'] ?? '') ?>">
                              </div>
                            </div>
                            <div class="mb-3 text-end">
                              <button type="submit" class="btn btn-warning fw-bold" style="color: #003399;">Submit</button>
                              <button type="reset" class="btn btn-danger text-white ms-2">Cancel</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- College Registration Section (updated to match screenshot) -->
                <div id="section-college" style="display:block;">
                  <div class="row justify-content-center">
                    <div class="col-lg-10">
                      <div class="card border-primary mb-4">
                        <div class="card-body">
                          <h4 class="card-title text-primary mb-4">College Registration</h4>
                          <!-- Above College form -->
                          <div id="collegeFormError" class="alert alert-danger" style="display:none;"></div>
                          <form id="collegeForm" method="POST" action="">
                            <h5 class="bg-info text-white p-2 rounded">Desired Course</h5>
                            <div class="mb-3">
                              <label class="form-label">Desired Course</label>
                              <select class="form-select" name="course" required>
                                <option value="" selected disabled>-- Select --</option>
                                <option value="BSIT" <?= (isset($_POST['course']) && $_POST['course']=='BSIT') ? 'selected' : '' ?>>BSIT</option>
                                <option value="BSCS" <?= (isset($_POST['course']) && $_POST['course']=='BSCS') ? 'selected' : '' ?>>BSCS</option>
                                <option value="BSBA" <?= (isset($_POST['course']) && $_POST['course']=='BSBA') ? 'selected' : '' ?>>BSBA</option>
                                <option value="BSHM" <?= (isset($_POST['course']) && $_POST['course']=='BSHM') ? 'selected' : '' ?>>BSHM</option>
                                <option value="BSTM" <?= (isset($_POST['course']) && $_POST['course']=='BSTM') ? 'selected' : '' ?>>BSTM</option>
                                <option value="BSPSYCH" <?= (isset($_POST['course']) && $_POST['course']=='BSPSYCH') ? 'selected' : '' ?>>BSPSYCH</option>
                                <option value="BSCRIM" <?= (isset($_POST['course']) && $_POST['course']=='BSCRIM') ? 'selected' : '' ?>>BSCRIM</option>
                              </select>
                            </div>
                            <h5 class="bg-info text-white p-2 rounded">Personal Information</h5>
                            <div class="row mb-3">
                              <div class="col-md-3"><label class="form-label">Family Name</label><input type="text" class="form-control" name="last_name" required value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Given Name</label><input type="text" class="form-control" name="first_name" required value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Middle Name</label><input type="text" class="form-control" name="middle_name" value="<?= htmlspecialchars($_POST['middle_name'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Suffix</label><input type="text" class="form-control" name="suffix" value="<?= htmlspecialchars($_POST['suffix'] ?? '') ?>"></div>
                            </div>
                            <div class="row mb-3">
                              <div class="col-md-3"><label class="form-label">Date Of Birth</label><input type="date" class="form-control" name="dob" required value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Place of Birth</label><input type="text" class="form-control" name="pob" value="<?= htmlspecialchars($_POST['pob'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Gender</label>
                                <select class="form-select" name="gender" required>
                                  <option value="" disabled>-- Select --</option>
                                  <option value="Male" <?= (isset($_POST['gender']) && $_POST['gender']=='Male') ? 'selected' : '' ?>>Male</option>
                                  <option value="Female" <?= (isset($_POST['gender']) && $_POST['gender']=='Female') ? 'selected' : '' ?>>Female</option>
                                </select>
                              </div>
                            </div>
                            <div class="row mb-3">
                              <div class="col-md-3"><label class="form-label">Civil Status</label>
                                <select class="form-select" name="civil_status" required>
                                  <option value="" disabled>-- Select --</option>
                                  <option value="Single" <?= (isset($_POST['civil_status']) && $_POST['civil_status']=='Single') ? 'selected' : '' ?>>Single</option>
                                  <option value="Married" <?= (isset($_POST['civil_status']) && $_POST['civil_status']=='Married') ? 'selected' : '' ?>>Married</option>
                                  <option value="Widowed" <?= (isset($_POST['civil_status']) && $_POST['civil_status']=='Widowed') ? 'selected' : '' ?>>Widowed</option>
                                  <option value="Separated" <?= (isset($_POST['civil_status']) && $_POST['civil_status']=='Separated') ? 'selected' : '' ?>>Separated</option>
                                  <option value="Annulled" <?= (isset($_POST['civil_status']) && $_POST['civil_status']=='Annulled') ? 'selected' : '' ?>>Annulled</option>
                                  <option value="Divorced" <?= (isset($_POST['civil_status']) && $_POST['civil_status']=='Divorced') ? 'selected' : '' ?>>Divorced</option>
                                </select>
                              </div>
                              <div class="col-md-3"><label class="form-label">Mobile No</label><input type="text" class="form-control" name="mobile" required value="<?= htmlspecialchars($_POST['mobile'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Land Line</label><input type="text" class="form-control" name="landline" value="<?= htmlspecialchars($_POST['landline'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Email Address</label><input type="email" class="form-control" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"></div>
                            </div>
                            <div class="row mb-3">
                              <div class="col-md-10"><label class="form-label">Complete Address</label><input type="text" class="form-control" name="address" required value="<?= htmlspecialchars($_POST['address'] ?? '') ?>"></div>
                              <div class="col-md-2"><label class="form-label">Zip Code</label><input type="text" class="form-control" name="zip_code" required value="<?= htmlspecialchars($_POST['zip_code'] ?? '') ?>"></div>
                            </div>
                            <!-- Sample data for dynamic dropdowns -->
                          
                            <!-- College Form Dynamic Dropdowns -->
                            <div class="row mb-3">
                              <div class="col-md-3">
                                <label class="form-label">Region</label>
                                <select class="form-select" name="region" id="college_region" required onchange="updateProvince('college_region','college_province','college_city','college_barangay')">
                                  <option value="" disabled selected>-- Select --</option>
                                </select>
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Province</label>
                                <select class="form-select" name="province" id="college_province" required onchange="updateCity('college_province','college_city','college_barangay')">
                                  <option value="" disabled>-- Select --</option>
                                </select>
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Town/Municipality/City</label>
                                <select class="form-select" name="city" id="college_city" required onchange="updateBarangay('college_city','college_barangay')">
                                  <option value="" disabled>-- Select --</option>
                                </select>
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Barangay</label>
                                <select class="form-select" name="barangay" id="college_barangay" required>
                                  <option value="" disabled>-- Select --</option>
                                </select>
                              </div>
                            </div>
                            
                            <!-- Educational Information Section (College only - Primary, Secondary, Tertiary) -->
                            <h5 class="bg-info text-white p-2 rounded">Educational Information</h5>
                            <div class="row mb-3">
                              <div class="col-md-6">
                                <label class="form-label">Primary School</label>
                                <select class="form-select" name="elementary_school" id="elementary_school">
                                  <option value="" selected>-- Select --</option>
                                  <option value="Dasmariñas Elementary School" <?= (isset($_POST['elementary_school']) && $_POST['elementary_school']=='Dasmariñas Elementary School') ? 'selected' : '' ?>>Dasmariñas Elementary School</option>
                                  <option value="Imus Central School" <?= (isset($_POST['elementary_school']) && $_POST['elementary_school']=='Imus Central School') ? 'selected' : '' ?>>Imus Central School</option>
                                  <option value="Bacoor Elementary School" <?= (isset($_POST['elementary_school']) && $_POST['elementary_school']=='Bacoor Elementary School') ? 'selected' : '' ?>>Bacoor Elementary School</option>
                                  <option value="Other" <?= (isset($_POST['elementary_school']) && $_POST['elementary_school']=='Other') ? 'selected' : '' ?>>Other</option>
                                </select>
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Year Graduated</label>
                                <input type="text" class="form-control" name="elementary_year_grad" id="elementary_year_grad" value="<?= htmlspecialchars($_POST['elementary_year_grad'] ?? '') ?>">
                              </div>
                            </div>
                            <div class="row mb-3">
                              <div class="col-md-6">
                                <label class="form-label">Secondary School</label>
                                <select class="form-select" name="high_school" id="high_school">
                                  <option value="" selected>-- Select --</option>
                                  <option value="Dasmariñas National High School" <?= (isset($_POST['high_school']) && $_POST['high_school']=='Dasmariñas National High School') ? 'selected' : '' ?>>Dasmariñas National High School</option>
                                  <option value="Imus National High School" <?= (isset($_POST['high_school']) && $_POST['high_school']=='Imus National High School') ? 'selected' : '' ?>>Imus National High School</option>
                                  <option value="Bacoor National High School" <?= (isset($_POST['high_school']) && $_POST['high_school']=='Bacoor National High School') ? 'selected' : '' ?>>Bacoor National High School</option>
                                  <option value="Other" <?= (isset($_POST['high_school']) && $_POST['high_school']=='Other') ? 'selected' : '' ?>>Other</option>
                                </select>
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Year Graduated</label>
                                <input type="text" class="form-control" name="high_year_grad" id="high_year_grad" value="<?= htmlspecialchars($_POST['high_year_grad'] ?? '') ?>">
                              </div>
                            </div>
                            <div class="row mb-3">
                              <div class="col-md-6">
                                <label class="form-label">Tertiary School</label>
                                <select class="form-select" name="tertiary_school" id="tertiary_school">
                                  <option value="" selected>-- Select --</option>
                                  <option value="NCST" <?= (isset($_POST['tertiary_school']) && $_POST['tertiary_school']=='NCST') ? 'selected' : '' ?>>NCST</option>
                                  <option value="De La Salle University" <?= (isset($_POST['tertiary_school']) && $_POST['tertiary_school']=='De La Salle University') ? 'selected' : '' ?>>De La Salle University</option>
                                  <option value="University of the Philippines" <?= (isset($_POST['tertiary_school']) && $_POST['tertiary_school']=='University of the Philippines') ? 'selected' : '' ?>>University of the Philippines</option>
                                  <option value="Other" <?= (isset($_POST['tertiary_school']) && $_POST['tertiary_school']=='Other') ? 'selected' : '' ?>>Other</option>
                                </select>
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Year Graduated</label>
                                <input type="text" class="form-control" name="tertiary_year_grad" id="tertiary_year_grad" value="<?= htmlspecialchars($_POST['tertiary_year_grad'] ?? '') ?>">
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Course Graduated</label>
                                <input type="text" class="form-control" name="course_graduated" id="course_graduated" value="<?= htmlspecialchars($_POST['course_graduated'] ?? '') ?>">
                              </div>
                            </div>


                            <div class="row mb-3">
                              <div class="col-md-6">
                                <label class="form-label">Educational Plan</label>
                                <select class="form-select" name="educational_plan">
                                  <option value="" selected>-- Select --</option>
                                  <option value="Academic eXentials" <?= (isset($_POST['educational_plan']) && $_POST['educational_plan']=='Academic eXentials') ? 'selected' : '' ?>>Academic eXentials</option>
                                  <option value="Academix" <?= (isset($_POST['educational_plan']) && $_POST['educational_plan']=='Academix') ? 'selected' : '' ?>>Academix</option>
                                  <option value="AXA" <?= (isset($_POST['educational_plan']) && $_POST['educational_plan']=='AXA') ? 'selected' : '' ?>>AXA</option>
                                  <option value="BDO Life" <?= (isset($_POST['educational_plan']) && $_POST['educational_plan']=='BDO Life') ? 'selected' : '' ?>>BDO Life</option>
                                  <option value="CAP Pension Plan" <?= (isset($_POST['educational_plan']) && $_POST['educational_plan']=='CAP Pension Plan') ? 'selected' : '' ?>>CAP Pension Plan</option>
                                  <option value="Insular Life" <?= (isset($_POST['educational_plan']) && $_POST['educational_plan']=='Insular Life') ? 'selected' : '' ?>>Insular Life</option>
                                  <option value="Manulife Education Plan" <?= (isset($_POST['educational_plan']) && $_POST['educational_plan']=='Manulife Education Plan') ? 'selected' : '' ?>>Manulife Education Plan</option>
                                  <option value="Pru Life UK" <?= (isset($_POST['educational_plan']) && $_POST['educational_plan']=='Pru Life UK') ? 'selected' : '' ?>>Pru Life UK</option>
                                  <option value="SSS Educational Assistance" <?= (isset($_POST['educational_plan']) && $_POST['educational_plan']=='SSS Educational Assistance') ? 'selected' : '' ?>>SSS Educational Assistance</option>
                                  <option value="Sun Life Education Plan" <?= (isset($_POST['educational_plan']) && $_POST['educational_plan']=='Sun Life Education Plan') ? 'selected' : '' ?>>Sun Life Education Plan</option>
                                </select>
                              </div>
                              <div class="col-md-6">
                                <label class="form-label">Academic Achievement</label>
                                <select class="form-select" name="academic_achievement">
                                  <option value="" selected>-- Select --</option>
                                  <option value="Honorable Mention" <?= (isset($_POST['academic_achievement']) && $_POST['academic_achievement']=='Honorable Mention') ? 'selected' : '' ?>>Honorable Mention</option>
                                  <option value="Salutatorian" <?= (isset($_POST['academic_achievement']) && $_POST['academic_achievement']=='Salutatorian') ? 'selected' : '' ?>>Salutatorian</option>
                                  <option value="Valedictorian" <?= (isset($_POST['academic_achievement']) && $_POST['academic_achievement']=='Valedictorian') ? 'selected' : '' ?>>Valedictorian</option>
                                </select>
                              </div>
                            </div>
                            <!-- Work Information Section (no dropdowns, just checkboxes and text) -->
                            <h5 class="bg-info text-white p-2 rounded">Work Information</h5>
                            <div class="row mb-3 align-items-center">
                              <div class="col-md-2"><label class="form-label">Working?</label>
                                <input type="hidden" name="is_working" value="0">
                                <input type="checkbox" class="form-check-input ms-2" name="is_working" value="1" <?= (isset($_POST['is_working']) && $_POST['is_working']) ? 'checked' : '' ?>>
                              </div>
                              <div class="col-md-4"><label class="form-label">Employer</label><input type="text" class="form-control" name="employer" value="<?= htmlspecialchars($_POST['employer'] ?? '') ?>"></div>
                              <div class="col-md-2"><label class="form-label">Work in Shifts?</label>
                                <input type="hidden" name="work_in_shifts" value="0">
                                <input type="checkbox" class="form-check-input ms-2" name="work_in_shifts" value="1" <?= (isset($_POST['work_in_shifts']) && $_POST['work_in_shifts']) ? 'checked' : '' ?>>
                              </div>
                              <div class="col-md-4"><label class="form-label">Work Position</label><input type="text" class="form-control" name="work_position" value="<?= htmlspecialchars($_POST['work_position'] ?? '') ?>"></div>
                            </div>
                            <!-- Family Connected to NCST Section -->
                            <h5 class="bg-info text-white p-2 rounded">Family Connected to NCST</h5>
                            <div class="row mb-3 align-items-center">
                              <div class="col-md-2"><label class="form-label">NCST Student</label>
                                <input type="hidden" name="family_connected_ncst" value="0">
                                <input type="checkbox" class="form-check-input ms-2" name="family_connected_ncst" value="1" <?= (isset($_POST['family_connected_ncst']) && $_POST['family_connected_ncst']) ? 'checked' : '' ?>>
                              </div>
                              <div class="col-md-2"><label class="form-label">No of Siblings</label><input type="number" class="form-control" name="no_of_siblings" value="<?= htmlspecialchars($_POST['no_of_siblings'] ?? '') ?>"></div>
                              <div class="col-md-2"><label class="form-label">NCST Employee</label>
                                <input type="hidden" name="ncst_employee" value="0">
                                <input type="checkbox" class="form-check-input ms-2" name="ncst_employee" value="1" <?= (isset($_POST['ncst_employee']) && $_POST['ncst_employee']) ? 'checked' : '' ?>>
                              </div>
                              <div class="col-md-3"><label class="form-label">Relationship</label>
                                <select class="form-select" name="ncst_relationship">
                                  <option value="" selected>-- Select --</option>
                                  <option value="Aunt-NCST Employee" <?= (isset($_POST['ncst_relationship']) && $_POST['ncst_relationship']=='Aunt-NCST Employee') ? 'selected' : '' ?>>Aunt-NCST Employee</option>
                                  <option value="Brother-NCST Employee" <?= (isset($_POST['ncst_relationship']) && $_POST['ncst_relationship']=='Brother-NCST Employee') ? 'selected' : '' ?>>Brother-NCST Employee</option>
                                  <option value="Father-NCST Employee" <?= (isset($_POST['ncst_relationship']) && $_POST['ncst_relationship']=='Father-NCST Employee') ? 'selected' : '' ?>>Father-NCST Employee</option>
                                  <option value="Mother-NCST Employee" <?= (isset($_POST['ncst_relationship']) && $_POST['ncst_relationship']=='Mother-NCST Employee') ? 'selected' : '' ?>>Mother-NCST Employee</option>
                                  <option value="No NCST Relations" <?= (isset($_POST['ncst_relationship']) && $_POST['ncst_relationship']=='No NCST Relations') ? 'selected' : '' ?>>No NCST Relations</option>
                                  <option value="Sister-NCST Employee" <?= (isset($_POST['ncst_relationship']) && $_POST['ncst_relationship']=='Sister-NCST Employee') ? 'selected' : '' ?>>Sister-NCST Employee</option>
                                  <option value="Uncle-NCST Employee" <?= (isset($_POST['ncst_relationship']) && $_POST['ncst_relationship']=='Uncle-NCST Employee') ? 'selected' : '' ?>>Uncle-NCST Employee</option>
                                </select>
                              </div>
                            </div>

                            <!-- How did Student come to know about NCST? -->
                            <h5 class="bg-info text-white p-2 rounded">How did Student come to know about NCST?</h5>
                            <div class="mb-3">
                              <select class="form-select" name="how_did_you_know_ncst">
                                <option value="" selected>-- Select --</option>
                                <option value="CareerTalk" <?= (isset($_POST['how_did_you_know_ncst']) && $_POST['how_did_you_know_ncst']=='CareerTalk') ? 'selected' : '' ?>>CareerTalk</option>
                                <option value="Posters" <?= (isset($_POST['how_did_you_know_ncst']) && $_POST['how_did_you_know_ncst']=='Posters') ? 'selected' : '' ?>>Posters</option>
                                <option value="Leaflets" <?= (isset($_POST['how_did_you_know_ncst']) && $_POST['how_did_you_know_ncst']=='Leaflets') ? 'selected' : '' ?>>Leaflets</option>
                                <option value="Friends/Relatives" <?= (isset($_POST['how_did_you_know_ncst']) && $_POST['how_did_you_know_ncst']=='Friends/Relatives') ? 'selected' : '' ?>>Friends/Relatives</option>
                                <option value="Billboards/Streamers" <?= (isset($_POST['how_did_you_know_ncst']) && $_POST['how_did_you_know_ncst']=='Billboards/Streamers') ? 'selected' : '' ?>>Billboards/Streamers</option>
                                <option value="Others" <?= (isset($_POST['how_did_you_know_ncst']) && $_POST['how_did_you_know_ncst']=='Others') ? 'selected' : '' ?>>Others</option>
                              </select>
                            </div>
                            <!-- Other Information Section -->
                            <h5 class="bg-info text-white p-2 rounded">Other Information</h5>
                            <div class="mb-3">
                              <label class="form-label">Student Type</label>
                              <select class="form-select" name="student_type" id="student_type" required onchange="toggleEducationalFields()">
                                <option value="" selected disabled>-- Select --</option>
                                <option value="New" <?= (isset($_POST['student_type']) && $_POST['student_type']=='New') ? 'selected' : '' ?>>New</option>
                                <option value="Transferee" <?= (isset($_POST['student_type']) && $_POST['student_type']=='Transferee') ? 'selected' : '' ?>>Transferee</option>
                                <option value="Returnee" <?= (isset($_POST['student_type']) && $_POST['student_type']=='Returnee') ? 'selected' : '' ?>>Returnee</option>
                                <option value="Foreign" <?= (isset($_POST['student_type']) && $_POST['student_type']=='Foreign') ? 'selected' : '' ?>>Foreign</option>
                                <option value="ALS Graduate" <?= (isset($_POST['student_type']) && $_POST['student_type']=='ALS Graduate') ? 'selected' : '' ?>>ALS Graduate</option>
                                <option value="DTS Student" <?= (isset($_POST['student_type']) && $_POST['student_type']=='DTS Student') ? 'selected' : '' ?>>DTS Student</option>
                                <option value="Cross Enrollee" <?= (isset($_POST['student_type']) && $_POST['student_type']=='Cross Enrollee') ? 'selected' : '' ?>>Cross Enrollee</option>
                              </select>
                            </div>
                            <!-- Parent/Guardian Information Section (no dropdowns, just text fields and checkboxes) -->
                            <h5 class="bg-info text-white p-2 rounded">Parent/Guardian Information</h5>
                            <div class="row mb-3">
                              <div class="col-md-3"><label class="form-label">Father Family Name</label><input type="text" class="form-control" name="father_family_name" value="<?= htmlspecialchars($_POST['father_family_name'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Father Given Name</label><input type="text" class="form-control" name="father_given_name" value="<?= htmlspecialchars($_POST['father_given_name'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Father Middle Name</label><input type="text" class="form-control" name="father_middle_name" value="<?= htmlspecialchars($_POST['father_middle_name'] ?? '') ?>"></div>
                              <div class="col-md-3 d-flex align-items-end">
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" name="father_deceased" id="father_deceased" <?= (isset($_POST['father_deceased']) && $_POST['father_deceased']) ? 'checked' : '' ?>>
                                  <label class="form-check-label" for="father_deceased">Deceased?</label>
                                </div>
                              </div>
                            </div>
                            <div class="mb-3"><label class="form-label">Father's Complete Address</label><input type="text" class="form-control" name="father_address" value="<?= htmlspecialchars($_POST['father_address'] ?? '') ?>"></div>
                            <div class="row mb-3">
                              <div class="col-md-3"><label class="form-label">Father's Mobile No</label><input type="text" class="form-control" name="father_mobile" value="<?= htmlspecialchars($_POST['father_mobile'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Father's Land Line</label><input type="text" class="form-control" name="father_landline" value="<?= htmlspecialchars($_POST['father_landline'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Father's Occupation</label><input type="text" class="form-control" name="father_occupation" value="<?= htmlspecialchars($_POST['father_occupation'] ?? '') ?>"></div>
                            </div>
                            <hr>
                            <div class="row mb-3">
                              <div class="col-md-3"><label class="form-label">Mother Family Name</label><input type="text" class="form-control" name="mother_family_name" value="<?= htmlspecialchars($_POST['mother_family_name'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Mother Given Name</label><input type="text" class="form-control" name="mother_given_name" value="<?= htmlspecialchars($_POST['mother_given_name'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Mother Middle Name</label><input type="text" class="form-control" name="mother_middle_name" value="<?= htmlspecialchars($_POST['mother_middle_name'] ?? '') ?>"></div>
                              <div class="col-md-3 d-flex align-items-end">
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" name="mother_deceased" id="mother_deceased" <?= (isset($_POST['mother_deceased']) && $_POST['mother_deceased']) ? 'checked' : '' ?>>
                                  <label class="form-check-label" for="mother_deceased">Deceased?</label>
                                </div>
                              </div>
                            </div>
                            <div class="row mb-3">
                              <div class="col-md-4"><label class="form-label">Mother Maiden Family Name</label><input type="text" class="form-control" name="mother_maiden_family_name" value="<?= htmlspecialchars($_POST['mother_maiden_family_name'] ?? '') ?>"></div>
                              <div class="col-md-4"><label class="form-label">Mother Maiden Given Name</label><input type="text" class="form-control" name="mother_maiden_given_name" value="<?= htmlspecialchars($_POST['mother_maiden_given_name'] ?? '') ?>"></div>
                              <div class="col-md-4"><label class="form-label">Mother Maiden Middle Name</label><input type="text" class="form-control" name="mother_maiden_middle_name" value="<?= htmlspecialchars($_POST['mother_maiden_middle_name'] ?? '') ?>"></div>
                            </div>
                            <div class="mb-3"><label class="form-label">Mother's Complete Address</label><input type="text" class="form-control" name="mother_address" value="<?= htmlspecialchars($_POST['mother_address'] ?? '') ?>"></div>
                            <div class="row mb-3">
                              <div class="col-md-3"><label class="form-label">Mother's Mobile No</label><input type="text" class="form-control" name="mother_mobile" value="<?= htmlspecialchars($_POST['mother_mobile'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Mother's Land Line</label><input type="text" class="form-control" name="mother_landline" value="<?= htmlspecialchars($_POST['mother_landline'] ?? '') ?>"></div>
                              <div class="col-md-3"><label class="form-label">Mother's Occupation</label><input type="text" class="form-control" name="mother_occupation" value="<?= htmlspecialchars($_POST['mother_occupation'] ?? '') ?>"></div>
                            </div>
                            <hr>
                            <h5 class="bg-info text-white p-2 rounded">Guardian Information</h5>
                            <div class="row mb-3">
                              <div class="col-md-3">
                                <label class="form-label">Guardian Family Name</label>
                                <input type="text" class="form-control <?php if(isset($missing) && in_array('guardian_family_name', $missing)) echo 'is-invalid'; ?>" name="guardian_family_name" value="<?= htmlspecialchars($_POST['guardian_family_name'] ?? '') ?>">
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Guardian Given Name</label>
                                <input type="text" class="form-control <?php if(isset($missing) && in_array('guardian_given_name', $missing)) echo 'is-invalid'; ?>" name="guardian_given_name" value="<?= htmlspecialchars($_POST['guardian_given_name'] ?? '') ?>">
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Guardian Middle Name</label>
                                <input type="text" class="form-control" name="guardian_middle_name" value="<?= htmlspecialchars($_POST['guardian_middle_name'] ?? '') ?>">
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Relationship</label>
                                <select class="form-select <?php if(isset($missing) && in_array('guardian_relationship', $missing)) echo 'is-invalid'; ?>" name="guardian_relationship">
                                  <option value="" disabled <?= !isset($_POST['guardian_relationship']) ? 'selected' : '' ?>>-- Select --</option>
                                  <option value="Father" <?= (isset($_POST['guardian_relationship']) && $_POST['guardian_relationship']=='Father') ? 'selected' : '' ?>>Father</option>
                                  <option value="Mother" <?= (isset($_POST['guardian_relationship']) && $_POST['guardian_relationship']=='Mother') ? 'selected' : '' ?>>Mother</option>
                                  <option value="Sibling" <?= (isset($_POST['guardian_relationship']) && $_POST['guardian_relationship']=='Sibling') ? 'selected' : '' ?>>Sibling</option>
                                  <option value="Relative" <?= (isset($_POST['guardian_relationship']) && $_POST['guardian_relationship']=='Relative') ? 'selected' : '' ?>>Relative</option>
                                  <option value="Other" <?= (isset($_POST['guardian_relationship']) && $_POST['guardian_relationship']=='Other') ? 'selected' : '' ?>>Other</option>
                                </select>
                              </div>
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Complete Address</label>
                              <input type="text" class="form-control <?php if(isset($missing) && in_array('guardian_address', $missing)) echo 'is-invalid'; ?>" name="guardian_address" value="<?= htmlspecialchars($_POST['guardian_address'] ?? '') ?>">
                            </div>
                            <div class="row mb-3">
                              <div class="col-md-3">
                                <label class="form-label">Mobile No</label>
                                <input type="text" class="form-control <?php if(isset($missing) && in_array('guardian_mobile', $missing)) echo 'is-invalid'; ?>" name="guardian_mobile" value="<?= htmlspecialchars($_POST['guardian_mobile'] ?? '') ?>">
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Landline</label>
                                <input type="text" class="form-control" name="guardian_landline" value="<?= htmlspecialchars($_POST['guardian_landline'] ?? '') ?>">
                              </div>
                              <div class="col-md-3">
                                <label class="form-label">Occupation</label>
                                <input type="text" class="form-control <?php if(isset($missing) && in_array('guardian_occupation', $missing)) echo 'is-invalid'; ?>" name="guardian_occupation" value="<?= htmlspecialchars($_POST['guardian_occupation'] ?? '') ?>">
                              </div>
                            </div>
                            <div class="mb-3 text-end">
                              <button type="submit" class="btn btn-warning fw-bold" style="color: #003399;">Submit</button>
                              <button type="reset" class="btn btn-danger text-white ms-2">Cancel</button>
                            </div>
                          </form>
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
            document.getElementById('section-shs').style.display = (section === 'shs') ? 'block' : 'none';
            document.getElementById('section-college').style.display = (section === 'college') ? 'block' : 'none';
            document.getElementById('nav-req').classList.toggle('active', section === 'requirements');
            document.getElementById('nav-shs').classList.toggle('active', section === 'shs');
            document.getElementById('nav-college').classList.toggle('active', section === 'college');
        }
        // Default to requirements, but override if PHP says so
        <?php if (isset($active_section)): ?>
          showSidebarSection('<?php echo $active_section; ?>');
        <?php else: ?>
          showSidebarSection('requirements');
        <?php endif; ?>
    </script>
    <script>
function toggleOtherNationality(selectId, inputId) {
  var select = document.getElementById(selectId);
  var input = document.getElementById(inputId);
  if (select.value === 'Other') {
    input.style.display = 'block';
    input.required = true;
  } else {
    input.style.display = 'none';
    input.required = false;
    input.value = '';
  }
}
</script>
<script>
function toggleOtherReligion(selectId, inputId) {
  var select = document.getElementById(selectId);
  var input = document.getElementById(inputId);
  if (select.value === 'Other') {
    input.style.display = 'block';
    input.required = true;
  } else {
    input.style.display = 'none';
    input.required = false;
    input.value = '';
  }
}

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
document.addEventListener('DOMContentLoaded', function() {
  // SHS
  <?php if (isset($_POST['region'])): ?>
    document.getElementById('shs_region').value = "<?= addslashes($_POST['region']) ?>";
    updateProvince('shs_region','shs_province','shs_city','shs_barangay');
    <?php if (isset($_POST['province'])): ?>
      document.getElementById('shs_province').value = "<?= addslashes($_POST['province']) ?>";
      updateCity('shs_province','shs_city','shs_barangay');
      <?php if (isset($_POST['city'])): ?>
        document.getElementById('shs_city').value = "<?= addslashes($_POST['city']) ?>";
        updateBarangay('shs_city','shs_barangay');
        <?php if (isset($_POST['barangay'])): ?>
          document.getElementById('shs_barangay').value = "<?= addslashes($_POST['barangay']) ?>";
        <?php endif; ?>
      <?php endif; ?>
    <?php endif; ?>
  <?php endif; ?>

  // College
  <?php if (isset($_POST['region'])): ?>
    document.getElementById('college_region').value = "<?= addslashes($_POST['region']) ?>";
    updateProvince('college_region','college_province','college_city','college_barangay');
    <?php if (isset($_POST['province'])): ?>
      document.getElementById('college_province').value = "<?= addslashes($_POST['province']) ?>";
      updateCity('college_province','college_city','college_barangay');
      <?php if (isset($_POST['city'])): ?>
        document.getElementById('college_city').value = "<?= addslashes($_POST['city']) ?>";
        updateBarangay('college_city','college_barangay');
        <?php if (isset($_POST['barangay'])): ?>
          document.getElementById('college_barangay').value = "<?= addslashes($_POST['barangay']) ?>";
        <?php endif; ?>
      <?php endif; ?>
    <?php endif; ?>
  <?php endif; ?>
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  populateRegionSelect('shs_region');
  populateRegionSelect('college_region');
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // For SHS form
  var shsForm = document.getElementById('shsForm');
  var shsFormError = document.getElementById('shsFormError');
  if (shsForm) {
    console.log('SHS Form found and event listener added');
    shsForm.addEventListener('submit', function(e) {
      e.preventDefault();
      console.log('SHS Form submitted');
      // Validate required fields before AJAX
      let firstInvalid = null;
      shsForm.querySelectorAll('[required]').forEach(function(field) {
        if (!field.value || (field.tagName === 'SELECT' && field.value === '')) {
          field.classList.add('is-invalid');
          if (!firstInvalid) firstInvalid = field;
        } else {
          field.classList.remove('is-invalid');
        }
      });
      if (firstInvalid) {
        shsFormError.style.display = 'block';
        shsFormError.textContent = 'Please fill in all required fields.';
        firstInvalid.focus();
        firstInvalid.scrollIntoView({behavior: 'smooth', block: 'center'});
        return; // Stop AJAX if invalid
      } else {
        shsFormError.style.display = 'none';
      }
      var formData = new FormData(shsForm);
      console.log('SHS FormData created, sending fetch request...');
      fetch('', {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(html => {
        console.log('SHS Response received:', html.substring(0, 500) + '...');
        var parser = new DOMParser();
        var doc = parser.parseFromString(html, 'text/html');
        var successDiv = doc.querySelector('#successMessage');
        console.log('SHS Success div found:', successDiv);
        if (successDiv && successDiv.innerHTML.trim() !== '') {
          console.log('SHS Success message found, showing modal');
          showNcstModal(
            'Application Submitted',
            successDiv.innerHTML,
            'Close',
            false,
            function() {
              // Close modal - no additional action needed
            }
          );
          shsForm.reset();
          // Reset dropdowns to initial state
          populateRegionSelect('shs_region');
          updateProvince('shs_region','shs_province','shs_city','shs_barangay');
          // Reset all other dropdowns to default
          resetAllDropdowns(shsForm);
        } else {
          console.log('SHS No success message found');
          // Show error if no success message
          shsFormError.style.display = 'block';
          shsFormError.textContent = 'There was an error submitting your application. Please try again.';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        shsFormError.style.display = 'block';
        shsFormError.textContent = 'There was an error submitting your application. Please try again. Error: ' + error.message;
      });
    });
  }
  // For College form
  var collegeForm = document.getElementById('collegeForm');
  var collegeFormError = document.getElementById('collegeFormError');
  if (collegeForm) {
    console.log('College Form found and event listener added');
    collegeForm.addEventListener('submit', function(e) {
      e.preventDefault();
      console.log('College Form submitted');
      // Validate required fields before AJAX
      let firstInvalid = null;
      collegeForm.querySelectorAll('[required]').forEach(function(field) {
        if (!field.value || (field.tagName === 'SELECT' && field.value === '')) {
          field.classList.add('is-invalid');
          if (!firstInvalid) firstInvalid = field;
        } else {
          field.classList.remove('is-invalid');
        }
      });
      if (firstInvalid) {
        collegeFormError.style.display = 'block';
        collegeFormError.textContent = 'Please fill in all required fields.';
        firstInvalid.focus();
        firstInvalid.scrollIntoView({behavior: 'smooth', block: 'center'});
        return; // Stop AJAX if invalid
      } else {
        collegeFormError.style.display = 'none';
      }
      var formData = new FormData(collegeForm);
      console.log('College FormData created, sending fetch request...');
      fetch('', {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(html => {
        var parser = new DOMParser();
        var doc = parser.parseFromString(html, 'text/html');
        var successDiv = doc.querySelector('#successMessage');
        if (successDiv && successDiv.innerHTML.trim() !== '') {
          showNcstModal(
            'Application Submitted',
            successDiv.innerHTML,
            'Close',
            false,
            function() {
              // Close modal - no additional action needed
            }
          );
          collegeForm.reset();
          // Reset dropdowns to initial state
          populateRegionSelect('college_region');
          updateProvince('college_region','college_province','college_city','college_barangay');
          // Reset all other dropdowns to default
          resetAllDropdowns(collegeForm);
        } else {
          // Show error if no success message
          collegeFormError.style.display = 'block';
          collegeFormError.textContent = 'There was an error submitting your application. Please try again.';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        collegeFormError.style.display = 'block';
        collegeFormError.textContent = 'There was an error submitting your application. Please try again. Error: ' + error.message;
      });
    });
  }
});
</script>
