<?php include 'backend/config.php'?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCST Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .btn-warning:hover, .btn-warning:focus {
            background-color: #e0a800 !important;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.10);
            border-color: #d39e00 !important;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar py-4 px-0" style="background: linear-gradient(180deg, #003399 0%, #0055cc 100%); min-height: 100vh; color: #fff; box-shadow: 2px 0 8px rgba(0,0,0,0.07);">
                <div class="text-center mb-4">
                    <img src="images/ncst-logo.png" alt="NCST Logo" style="max-width: 60px;">
                    <h5 class="mt-2 mb-0 fw-bold">NCST Registration</h5>
                </div>
                <ul class="nav flex-column">
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
            </nav>
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <!-- Requirements Section -->
                <div id="section-requirements">
                    <div class="card shadow-lg mb-4" style="border-radius: 1rem;">
                        <div class="card-body">
                            <h4 class="card-title mb-3 fw-bold">Enrollment Requirements</h4>
                            <strong>SENIOR HIGH SCHOOL</strong><br>
                            <small>(NO ENTRANCE EXAMINATION)</small>
                            <ul class="mt-2 mb-0">
                                <li>F138/Report Card (Original & Photocopied)</li>
                                <li>Good Moral Character (Original with Dry Seal & Photocopied)</li>
                                <li>Moving up Certificate (Photocopy of Diploma)</li>
                                <li>2pcs. Photocopied Birth Certificate (PSA)</li>
                                <li>2pcs. Photocopied Marriage Contract (PSA), if married</li>
                                <li>4pcs. 2x2 Picture (White background with name tag)</li>
                                <li>1pc. Long Brown Envelope</li>
                            </ul>
                            <strong class="mt-3 d-block">COLLEGE FRESHMEN</strong>
                            <ul class="mt-2 mb-0">
                                <li>Properly accomplished admission form</li>
                                <li>Four (4) 2x2 recent, identical color pictures in white background with name tag</li>
                                <li>Five (5) 1x1 recent, identical color pictures in white background with name tag</li>
                                <li>Submit original and photocopied Form 138 / Report Card</li>
                                <li>Submit original Good Moral Character certificate with dry seal and Photocopied</li>
                                <li>If married, two (2) photocopies of marriage certificate duly signed by a priest / minister</li>
                                <li>1pc. Long Brown Envelope</li>
                            </ul>
                            <strong class="mt-3 d-block">TRANSFEREES</strong>
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
                            <div class="mt-3">
                                <small>The NCST Admissions Office is open from Monday to Saturday 8am to 5pm.<br>
                                For more details regarding admissions, please call us at (046) 416-6278</small>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <a href="index.php" class="btn btn-warning"><i class="bi bi-arrow-left-circle me-2"></i>Go Back to NCST Main Page</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Senior High Form Section -->
                <div id="section-shs" style="display:none;">
                    <form class="card shadow-lg p-4 mt-0" style="border-radius: 1rem;">
                        <h3 class="mb-4">Senior High School Registration</h3>
                        <!-- Desired Track/Course -->
                        <h5 class="bg-info text-white p-2 rounded">Desired Track/Course</h5>
                        <div class="mb-3">
                            <label for="desiredTrack" class="form-label">Desired Track/Course</label>
                            <select class="form-select" id="desiredTrack" required>
                                <option value="" selected disabled>-- Select --</option>
                                <option>STEM</option>
                                <option>ABM</option>
                                <option>HUMSS</option>
                                <option>GAS</option>
                                <!-- Add more tracks as needed -->
                            </select>
                        </div>
                        <!-- Personal Information -->
                        <h5 class="bg-info text-white p-2 rounded">Personal Information</h5>
                        <div class="row mb-3">
                            <div class="col-md-3"><label class="form-label">Family Name</label><input type="text" class="form-control" require></div>
                            <div class="col-md-3"><label class="form-label">Given Name</label><input type="text" class="form-control" require></div>
                            <div class="col-md-3"><label class="form-label">Middle Name</label><input type="text" class="form-control" require></div>
                            <div class="col-md-3"><label class="form-label">Suffix</label><input type="text" class="form-control" require></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><label class="form-label">Date Of Birth</label><input type="date" class="form-control" require></div>
                            <div class="col-md-3"><label class="form-label">Place of Birth</label><input type="text" class="form-control" require></div>
                            <div class="col-md-3"><label class="form-label">Gender</label><select class="form-select" require><option>-- Select --</option></select></div>
                            <div class="col-md-3"><label class="form-label">Civil Status</label><select class="form-select" require><option>-- Select --</option></select></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><label class="form-label">Nationality</label><select class="form-select" require><option>-- Select --</option></select></div>
                            <div class="col-md-3"><label class="form-label">Religion</label><select class="form-select" require><option>-- Select --</option></select></div>
                            <div class="col-md-3"><label class="form-label">Dialect/Language</label><input type="text" class="form-control" require></div>
                        </div>
                        <!-- Address/Contact Information -->
                        <h5 class="bg-info text-white p-2 rounded">Address/Contact Information</h5>
                        <div class="row mb-3">
                            <div class="col-md-10"><label class="form-label">Complete Address</label><input type="text" class="form-control" require></div>
                            <div class="col-md-2"><label class="form-label">Zip Code</label><input type="text" class="form-control" require></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><label class="form-label">Region</label><select class="form-select" require><option>-- Select --</option></select></div>
                            <div class="col-md-3"><label class="form-label">Province</label><select class="form-select" require><option>-- Select --</option></select></div>
                            <div class="col-md-3"><label class="form-label">Town/Municipality/City</label><select class="form-select" require><option>-- Select --</option></select></div>
                            <div class="col-md-3"><label class="form-label">Barangay</label><select class="form-select" require><option>-- Select --</option></select></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4"><label class="form-label">Email Address</label><input type="email" class="form-control" require></div>
                            <div class="col-md-4"><label class="form-label">Mobile No</label><input type="text" class="form-control" require></div>
                            <div class="col-md-4"><label class="form-label">Landline</label><input type="text" class="form-control" require></div>
                        </div>
                        <!-- Educational Background -->
                        <h5 class="bg-info text-white p-2 rounded">Educational Background</h5>
                        <div class="row mb-3">
                            <div class="col-md-8"><label class="form-label">Elementary School</label><select class="form-select" require><option>-- Select --</option></select></div>
                            <div class="col-md-4"><label class="form-label">Year Graduated</label><input type="text" class="form-control" require></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-8"><label class="form-label">High School</label><select class="form-select" require><option>-- Select --</option></select></div>
                            <div class="col-md-4"><label class="form-label">Year Graduated</label><input type="text" class="form-control" require></div>
                        </div>
                        <!-- Family/Guardian Information -->
                        <h5 class="bg-info text-white p-2 rounded">Father Information</h5>
                        <div class="row mb-3">
                            <div class="col-md-3"><label class="form-label">Father Family Name</label><input type="text" class="form-control" require></div>
                            <div class="col-md-3"><label class="form-label">Father Given Name</label><input type="text" class="form-control" require></div>
                            <div class="col-md-3"><label class="form-label">Father Middle Name</label><input type="text" class="form-control" require></div>
                            <div class="col-md-1 d-flex align-items-end"><label class="form-label me-2">Deceased?</label><input type="checkbox" class="form-check-input" require></div>
                        </div>
                        <div class="mb-3"><label class="form-label">Complete Address</label><input type="text" class="form-control" require></div>
                        <div class="row mb-3">
                            <div class="col-md-3"><label class="form-label">Mobile No</label><input type="text" class="form-control" require></div>
                            <div class="col-md-3"><label class="form-label">Landline</label><input type="text" class="form-control" require></div>
                            <div class="col-md-3"><label class="form-label">Occupation</label><input type="text" class="form-control" require></div>
                        </div>
                        <hr>
                        <h5 class="bg-info text-white p-2 rounded">Mother Information</h5>
                        <div class="row mb-3">
                            <div class="col-md-3"><label class="form-label">Mother Family Name</label><input type="text" class="form-control" require></div>
                            <div class="col-md-3"><label class="form-label">Mother Given Name</label><input type="text" class="form-control" require></div>
                            <div class="col-md-3"><label class="form-label">Mother Middle Name</label><input type="text" class="form-control" require></div>
                            <div class="col-md-1 d-flex align-items-end"><label class="form-label me-2">Deceased?</label><input type="checkbox" class="form-check-input" require></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4"><label class="form-label">Mother Maiden Family Name</label><input type="text" class="form-control" require></div>
                            <div class="col-md-4"><label class="form-label">Mother Maiden Given Name</label><input type="text" class="form-control" require></div>
                            <div class="col-md-4"><label class="form-label">Mother Maiden Middle Name</label><input type="text" class="form-control" require></div>
                        </div>
                        <div class="mb-3"><label class="form-label">Complete Address</label><input type="text" class="form-control" require></div>
                        <div class="row mb-3">
                            <div class="col-md-3"><label class="form-label">Mobile No</label><input type="text" class="form-control" require></div>
                            <div class="col-md-3"><label class="form-label">Landline</label><input type="text" class="form-control" require></div>
                            <div class="col-md-3"><label class="form-label">Occupation</label><input type="text" class="form-control" require></div>
                        </div>
                        <hr>
                        <h5 class="bg-info text-white p-2 rounded">Guardian Information</h5>
                        <div class="row mb-3">
                            <div class="col-md-3"><label class="form-label">Guardian Family Name</label><input type="text" class="form-control" require></div>
                            <div class="col-md-3"><label class="form-label">Guardian Given Name</label><input type="text" class="form-control" require></div>
                            <div class="col-md-3"><label class="form-label">Guardian Middle Name</label><input type="text" class="form-control" require></div>
                            <div class="col-md-3"><label class="form-label">Relationship</label><input type="text" class="form-control" require></div>
                        </div>
                        <div class="mb-3"><label class="form-label">Complete Address</label><input type="text" class="form-control" require></div>
                        <div class="row mb-3">
                            <div class="col-md-3"><label class="form-label">Mobile No</label><input type="text" class="form-control" require></div>
                            <div class="col-md-3"><label class="form-label">Landline</label><input type="text" class="form-control" require></div>
                            <div class="col-md-3"><label class="form-label">Occupation</label><input type="text" class="form-control" require></div>
                        </div>
                        <hr>
                        <div class="mb-3 text-end">
                            <button type="submit" class="btn btn-success">Submit</button>
                            <button type="reset" class="btn btn-danger text-white ms-2">Cancel</button>
                        </div>
                    </form>
                </div>

                <!----------------------------->

                <!-- College Form Section -->
                <div id="section-college" style="display:none;">
                    <?php include 'admission_backend.php';?>
                    <form class="card shadow-lg p-4 mt-0" style="border-radius: 1rem;" method="POST">
                        <h3 class="mb-4">College Registration</h3>

                        <!-- Desired Course -->
                        <h5 class="bg-info text-white p-2 rounded">Desired Course</h5>
                        <div class="mb-3">
                            <label for="desiredCourse" class="form-label">Desired Course</label>
                                <select class="form-select" id="desiredCourse" name="desired_course" required>
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
                        <!-- Personal Information -->
                        <h5 class="bg-info text-white p-2 rounded">Personal Information</h5>
                        <div class="row mb-3">
                            <div class="col-md-3"><label class="form-label">Last Name</label><input type="text" class="form-control" name="last_name" required></div>
                            <div class="col-md-3"><label class="form-label">First Name</label><input type="text" class="form-control" name="first_name" required></div>
                            <div class="col-md-3"><label class="form-label">Middle Name</label><input type="text" class="form-control" name="middle_name" required></div>
                            <div class="col-md-3"><label class="form-label">Suffix</label><input type="text" class="form-control" name="suffix"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-10"><label class="form-label">Complete Address</label><input type="text" class="form-control" name="complete_address" required></div>
                            <div class="col-md-2"><label class="form-label">Zip Code</label><input type="text" class="form-control" name="zip_code" required></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><label class="form-label">Region</label><select class="form-select" name="region" required><option value="-- Select --">-- Select --</option></select></div>
                            <div class="col-md-3"><label class="form-label">Province</label><select class="form-select" name="province" required><option value="-- Select --">-- Select --</option></select></div>
                            <div class="col-md-3"><label class="form-label">Town/Municipality/City</label><select class="form-select" name="municipality" required><option value="-- Select --">-- Select --</option></select></div>
                            <div class="col-md-3"><label class="form-label">Barangay</label><select class="form-select" name="barangay" required><option value="-- Select --">-- Select --</option></select></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><label class="form-label">Land Line</label><input type="text" class="form-control" name="landline"></div>
                            <div class="col-md-3"><label class="form-label">Mobile No</label><input type="text" class="form-control" name="mobile_no" required></div>
                            <div class="col-md-2"><label class="form-label">Gender</label>
                            <select class="form-select" name="gender" required>
                                <option value="" selected disabled>-- Select --</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                            <div class="col-md-2"><label class="form-label">Civil Status</label>
                            <select class="form-select" name="civil_status" required>
                                <option value="" selected disabled>-- Select --</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Widowed">Widowed</option>
                            </select>
                        </div>
                            <div class="col-md-2"><label class="form-label">Nationality</label>
                            <select class="form-select" name="nationality" required>
                            <option value="" selected disabled>-- Select --</option>
                            <option value="Filipino" >Filipino</option>
                            </select>
                        </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><label class="form-label">Date Of Birth</label><input type="date" class="form-control" name="date_of_birth" required></div>
                            <div class="col-md-3"><label class="form-label">Place of Birth</label><input type="text" class="form-control" name="place_of_birth" required></div>
                            <div class="col-md-3"><label class="form-label">Email Address</label><input type="email" class="form-control" name="email_address" required></div>
                            <div class="col-md-3"><label class="form-label">Religion</label><select class="form-select" name="religion" required><option value="-- Select --">-- Select --</option></select></div>
                        </div>

                        <!-- Educational Information -->
                    <h5 class="bg-info text-white p-2 rounded">Educational Information</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Primary School</label>
                            <select class="form-select" name="primary_school">
                                <option>-- Select --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Year Graduated</label>
                            <input type="text" class="form-control" name="primary_year_graduated">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Secondary School</label>
                            <select class="form-select" name="secondary_school">
                                <option>-- Select --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Year Graduated</label>
                            <input type="text" class="form-control" name="secondary_year_graduated">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tertiary School</label>
                            <select class="form-select" name="tertiary_school">
                                <option>-- Select --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Year Graduated</label>
                            <input type="text" class="form-control" name="tertiary_year_graduated">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Course Graduated</label>
                            <input type="text" class="form-control" name="tertiary_course" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Educational Plan</label>
                            <select class="form-select" name="educational_plan" >
                                <option>-- Select --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Academic Achievement</label>
                            <select class="form-select" name="academic_achievement" >
                                <option>-- Select --</option>
                            </select>
                        </div>
                    </div>

                    <!-- Work Information -->
                    <h5 class="bg-info text-white p-2 rounded">Work Information</h5>
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-2">
                            <label class="form-label">Working?</label>
                            <input type="checkbox" class="form-check-input ms-2" name="working">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Employer</label>
                            <input type="text" class="form-control" name="employer">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Work in Shifts?</label>
                            <input type="checkbox" class="form-check-input ms-2" name="work_shift">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Work Position</label>
                            <input type="text" class="form-control" name="work_position">
                        </div>
                    </div>

                    <!-- Family Connected to NCST -->
                    <h5 class="bg-info text-white p-2 rounded">Family Connected to NCST</h5>
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-2">
                            <label class="form-label">NCST Student</label>
                            <input type="checkbox" class="form-check-input ms-2" name="ncst_student">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">No of Siblings</label>
                            <input type="number" class="form-control" name="number_of_siblings" >
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">NCST Employee</label>
                            <input type="checkbox" class="form-check-input ms-2" name="ncst_employee">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Relationship</label>
                            <select class="form-select" name="ncst_relationship">
                                <option value="">-- Select --</option>
                            </select>
                        </div>
                    </div>

                    <!-- How did Student come to know about NCST? -->
                    <h5 class="bg-info text-white p-2 rounded">How did Student come to know about NCST?</h5>
                    <div class="mb-3">
                        <select class="form-select" name="how_know_ncst">
                            <option>-- Select --</option>
                        </select>
                    </div>

                    <!-- Other Information -->
                    <h5 class="bg-info text-white p-2 rounded">Other Information</h5>
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-2"><label class="form-label">Transferee?</label><input type="checkbox" class="form-check-input ms-2" name="transferee"></div>
                        <div class="col-md-2"><label class="form-label">ALS Graduate?</label><input type="checkbox" class="form-check-input ms-2" name="als_graduate"></div>
                        <div class="col-md-2"><label class="form-label">Returnee?</label><input type="checkbox" class="form-check-input ms-2" name="returnee"></div>
                        <div class="col-md-2"><label class="form-label">DTS Student?</label><input type="checkbox" class="form-check-input ms-2" name="dts_student"></div>
                        <div class="col-md-2"><label class="form-label">Cross Enrollee?</label><input type="checkbox" class="form-check-input ms-2" name="cross_enrollee"></div>
                        <div class="col-md-2"><label class="form-label">Foreign Student?</label><input type="checkbox" class="form-check-input ms-2" name="foreign_student"></div>
                    </div>


                    <!-- Parent/Guardian Information -->
                    <h5 class="bg-info text-white p-2 rounded">Parent/Guardian Information</h5>
                    <div class="mb-2"><strong>Father Information</strong></div>
                    <div class="row mb-3">
                        <div class="col-md-3"><label class="form-label">Family Name</label><input type="text" class="form-control" name="father_family_name" required></div>
                        <div class="col-md-3"><label class="form-label">Given Name</label><input type="text" class="form-control" name="father_given_name" required></div>
                        <div class="col-md-3"><label class="form-label">Middle Name</label><input type="text" class="form-control" name="father_middle_name" required></div>
                        <div class="col-md-3"><label class="form-label">Deceased?</label><input type="checkbox" class="form-check-input ms-2" name="father_deceased" ></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Father's Complete Address</label><input type="text" class="form-control" name="father_address" required></div>
                    <div class="row mb-3">
                        <div class="col-md-3"><label class="form-label">Father's Land Line</label><input type="text" class="form-control" name="father_landline" ></div>
                        <div class="col-md-3"><label class="form-label">Father's Mobile No</label><input type="text" class="form-control" name="father_mobile" required></div>
                        <div class="col-md-3"><label class="form-label">Father's Occupation</label><input type="text" class="form-control" name="father_occupation" required></div>
                    </div>

                    <div class="mb-2"><strong>Mother Information</strong></div>
                    <div class="row mb-3">
                        <div class="col-md-3"><label class="form-label">Family Name</label><input type="text" class="form-control" name="mother_family_name" required></div>
                        <div class="col-md-3"><label class="form-label">Given Name</label><input type="text" class="form-control" name="mother_given_name" required></div>
                        <div class="col-md-3"><label class="form-label">Middle Name</label><input type="text" class="form-control" name="mother_middle_name" required></div>
                        <div class="col-md-3"><label class="form-label">Deceased?</label><input type="checkbox" class="form-check-input ms-2" name="mother_deceased" ></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><label class="form-label">Mother Maiden Family Name</label><input type="text" class="form-control" name="mother_maiden_family_name" required></div>
                        <div class="col-md-4"><label class="form-label">Mother Maiden Given Name</label><input type="text" class="form-control" name="mother_maiden_given_name" required></div>
                        <div class="col-md-4"><label class="form-label">Mother Maiden Middle Name</label><input type="text" class="form-control" name="mother_maiden_middle_name" required></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Mother's Complete Address</label><input type="text" class="form-control" name="mother_address" required></div>
                    <div class="row mb-3">
                        <div class="col-md-3"><label class="form-label">Mother's Land Line</label><input type="text" class="form-control" name="mother_landline" ></div>
                        <div class="col-md-3"><label class="form-label">Mother's Mobile No</label><input type="text" class="form-control" name="mother_mobile" required></div>
                        <div class="col-md-3"><label class="form-label">Mother's Occupation</label><input type="text" class="form-control" name="mother_occupation" required></div>
                    </div>

                    <div class="mb-2"><strong>Guardian Information</strong></div>
                    <div class="row mb-3">
                        <div class="col-md-3"><label class="form-label">Family Name</label><input type="text" class="form-control" name="guardian_family_name" required></div>
                        <div class="col-md-3"><label class="form-label">Given Name</label><input type="text" class="form-control" name="guardian_given_name" required></div>
                        <div class="col-md-3"><label class="form-label">Middle Name</label><input type="text" class="form-control" name="guardian_middle_name" required></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Guardian Complete Address</label><input type="text" class="form-control" name="guardian_address" required></div>
                    <div class="row mb-3">
                        <div class="col-md-3"><label class="form-label">Guardian Land Line</label><input type="text" class="form-control" name="guardian_landline" ></div>
                        <div class="col-md-3"><label class="form-label">Guardian Mobile No</label><input type="text" class="form-control" name="guardian_mobile" required></div>
                        <div class="col-md-3"><label class="form-label">Guardian Occupation</label><input type="text" class="form-control" name="guardian_occupation" required></div>
                        <div class="col-md-3"><label class="form-label">Guardian Relationship</label><input type="text" class="form-control" name="guardian_relationship" required></div>
                    </div>
                    <div class="mb-3 text-end">
                        <button type="submit" class="btn btn-success" name="submit">Submit</button>
                        <button type="reset" class="btn btn-danger text-white ms-2">Cancel</button>
                    </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
    <!-- SweetAlert 2 JS Bundle -->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        // Default to requirements
        showSidebarSection('requirements');
    </script>
</body>
</html>
