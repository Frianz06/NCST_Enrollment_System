<?php
session_start();
require_once '../../db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Officer Dashboard | NCST</title>
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
            color: #fff;
        }
        .table-danger thead {
            background: #dc3545 !important;
            color: #fff;
        }
        .badge.bg-success {
            font-size: 0.8rem;
            padding: 0.4rem 0.6rem;
        }
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
        <h5 class="mb-4">NCST Admission</h5>
        <nav class="nav flex-column w-100">
            <a class="nav-link active" href="#pending-section" data-bs-toggle="tab">Pending Applications</a>
            <a class="nav-link" href="#approved-section" data-bs-toggle="tab">Approved Applications</a>
            <a class="nav-link" href="#rejected-section" data-bs-toggle="tab">Rejected Applications</a>
            <a class="nav-link" href="#college-registration-section" data-bs-toggle="tab">College Registration</a>
        </nav>
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
        <h5 class="offcanvas-title" id="sidebarMenuLabel">NCST Admission</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <div class="text-center mb-4">
          <div class="logo d-inline-block">
            <img src="../../images/ncst-logo.png" alt="NCST Logo" style="max-width: 60px;">
          </div>
        </div>
        <nav class="nav flex-column">
          <a class="nav-link active" href="#pending-section" data-bs-toggle="tab">Pending Applications</a>
          <a class="nav-link" href="#approved-section" data-bs-toggle="tab">Approved Applications</a>
          <a class="nav-link" href="#rejected-section" data-bs-toggle="tab">Rejected Applications</a>
          <a class="nav-link" href="#college-registration-section" data-bs-toggle="tab">College Registration</a>
        </nav>
      </div>
    </div>
    <div class="topbar d-flex align-items-center justify-content-end">
        <span class="me-3">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="../logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
    </div>
    <div class="main-content">
        <div class="tab-content">
            <!-- Pending Applications Tab -->
            <div class="tab-pane fade show active" id="pending-section">
                <div class="card-ncst p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h3 class="fw-bold mb-0" style="color:#003399;">Pending Applications</h3>
                            <small class="text-muted" id="pendingCount"></small>
                        </div>
                        <div class="input-group" style="max-width: 320px;">
                            <input type="text" id="searchPendingInput" class="form-control" placeholder="Search by Tracking # or Surname...">
                            <span class="input-group-text bg-primary text-white"><i class="bi bi-search"></i></span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="pendingTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Tracking #</th>
                                    <th>Full Name</th>
                                    <th>Type</th>
                                    <th>Course/Track</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="pendingTableBody">
                                <!-- AJAX loaded rows -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Approved Applications Tab -->
            <div class="tab-pane fade" id="approved-section">
                <div class="card-ncst p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h3 class="fw-bold mb-0" style="color:#28a745;">Approved Applications</h3>
                            <small class="text-muted" id="approvedCount"></small>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="input-group" style="max-width: 250px;">
                                <input type="text" id="searchApprovedInput" class="form-control" placeholder="Search by Tracking # or Surname...">
                                <span class="input-group-text bg-success text-white"><i class="bi bi-search"></i></span>
                            </div>
                            <button class="btn btn-primary" onclick="enrollAllApproved()">
                                <i class="bi bi-graduation-cap"></i> Enroll Students
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="approvedTable">
                            <thead class="table-success">
                                <tr>
                                    <th>Tracking #</th>
                                    <th>Full Name</th>
                                    <th>Type</th>
                                    <th>Course/Track</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="approvedTableBody">
                                <!-- AJAX loaded rows -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Rejected Applications Tab -->
            <div class="tab-pane fade" id="rejected-section">
                <div class="card-ncst p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h3 class="fw-bold mb-0" style="color:#dc3545;">Rejected Applications</h3>
                            <small class="text-muted" id="rejectedCount"></small>
                        </div>
                        <div class="input-group" style="max-width: 320px;">
                            <input type="text" id="searchRejectedInput" class="form-control" placeholder="Search by Tracking # or Surname...">
                            <span class="input-group-text bg-danger text-white"><i class="bi bi-search"></i></span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="rejectedTable">
                            <thead class="table-danger">
                                <tr>
                                    <th>Tracking #</th>
                                    <th>Full Name</th>
                                    <th>Type</th>
                                    <th>Course/Track</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="rejectedTableBody">
                                <!-- AJAX loaded rows -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- College Registration Tab -->
            <div class="tab-pane fade" id="college-registration-section">
                <div class="card-ncst p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h3 class="fw-bold mb-0" style="color:#003399;">College Registration</h3>
                            <small class="text-muted">Register new college students</small>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="card border-primary mb-4">
                                <div class="card-body">
                                    <div id="collegeFormError" class="alert alert-danger" style="display:none;"></div>
                                    <div id="collegeFormSuccess" class="alert alert-success" style="display:none;"></div>
                                    <form id="collegeForm" method="POST" action="">
                                        <h5 class="bg-info text-white p-2 rounded">Desired Course</h5>
                                        <div class="mb-3">
                                            <label class="form-label">Desired Course</label>
                                            <select class="form-select" name="course" required>
                                                <option value="" selected disabled>-- Select --</option>
                                                <option value="BSIT">BSIT</option>
                                                <option value="BSCS">BSCS</option>
                                                <option value="BSBA">BSBA</option>
                                                <option value="BSHM">BSHM</option>
                                                <option value="BSTM">BSTM</option>
                                                <option value="BSPSYCH">BSPSYCH</option>
                                                <option value="BSCRIM">BSCRIM</option>
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
                                            <div class="col-md-3"><label class="form-label">Date Of Birth</label><input type="date" class="form-control" name="dob" required></div>
                                            <div class="col-md-3"><label class="form-label">Place of Birth</label><input type="text" class="form-control" name="pob"></div>
                                            <div class="col-md-3"><label class="form-label">Gender</label>
                                                <select class="form-select" name="gender" required>
                                                    <option value="" disabled selected>-- Select --</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3"><label class="form-label">Civil Status</label>
                                                <select class="form-select" name="civil_status" required>
                                                    <option value="" disabled selected>-- Select --</option>
                                                    <option value="Single">Single</option>
                                                    <option value="Married">Married</option>
                                                    <option value="Widowed">Widowed</option>
                                                    <option value="Separated">Separated</option>
                                                    <option value="Annulled">Annulled</option>
                                                    <option value="Divorced">Divorced</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">

                                            <div class="col-md-3"><label class="form-label">Nationality</label><input type="text" class="form-control" name="nationality" value="Filipino"></div>
                                            <div class="col-md-3"><label class="form-label">Religion</label><input type="text" class="form-control" name="religion"></div>
                                            <div class="col-md-3"><label class="form-label">Dialect</label><input type="text" class="form-control" name="dialect"></div>
                                        </div>
                                        <div class="row mb-3">


                                            <div class="col-md-3"><label class="form-label">Mobile No</label><input type="text" class="form-control" name="mobile" required></div>
                                            <div class="col-md-3"><label class="form-label">Land Line</label><input type="text" class="form-control" name="landline"></div>
                                            <div class="col-md-3"><label class="form-label">Email Address</label><input type="email" class="form-control" name="email" required></div>
                                            <div class="col-md-3"><label class="form-label">Zip Code</label><input type="text" class="form-control" name="zip_code" required></div>
                                        </div>


                                        <div class="row mb-3">
                                            <div class="col-md-4"><label class="form-label">Nationality</label><input type="text" class="form-control" name="nationality" value="Filipino"></div>
                                            <div class="col-md-4"><label class="form-label">Religion</label><input type="text" class="form-control" name="religion"></div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Complete Address</label>
                                            <input type="text" class="form-control" name="address" required>
                                        </div>
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
                                                    <option value="" disabled selected>-- Select --</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Town/Municipality/City</label>
                                                <select class="form-select" name="city" id="college_city" required onchange="updateBarangay('college_city','college_barangay')">
                                                    <option value="" disabled selected>-- Select --</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Barangay</label>
                                                <select class="form-select" name="barangay" id="college_barangay" required>
                                                    <option value="" disabled selected>-- Select --</option>
                                                </select>
                                            </div>
                                        </div>
                                        <h5 class="bg-info text-white p-2 rounded">Educational Information</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Primary School</label>
                                                <select class="form-select" name="elementary_school">
                                                    <option value="" selected>-- Select --</option>
                                                    <option value="Dasmariñas Elementary School">Dasmariñas Elementary School</option>
                                                    <option value="Imus Central School">Imus Central School</option>
                                                    <option value="Bacoor Elementary School">Bacoor Elementary School</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Year Graduated</label>
                                                <input type="text" class="form-control" name="elementary_year_grad">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Secondary School</label>
                                                <select class="form-select" name="high_school">
                                                    <option value="" selected>-- Select --</option>
                                                    <option value="Dasmariñas National High School">Dasmariñas National High School</option>
                                                    <option value="Imus National High School">Imus National High School</option>
                                                    <option value="Bacoor National High School">Bacoor National High School</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Year Graduated</label>
                                                <input type="text" class="form-control" name="high_year_grad">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Student Type</label>
                                                <select class="form-select" name="student_type" required onchange="toggleEducationalFields()">
                                                    <option value="" selected disabled>-- Select --</option>
                                                    <option value="New">New</option>
                                                    <option value="Transferee">Transferee</option>
                                                    <option value="Returnee">Returnee</option>
                                                    <option value="Foreign">Foreign</option>
                                                    <option value="ALS Graduate">ALS Graduate</option>
                                                    <option value="DTS Student">DTS Student</option>
                                                    <option value="Cross Enrollee">Cross Enrollee</option>
                                                </select>
                                            </div>                                            <div class="col-md-6">
                                                <label class="form-label">Grade 10 Section</label>
                                                <input type="text" class="form-control" name="grade10_section" placeholder="Enter Grade 10 Section">

                                        </div>
                                        
                                        <!-- Additional fields for transferees -->
                                        <div id="transferee_fields" style="display:none;">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Tertiary School</label>
                                                    <select class="form-select" name="tertiary_school">
                                                        <option value="" selected>-- Select --</option>
                                                        <option value="NCST">NCST</option>
                                                        <option value="De La Salle University">De La Salle University</option>
                                                        <option value="University of the Philippines">University of the Philippines</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Year Graduated</label>
                                                    <input type="text" class="form-control" name="tertiary_year_grad">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Course Graduated</label>
                                                    <input type="text" class="form-control" name="course_graduated">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Grade 10 Section</label>
                                                    <input type="text" class="form-control" name="grade10_section">
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Academic Performance Section -->
                                        <h5 class="bg-info text-white p-2 rounded">Academic Performance</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-6">

                                                <label class="form-label">Educational Plan</label>
                                                <select class="form-select" name="educational_plan">
                                                    <option value="" selected>-- Select --</option>
                                                    <option value="Academic eXentials">Academic eXentials</option>
                                                    <option value="Academix">Academix</option>
                                                    <option value="AXA">AXA</option>
                                                    <option value="BDO Life">BDO Life</option>
                                                    <option value="CAP Pension Plan">CAP Pension Plan</option>
                                                    <option value="Insular Life">Insular Life</option>
                                                    <option value="Manulife Education Plan">Manulife Education Plan</option>
                                                    <option value="Pru Life UK">Pru Life UK</option>
                                                    <option value="SSS Educational Assistance">SSS Educational Assistance</option>
                                                    <option value="Sun Life Education Plan">Sun Life Education Plan</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Academic Achievement</label>
                                                <select class="form-select" name="academic_achievement">
                                                    <option value="" selected>-- Select --</option>
                                                    <option value="Honorable Mention">Honorable Mention</option>
                                                    <option value="Salutatorian">Salutatorian</option>
                                                    <option value="Valedictorian">Valedictorian</option>

                                                <label class="form-label">Academic Achievement</label>
                                                <select class="form-select" name="academic_achievement">
                                                    <option value="" selected>-- Select --</option>
                                                    <option value="With Honors">With Honors</option>
                                                    <option value="With High Honors">With High Honors</option>
                                                    <option value="With Highest Honors">With Highest Honors</option>
                                                    <option value="Dean's List">Dean's List</option>
                                                    <option value="None">None</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Educational Plan</label>
                                                <select class="form-select" name="educational_plan">
                                                    <option value="" selected>-- Select --</option>
                                                    <option value="Full-time Study">Full-time Study</option>
                                                    <option value="Part-time Study">Part-time Study</option>
                                                    <option value="Work and Study">Work and Study</option>
                                                    <option value="Distance Learning">Distance Learning</option>

                                                </select>
                                            </div>
                                        </div>


                                        <!-- Work Information Section -->
                                        <h5 class="bg-info text-white p-2 rounded">Work Information</h5>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-md-2">
                                                <label class="form-label">Working?</label>
                                                <input type="hidden" name="is_working" value="0">
                                                <input type="checkbox" class="form-check-input ms-2" name="is_working" value="1">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Employer</label>
                                                <input type="text" class="form-control" name="employer">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Work in Shifts?</label>
                                                <input type="hidden" name="work_in_shifts" value="0">
                                                <input type="checkbox" class="form-check-input ms-2" name="work_in_shifts" value="1">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Work Position</label>
                                                <input type="text" class="form-control" name="work_position">
                                            </div>
                                        </div>

                                        <!-- Family Connected to NCST Section -->
                                        <h5 class="bg-info text-white p-2 rounded">Family Connected to NCST</h5>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-md-2">
                                                <label class="form-label">NCST Student</label>
                                                <input type="hidden" name="family_connected_ncst" value="0">
                                                <input type="checkbox" class="form-check-input ms-2" name="family_connected_ncst" value="1">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">No of Siblings</label>
                                                <input type="number" class="form-control" name="no_of_siblings">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">NCST Employee</label>
                                                <input type="hidden" name="ncst_employee" value="0">
                                                <input type="checkbox" class="form-check-input ms-2" name="ncst_employee" value="1">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Relationship</label>
                                                <select class="form-select" name="ncst_relationship">
                                                    <option value="" selected>-- Select --</option>
                                                    <option value="Aunt-NCST Employee">Aunt-NCST Employee</option>
                                                    <option value="Brother-NCST Employee">Brother-NCST Employee</option>
                                                    <option value="Father-NCST Employee">Father-NCST Employee</option>
                                                    <option value="Mother-NCST Employee">Mother-NCST Employee</option>
                                                    <option value="No NCST Relations">No NCST Relations</option>
                                                    <option value="Sister-NCST Employee">Sister-NCST Employee</option>
                                                    <option value="Uncle-NCST Employee">Uncle-NCST Employee</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- How did Student come to know about NCST? -->
                                        <h5 class="bg-info text-white p-2 rounded">How did Student come to know about NCST?</h5>
                                        <div class="mb-3">
                                            <select class="form-select" name="how_did_you_know_ncst">
                                                <option value="" selected>-- Select --</option>
                                                <option value="CareerTalk">CareerTalk</option>
                                                <option value="Posters">Posters</option>
                                                <option value="Leaflets">Leaflets</option>
                                                <option value="Friends/Relatives">Friends/Relatives</option>
                                                <option value="Billboards/Streamers">Billboards/Streamers</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>
                                        
                                        <!-- Additional fields for transferees -->
                                        <div id="transferee_fields" style="display:none;">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Tertiary School</label>
                                                    <select class="form-select" name="tertiary_school">
                                                        <option value="" selected>-- Select --</option>
                                                        <option value="NCST">NCST</option>
                                                        <option value="De La Salle University">De La Salle University</option>
                                                        <option value="University of the Philippines">University of the Philippines</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Year Graduated</label>
                                                    <input type="text" class="form-control" name="tertiary_year_grad">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Course Graduated</label>
                                                    <input type="text" class="form-control" name="course_graduated">
                                                </div>

                                        <!-- Work Experience Section -->
                                        <h5 class="bg-info text-white p-2 rounded">Work Experience</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label class="form-label">Are you currently working?</label>
                                                <select class="form-select" name="is_working" onchange="toggleWorkFields()">
                                                    <option value="" selected>-- Select --</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="work_fields" style="display:none;">
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Employer</label>
                                                    <input type="text" class="form-control" name="employer">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Work Position</label>
                                                    <input type="text" class="form-control" name="work_position">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Work in Shifts?</label>
                                                    <select class="form-select" name="work_in_shifts">
                                                        <option value="" selected>-- Select --</option>
                                                        <option value="1">Yes</option>
                                                        <option value="0">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Family Background Section -->
                                        <h5 class="bg-info text-white p-2 rounded">Family Background</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label class="form-label">Number of Siblings</label>
                                                <input type="number" class="form-control" name="no_of_siblings" min="0">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Family Connected to NCST?</label>
                                                <select class="form-select" name="family_connected_ncst" onchange="toggleNCSTRelationship()">
                                                    <option value="" selected>-- Select --</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3" id="ncst_relationship_field" style="display:none;">
                                                <label class="form-label">NCST Relationship</label>
                                                <select class="form-select" name="ncst_relationship">
                                                    <option value="" selected>-- Select --</option>
                                                    <option value="Alumni">Alumni</option>
                                                    <option value="Current Student">Current Student</option>
                                                    <option value="Faculty">Faculty</option>
                                                    <option value="Staff">Staff</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">How Did You Know NCST?</label>
                                                <select class="form-select" name="how_did_you_know_ncst">
                                                    <option value="" selected>-- Select --</option>
                                                    <option value="Social Media">Social Media</option>
                                                    <option value="Website">Website</option>
                                                    <option value="Friends/Family">Friends/Family</option>
                                                    <option value="School Visit">School Visit</option>
                                                    <option value="Advertisement">Advertisement</option>
                                                    <option value="Other">Other</option>
                                                </select>

                                            </div>
                                        </div>

                                        <!-- Parent/Guardian Information Section -->
                                        <h5 class="bg-info text-white p-2 rounded">Parent/Guardian Information</h5>
                                        <div class="row mb-3">
                                            <div class="col-md-3"><label class="form-label">Father Family Name</label><input type="text" class="form-control" name="father_family_name"></div>
                                            <div class="col-md-3"><label class="form-label">Father Given Name</label><input type="text" class="form-control" name="father_given_name"></div>
                                            <div class="col-md-3"><label class="form-label">Father Middle Name</label><input type="text" class="form-control" name="father_middle_name"></div>
                                            <div class="col-md-3 d-flex align-items-end">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="father_deceased" id="father_deceased">
                                                    <label class="form-check-label" for="father_deceased">Deceased?</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3"><label class="form-label">Father's Complete Address</label><input type="text" class="form-control" name="father_address"></div>
                                        <div class="row mb-3">
                                            <div class="col-md-4"><label class="form-label">Father's Mobile No</label><input type="text" class="form-control" name="father_mobile"></div>
                                            <div class="col-md-4"><label class="form-label">Father's Land Line</label><input type="text" class="form-control" name="father_landline"></div>
                                            <div class="col-md-4"><label class="form-label">Father's Occupation</label><input type="text" class="form-control" name="father_occupation"></div>
                                        </div>
                                        <hr>
                                        <div class="row mb-3">
                                            <div class="col-md-3"><label class="form-label">Mother Family Name</label><input type="text" class="form-control" name="mother_family_name"></div>
                                            <div class="col-md-3"><label class="form-label">Mother Given Name</label><input type="text" class="form-control" name="mother_given_name"></div>
                                            <div class="col-md-3"><label class="form-label">Mother Middle Name</label><input type="text" class="form-control" name="mother_middle_name"></div>
                                            <div class="col-md-3 d-flex align-items-end">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="mother_deceased" id="mother_deceased">
                                                    <label class="form-check-label" for="mother_deceased">Deceased?</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4"><label class="form-label">Mother Maiden Family Name</label><input type="text" class="form-control" name="mother_maiden_family_name"></div>
                                            <div class="col-md-4"><label class="form-label">Mother Maiden Given Name</label><input type="text" class="form-control" name="mother_maiden_given_name"></div>
                                            <div class="col-md-4"><label class="form-label">Mother Maiden Middle Name</label><input type="text" class="form-control" name="mother_maiden_middle_name"></div>
                                        </div>
                                        <div class="mb-3"><label class="form-label">Mother's Complete Address</label><input type="text" class="form-control" name="mother_address"></div>
                                        <div class="row mb-3">
                                            <div class="col-md-4"><label class="form-label">Mother's Mobile No</label><input type="text" class="form-control" name="mother_mobile"></div>
                                            <div class="col-md-4"><label class="form-label">Mother's Land Line</label><input type="text" class="form-control" name="mother_landline"></div>
                                            <div class="col-md-4"><label class="form-label">Mother's Occupation</label><input type="text" class="form-control" name="mother_occupation"></div>
                                        </div>
                                        <hr>
                                        <h6 class="text-info">Guardian Information (Required if no parent information provided)</h6>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label class="form-label">Guardian Family Name</label>
                                                <input type="text" class="form-control" name="guardian_family_name">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Guardian Given Name</label>
                                                <input type="text" class="form-control" name="guardian_given_name">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Guardian Middle Name</label>
                                                <input type="text" class="form-control" name="guardian_middle_name">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Relationship</label>
                                                <select class="form-select" name="guardian_relationship">
                                                    <option value="" disabled selected>-- Select --</option>
                                                    <option value="Father">Father</option>
                                                    <option value="Mother">Mother</option>
                                                    <option value="Sibling">Sibling</option>
                                                    <option value="Relative">Relative</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Guardian's Complete Address</label>
                                            <input type="text" class="form-control" name="guardian_address">
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Guardian's Mobile No</label>
                                                <input type="text" class="form-control" name="guardian_mobile">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Guardian's Landline</label>
                                                <input type="text" class="form-control" name="guardian_landline">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Guardian's Occupation</label>
                                                <input type="text" class="form-control" name="guardian_occupation">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3 text-end">
                                            <button type="submit" class="btn btn-warning fw-bold" style="color: #003399;">Submit Application</button>
                                            <button type="reset" class="btn btn-danger text-white ms-2">Clear Form</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Reusable NCST Modal (unchanged) -->
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
    <!-- Place this modal at the end of your HTML, before </body> -->
    <div class="modal fade" id="viewApplicantModal" tabindex="-1" aria-labelledby="viewApplicantModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header ncst-theme">
            <h5 class="modal-title" id="viewApplicantModalLabel">Applicant Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="viewApplicantModalBody">
            <!-- Details will be loaded here -->
          </div>
        </div>
      </div>
    </div>
    <!-- Checklist Modal -->
    <div class="modal fade" id="checklistModal" tabindex="-1" aria-labelledby="checklistModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header ncst-theme">
            <h5 class="modal-title" id="checklistModalLabel">Requirements Checklist</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="checklistModalBody">
            <!-- Checklist will be loaded here -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-ncst" id="saveChecklistBtn">Save Checklist</button>
          </div>
        </div>
      </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>
<script>
// AJAX loader for application tables
function loadApplications(status, tableBodyId, countId) {
    $.ajax({
        url: 'fetch_applications.php',
        type: 'GET',
        data: { status: status },
        success: function(data) {
            $('#' + tableBodyId).html(data);
            // Optionally, update count if fetch_applications.php returns a count (not implemented by default)
            // $('#' + countId).text(count + ' application(s) ...');
        },
        error: function(xhr, status, error) {
            $('#' + tableBodyId).html('<tr><td colspan="6" class="text-center text-danger">Failed to load applications.</td></tr>');
        }
    });
}

$(document).ready(function() {
    // Initial load
    loadApplications('new', 'pendingTableBody', 'pendingCount');
    loadApplications('approved', 'approvedTableBody', 'approvedCount');
    loadApplications('rejected', 'rejectedTableBody', 'rejectedCount');

    // Tab navigation
    $('.nav-link').on('click', function() {
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
        var target = $(this).attr('href');
        if (target === '#pending-section') loadApplications('new', 'pendingTableBody', 'pendingCount');
        if (target === '#approved-section') loadApplications('approved', 'approvedTableBody', 'approvedCount');
        if (target === '#rejected-section') loadApplications('rejected', 'rejectedTableBody', 'rejectedCount');
    });

    // Search bar filter logic (client-side)
    $('#searchPendingInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#pendingTableBody tr').filter(function() {
            var tracking = $(this).find('td').eq(0).text().toLowerCase();
            var name = $(this).find('td').eq(1).text().toLowerCase();
            $(this).toggle(
                tracking.indexOf(value) > -1 ||
                name.split(',')[0].indexOf(value) > -1
            );
        });
    });
    $('#searchApprovedInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#approvedTableBody tr').filter(function() {
            var tracking = $(this).find('td').eq(0).text().toLowerCase();
            var name = $(this).find('td').eq(1).text().toLowerCase();
            $(this).toggle(
                tracking.indexOf(value) > -1 ||
                name.split(',')[0].indexOf(value) > -1
            );
        });
    });
    $('#searchRejectedInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#rejectedTableBody tr').filter(function() {
            var tracking = $(this).find('td').eq(0).text().toLowerCase();
            var name = $(this).find('td').eq(1).text().toLowerCase();
            $(this).toggle(
                tracking.indexOf(value) > -1 ||
                name.split(',')[0].indexOf(value) > -1
            );
        });
    });
});

// Reusable NCST Modal Functions (unchanged)
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
  const confirmBtn = document.getElementById('ncstModalConfirm');
  const newConfirmBtn = confirmBtn.cloneNode(true);
  confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
  if (onConfirm) {
    newConfirmBtn.addEventListener('click', onConfirm);
  } else {
    // If no onConfirm and cancel is hidden, close modal on OK
    if (!showCancel) {
      newConfirmBtn.addEventListener('click', function() {
        var modal = bootstrap.Modal.getInstance(document.getElementById('ncstModal'));
        modal.hide();
      });
    }
  }
  const modal = new bootstrap.Modal(document.getElementById('ncstModal'));
  modal.show();
}

let currentAppId = null;

function approveApplicant(appId) {
    const badge = $('#req-status-' + appId);
    if (badge.length && badge.text().trim() !== 'Complete') {
        showNcstModal(
            'Cannot Approve',
            'Requirements are still incomplete. You cannot approve this applicant until all requirements are complete.',
            'OK',
            false
        );
        return;
    }
    currentAppId = appId;
    showNcstModal(
        'Confirm Approval',
        'Are you sure you want to approve this applicant?',
        'Approve',
        true,
        function() {
            $.ajax({
                url: 'process_decision.php',
                type: 'POST',
                data: {
                    id: currentAppId,
                    action: 'approve',
                    checklist: '{}'
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        alert('Applicant approved successfully! They will now appear in the approved applications section and can proceed to registration.');
                        loadApplications('new', 'pendingTableBody', 'pendingCount');
                        loadApplications('approved', 'approvedTableBody', 'approvedCount');
                    } else {
                        alert(data.message || 'Error approving applicant.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('AJAX error: ' + error + '\nPlease check the console for details.');
                }
            });
            var modal = bootstrap.Modal.getInstance(document.getElementById('ncstModal'));
            modal.hide();
        }
    );
}

function rejectApplicant(appId) {
    currentAppId = appId;
    showNcstModal(
        'Confirm Rejection',
        'Are you sure you want to reject this applicant?',
        'Reject',
        true,
        function() {
            $.ajax({
                url: 'process_decision.php',
                type: 'POST',
                data: {
                    id: currentAppId,
                    action: 'reject',
                    checklist: '{}'
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        alert('Applicant rejected successfully!');
                        loadApplications('new', 'pendingTableBody', 'pendingCount');
                        loadApplications('rejected', 'rejectedTableBody', 'rejectedCount');
                    } else {
                        alert(data.message || 'Error rejecting applicant.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('AJAX error: ' + error + '\nPlease check the console for details.');
                }
            });
            var modal = bootstrap.Modal.getInstance(document.getElementById('ncstModal'));
            modal.hide();
        }
    );
}

function enrollStudent(studentId, studentType) {
    window.location.href = '../registration_officer/enrollment_page.php';
}
function enrollAllApproved() {
    window.location.href = '../registration_officer/enrollment_page.php';
}

function showApplicantDetails(applicantId) {
    $('#viewApplicantModalBody').html('<div class="text-center text-muted">Loading...</div>');
    $.get('get_applicant_details.php', { id: applicantId }, function(data) {
        $('#viewApplicantModalBody').html(data);
        var modal = new bootstrap.Modal(document.getElementById('viewApplicantModal'));
        modal.show();
    });
}

let currentChecklistApplicantId = null;

function showChecklistModal(applicantId) {
    currentChecklistApplicantId = applicantId;
    $('#checklistModalBody').html('<div class="text-center text-muted">Loading...</div>');
    $.get('get_checklist.php', { id: applicantId }, function(data) {
        $('#checklistModalBody').html(data);
        var modal = new bootstrap.Modal(document.getElementById('checklistModal'));
        modal.show();
    });
}

$(document).on('click', '#saveChecklistBtn', function() {
    const checklist = {};
    $('#checklistModalBody input[type=checkbox]').each(function() {
        checklist[$(this).data('req')] = $(this).is(':checked') ? 1 : 0;
    });
    // Requirements list (must match PHP)
    const normalRequirements = [
        "Properly accomplished admission form",
        "Four (4) 2x2 recent, identical color pictures in white background with name tag",
        "Five (5) 1x1 recent, identical color pictures in white background with name tag",
        "Submit original and photocopied Form 138 / Report Card",
        "Submit original Good Moral Character certificate with dry seal and Photocopied",
        "1pc. Long Brown Envelope"
    ];
    const marriageReq = "If married, two (2) photocopies of marriage certificate duly signed by a priest / minister";
    const isMarried = checklist['married'] === 1;
    // Check if all normal requirements are checked
    let allNormalChecked = normalRequirements.every(req => checklist[req] === 1);
    // If married, marriage certificate must also be checked
    let isComplete = allNormalChecked && (!isMarried || checklist[marriageReq] === 1);
    $.post('save_checklist.php', {
        id: currentChecklistApplicantId,
        checklist: JSON.stringify(checklist)
    }, function(resp) {
        if (resp.success) {
            // Update badge in table
            const badge = $('#req-status-' + currentChecklistApplicantId);
            if (badge.length) {
                if (isComplete) {
                    badge.removeClass('bg-danger').addClass('bg-success').text('Complete');
                } else {
                    badge.removeClass('bg-success').addClass('bg-danger').text('Incomplete');
                }
            }
            // Optionally close modal
            var modal = bootstrap.Modal.getInstance(document.getElementById('checklistModal'));
            modal.hide();
        } else {
            alert(resp.message || 'Failed to save checklist.');
        }
    }, 'json');
});

// College Registration Form JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize region dropdown for college registration
    populateRegionSelect('college_region');
    
    // College form submission
    const collegeForm = document.getElementById('collegeForm');
    const collegeFormError = document.getElementById('collegeFormError');
    const collegeFormSuccess = document.getElementById('collegeFormSuccess');
    
    if (collegeForm) {
        collegeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Hide previous messages
            collegeFormError.style.display = 'none';
            collegeFormSuccess.style.display = 'none';
            
            // Validate required fields
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
                return;
            }
            
            // Submit form via AJAX
            const formData = new FormData(collegeForm);
            
            fetch('college_application_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    collegeFormSuccess.style.display = 'block';
                    collegeFormSuccess.innerHTML = `
                        <strong>Application Submitted Successfully!</strong><br>
                        Tracking Number: <strong>${data.tracking_number}</strong><br>
                        <small>The application has been added to the system and can be viewed by all authorized personnel.</small>
                    `;
                    collegeForm.reset();
                    // Reset dropdowns
                    populateRegionSelect('college_region');
                    updateProvince('college_region','college_province','college_city','college_barangay');
                    
                    // Refresh the pending applications table
                    loadApplications('new', 'pendingTableBody', 'pendingCount');
                } else {
                    collegeFormError.style.display = 'block';
                    collegeFormError.textContent = data.message || 'There was an error submitting the application. Please try again.';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                collegeFormError.style.display = 'block';
                collegeFormError.textContent = 'There was an error submitting the application. Please try again.';
            });
        });
    }
});

// Location dropdown functions for college registration
function updateProvince(regionSelectId, provinceSelectId, citySelectId, barangaySelectId) {
    const regionSelect = document.getElementById(regionSelectId);
    const provinceSelect = document.getElementById(provinceSelectId);
    const citySelect = document.getElementById(citySelectId);
    const barangaySelect = document.getElementById(barangaySelectId);
    
    // Clear dependent dropdowns
    provinceSelect.innerHTML = '<option value="" disabled selected>-- Select --</option>';
    citySelect.innerHTML = '<option value="" disabled selected>-- Select --</option>';
    barangaySelect.innerHTML = '<option value="" disabled selected>-- Select --</option>';
    
    const selectedRegion = regionSelect.value;
    if (!selectedRegion) return;
    
    // Sample provinces for demonstration - replace with actual data
    const provinces = {
        'NCR': ['Metro Manila'],
        'Region IV-A': ['Cavite', 'Laguna', 'Batangas', 'Rizal', 'Quezon']
    };
    
    if (provinces[selectedRegion]) {
        provinces[selectedRegion].forEach(province => {
            const option = document.createElement('option');
            option.value = province;
            option.textContent = province;
            provinceSelect.appendChild(option);
        });
    }
}

function updateCity(provinceSelectId, citySelectId, barangaySelectId) {
    const provinceSelect = document.getElementById(provinceSelectId);
    const citySelect = document.getElementById(citySelectId);
    const barangaySelect = document.getElementById(barangaySelectId);
    
    // Clear dependent dropdowns
    citySelect.innerHTML = '<option value="" disabled selected>-- Select --</option>';
    barangaySelect.innerHTML = '<option value="" disabled selected>-- Select --</option>';
    
    const selectedProvince = provinceSelect.value;
    if (!selectedProvince) return;
    
    // Sample cities for demonstration - replace with actual data
    const cities = {
        'Cavite': ['Dasmariñas', 'Imus', 'Bacoor', 'General Trias'],
        'Metro Manila': ['Manila', 'Quezon City', 'Makati', 'Pasig']
    };
    
    if (cities[selectedProvince]) {
        cities[selectedProvince].forEach(city => {
            const option = document.createElement('option');
            option.value = city;
            option.textContent = city;
            citySelect.appendChild(option);
        });
    }
}

function updateBarangay(citySelectId, barangaySelectId) {
    const citySelect = document.getElementById(citySelectId);
    const barangaySelect = document.getElementById(barangaySelectId);
    
    // Clear barangay dropdown
    barangaySelect.innerHTML = '<option value="" disabled selected>-- Select --</option>';
    
    const selectedCity = citySelect.value;
    if (!selectedCity) return;
    
    // Sample barangays for demonstration - replace with actual data
    const barangays = {
        'Dasmariñas': ['Zone I', 'Zone II', 'Zone III', 'Zone IV'],
        'Imus': ['Barangay I', 'Barangay II', 'Barangay III'],
        'Bacoor': ['Molino I', 'Molino II', 'Molino III']
    };
    
    if (barangays[selectedCity]) {
        barangays[selectedCity].forEach(barangay => {
            const option = document.createElement('option');
            option.value = barangay;
            option.textContent = barangay;
            barangaySelect.appendChild(option);
        });
    }
}

function populateRegionSelect(regionSelectId) {
    const regionSelect = document.getElementById(regionSelectId);
    if (!regionSelect) return;
    
    // Clear existing options except the first one
    regionSelect.innerHTML = '<option value="" disabled selected>-- Select --</option>';
    
    // Sample regions - replace with actual data
    const regions = ['NCR', 'Region IV-A', 'Region IV-B'];
    
    regions.forEach(region => {
        const option = document.createElement('option');
        option.value = region;
        option.textContent = region;
        regionSelect.appendChild(option);
    });
}

function toggleEducationalFields() {
    const studentType = document.querySelector('select[name="student_type"]').value;
    const transfereeFields = document.getElementById('transferee_fields');
    const elementarySchool = document.querySelector('select[name="elementary_school"]');
    const elementaryYearGrad = document.querySelector('input[name="elementary_year_grad"]');
    const highSchool = document.querySelector('select[name="high_school"]');
    const highYearGrad = document.querySelector('input[name="high_year_grad"]');
    const tertiarySchool = document.querySelector('select[name="tertiary_school"]');
    
    // Reset all fields first
    if (elementarySchool) elementarySchool.required = false;
    if (elementaryYearGrad) elementaryYearGrad.required = false;
    if (highSchool) highSchool.required = false;
    if (highYearGrad) highYearGrad.required = false;
    if (tertiarySchool) tertiarySchool.required = false;
    
    // Hide transferee fields by default
    if (transfereeFields) transfereeFields.style.display = 'none';
    
    if (studentType === 'New') {
        // For new students: elementary and high school education required
        if (elementarySchool) elementarySchool.required = true;
        if (elementaryYearGrad) elementaryYearGrad.required = true;
        if (highSchool) highSchool.required = true;
        if (highYearGrad) highYearGrad.required = true;
    } else if (studentType === 'Transferee') {
        // For transferees: elementary, high school, and tertiary school required
        if (elementarySchool) elementarySchool.required = true;
        if (elementaryYearGrad) elementaryYearGrad.required = true;
        if (highSchool) highSchool.required = true;
        if (highYearGrad) highYearGrad.required = true;
        if (tertiarySchool) tertiarySchool.required = true;
        if (transfereeFields) transfereeFields.style.display = 'block';
    }
}


function toggleWorkFields() {
    const isWorking = document.querySelector('select[name="is_working"]').value;
    const workFields = document.getElementById('work_fields');
    const employerField = document.querySelector('input[name="employer"]');
    const workPositionField = document.querySelector('input[name="work_position"]');
    const workInShiftsField = document.querySelector('select[name="work_in_shifts"]');

    if (workFields) workFields.style.display = 'none';
    if (isWorking === '1') {
        if (employerField) employerField.required = true;
        if (workPositionField) workPositionField.required = true;
        if (workInShiftsField) workInShiftsField.required = true;
        if (workFields) workFields.style.display = 'block';
    }
}

function toggleNCSTRelationship() {
    const familyConnected = document.querySelector('select[name="family_connected_ncst"]').value;
    const ncstRelationshipField = document.getElementById('ncst_relationship_field');
    const ncstRelationshipSelect = document.querySelector('select[name="ncst_relationship"]');

    if (ncstRelationshipField) ncstRelationshipField.style.display = 'none';
    if (familyConnected === '1') {
        if (ncstRelationshipSelect) ncstRelationshipSelect.required = true;
        if (ncstRelationshipField) ncstRelationshipField.style.display = 'block';
    }
}


</script>
</body>
</html> 