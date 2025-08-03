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
            <a class="nav-link active" href="#applications-section" data-bs-toggle="tab">All Applications</a>
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
          <a class="nav-link active" href="#applications-section" data-bs-toggle="tab">All Applications</a>
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
            <!-- All Applications Tab -->
            <div class="tab-pane fade show active" id="applications-section">
                <div class="card-ncst p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h3 class="fw-bold mb-0" style="color:#003399;">Submitted Applications</h3>
                            <small class="text-muted">All applications are automatically approved upon submission</small>
                        </div>
                        <div class="input-group" style="max-width: 320px;">
                            <input type="text" id="searchApplicationsInput" class="form-control" placeholder="Search by Tracking # or Name...">
                            <span class="input-group-text bg-primary text-white"><i class="bi bi-search"></i></span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="applicationsTable">
                            <thead class="table-success">
                                <tr>
                                    <th>Tracking #</th>
                                    <th>Student ID</th>
                                    <th>Full Name</th>
                                    <th>Course</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Date Submitted</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="applicationsTableBody">
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
                            <small class="text-muted">Register new college students - Student ID and account will be auto-generated</small>
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
                                            </div>
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
    
    <!-- View Application Modal -->
    <div class="modal fade" id="viewApplicationModal" tabindex="-1" aria-labelledby="viewApplicationModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header ncst-theme">
            <h5 class="modal-title" id="viewApplicationModalLabel">Application Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="viewApplicationModalBody">
            <!-- Details will be loaded here -->
          </div>
        </div>
      </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>
<script>
// AJAX loader for applications table
function loadApplications() {
    $.ajax({
        url: 'fetch_applications.php',
        type: 'GET',
        data: { status: 'all' },
        success: function(data) {
            $('#applicationsTableBody').html(data);
        },
        error: function(xhr, status, error) {
            $('#applicationsTableBody').html('<tr><td colspan="8" class="text-center text-danger">Failed to load applications.</td></tr>');
        }
    });
}

$(document).ready(function() {
    // Initial load
    loadApplications();

    // Tab navigation
    $('.nav-link').on('click', function() {
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
        var target = $(this).attr('href');
        if (target === '#applications-section') {
            loadApplications();
        }
    });

    // Search functionality
    $('#searchApplicationsInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#applicationsTableBody tr').filter(function() {
            var tracking = $(this).find('td').eq(0).text().toLowerCase();
            var name = $(this).find('td').eq(2).text().toLowerCase();
            $(this).toggle(
                tracking.indexOf(value) > -1 || name.indexOf(value) > -1
            );
        });
    });
});

function showApplicationDetails(applicationId) {
    $('#viewApplicationModalBody').html('<div class="text-center text-muted">Loading...</div>');
    $.get('get_applicant_details.php', { id: applicationId }, function(data) {
        $('#viewApplicationModalBody').html(data);
        var modal = new bootstrap.Modal(document.getElementById('viewApplicationModal'));
        modal.show();
    });
}

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
                        Student ID: <strong>${data.student_id}</strong><br>
                        Username: <strong>${data.username}</strong><br>
                        Password: <strong>${data.password}</strong><br>
                        <small>The student can now login to the student portal using these credentials.</small>
                    `;
                    collegeForm.reset();
                    // Reset dropdowns
                    populateRegionSelect('college_region');
                    updateProvince('college_region','college_province','college_city','college_barangay');
                    
                    // Refresh the applications table
                    loadApplications();
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