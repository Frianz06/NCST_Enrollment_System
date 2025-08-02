<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Portal</title>
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
/* Extra small font size for tables */
.fs-xsmall {
  font-size: 0.75rem !important;
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
          <li class="nav-item"><a class="nav-link text-warning" href="#logout"><i class="bi bi-box-arrow-right me-1"></i> Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
          <div class="card-body text-center">
            <img src="images/ncst-logo.png" alt="NCST Logo" width="70" class="mb-3">
            <h3 class="fw-bold mb-2" style="color:#003399;">Welcome, Student!</h3>
            <p class="mb-0">This is your student portal. Use the tabs below to access your enrollment, accountabilities, grades, and instructor evaluation.</p>
          </div>
        </div>
        <!-- Bootstrap Tabs -->
        <ul class="nav nav-tabs mb-4" id="portalTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="enrollment-tab" data-bs-toggle="tab" data-bs-target="#enrollment" type="button" role="tab" aria-controls="enrollment" aria-selected="true">
              <i class="bi bi-pencil-square me-1"></i> Enrollment
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="accountabilities-tab" data-bs-toggle="tab" data-bs-target="#accountabilities" type="button" role="tab" aria-controls="accountabilities" aria-selected="false">
              <i class="bi bi-cash-coin me-1"></i> Accountabilities
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="grades-tab" data-bs-toggle="tab" data-bs-target="#grades" type="button" role="tab" aria-controls="grades" aria-selected="false">
              <i class="bi bi-journal-text me-1"></i> Grades Viewing
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="evaluation-tab" data-bs-toggle="tab" data-bs-target="#evaluation" type="button" role="tab" aria-controls="evaluation" aria-selected="false">
              <i class="bi bi-clipboard-check me-1"></i> Instructor Evaluation
            </button>
          </li>
        </ul>
        <div class="tab-content" id="portalTabContent">
          <div class="tab-pane fade show active" id="enrollment" role="tabpanel" aria-labelledby="enrollment-tab">
            <h4 class="fw-bold mb-3 mt-2" style="color:#003399;"><i class="bi bi-pencil-square me-2"></i>Enrollment</h4>
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
                  <tr><td>1</td><td>1</td><td>GE 003C</td><td>Mathematics in the Modern World</td><td>3.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>1</td><td>1</td><td>GE 005</td><td>Science, Technology & Society</td><td>3.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>1</td><td>1</td><td>GE 007</td><td>Contemporary World</td><td>3.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>1</td><td>1</td><td>GELEC 004-IT</td><td>Living in the IT Era</td><td>3.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>1</td><td>1</td><td>IT 101</td><td>Introduction to Computing</td><td>3.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>1</td><td>1</td><td>IT 102</td><td>Computer Programming 1</td><td>3.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>1</td><td>1</td><td>NCST 001</td><td>Nation Builders (NCST Culture 1)</td><td>3.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>1</td><td>1</td><td>NSTP 001</td><td>National Service Training Program 1</td><td>0.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>1</td><td>1</td><td>PATHFIT 1</td><td>Physical Activities Towards Health & Fitness 1</td><td>2.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>1</td><td>2</td><td>GE 004</td><td>Understanding the Self</td><td>3.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>1</td><td>2</td><td>GE 008</td><td>Art Appreciation</td><td>3.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>1</td><td>2</td><td>GELEC 012-IT</td><td>Reading Visual Art</td><td>3.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>1</td><td>2</td><td>IT 103</td><td>Computer Programming 2</td><td>3.00</td><td>Passed</td><td>IT 102</td><td>Passed</td><td>No</td></tr>
                  <tr><td>1</td><td>2</td><td>IT 104</td><td>Web System Technologies 1</td><td>3.00</td><td>Passed</td><td>IT 101</td><td>Passed</td><td>No</td></tr>
                  <tr><td>1</td><td>2</td><td>IT 104</td><td>Web System Technologies 1</td><td>3.00</td><td>Passed</td><td>IT 102</td><td>Passed</td><td>No</td></tr>
                  <tr><td>1</td><td>2</td><td>IT 105</td><td>Discrete Structures 1</td><td>3.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>1</td><td>2</td><td>IT 106</td><td>Networking 1</td><td>3.00</td><td>Passed</td><td>IT 101</td><td>Passed</td><td>No</td></tr>
                  <tr><td>1</td><td>2</td><td>NSTP 002</td><td>National Service Training Program 2</td><td>0.00</td><td>Passed</td><td>NSTP 001</td><td>Passed</td><td>No</td></tr>
                  <tr><td>1</td><td>2</td><td>PATHFIT 2</td><td>Physical Activities Towards Health & Fitness 2</td><td>2.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>2</td><td>1</td><td>GE 002</td><td>Readings in Philippine History</td><td>3.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>2</td><td>1</td><td>GE 006</td><td>Ethics</td><td>3.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>2</td><td>1</td><td>IT 201</td><td>Information Management 1</td><td>3.00</td><td>Passed</td><td>IT 103</td><td>Passed</td><td>No</td></tr>
                  <tr><td>2</td><td>1</td><td>IT 202</td><td>Interactive Media Design</td><td>3.00</td><td>Passed</td><td>GELEC 012-IT</td><td>Passed</td><td>No</td></tr>
                  <tr><td>2</td><td>1</td><td>IT 202</td><td>Interactive Media Design</td><td>3.00</td><td>Passed</td><td>IT 101</td><td>Passed</td><td>No</td></tr>
                  <tr><td>2</td><td>1</td><td>IT 203</td><td>Integrative Programming Technologies 1</td><td>3.00</td><td>Passed</td><td>IT 103</td><td>Passed</td><td>No</td></tr>
                  <tr><td>2</td><td>1</td><td>IT 203</td><td>Integrative Programming Technologies 1</td><td>3.00</td><td>Passed</td><td>IT 104</td><td>Passed</td><td>No</td></tr>
                  <tr><td>2</td><td>1</td><td>IT 204</td><td>Discrete Structures 2</td><td>3.00</td><td>Passed</td><td>IT 105</td><td>Passed</td><td>No</td></tr>
                  <tr><td>2</td><td>1</td><td>IT 205</td><td>Data Structures and Algorithms</td><td>3.00</td><td>Passed</td><td>IT 103</td><td>Passed</td><td>No</td></tr>
                  <tr><td>2</td><td>1</td><td>IT 206</td><td>Web System Technologies 2</td><td>3.00</td><td>Passed</td><td>IT 104</td><td>Passed</td><td>No</td></tr>
                  <tr><td>2</td><td>1</td><td>PATHFIT 3</td><td>Physical Activities Towards Health & Fitness 3</td><td>2.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>2</td><td>2</td><td>GE 001D</td><td>Purposive Communication</td><td>3.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>2</td><td>2</td><td>GELEC 009-IT</td><td>The Entrepreneurial Mind</td><td>3.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>2</td><td>2</td><td>IT 207</td><td>Application Development and Emerging Technologies 1</td><td>3.00</td><td>Passed</td><td>IT 203</td><td>Passed</td><td>No</td></tr>
                  <tr><td>2</td><td>2</td><td>IT 208</td><td>Object Oriented Programming</td><td>3.00</td><td>Passed</td><td>IT 203</td><td>Passed</td><td>No</td></tr>
                  <tr><td>2</td><td>2</td><td>IT 209</td><td>Platform Technologies</td><td>3.00</td><td>Passed</td><td>IT 101</td><td>Passed</td><td>No</td></tr>
                  <tr><td>2</td><td>2</td><td>IT 209</td><td>Platform Technologies</td><td>3.00</td><td>Passed</td><td>IT 203</td><td>Passed</td><td>No</td></tr>
                  <tr><td>2</td><td>2</td><td>IT 210</td><td>Human Computer Interaction 1</td><td>3.00</td><td>Passed</td><td>IT 205</td><td>Passed</td><td>No</td></tr>
                  <tr><td>2</td><td>2</td><td>IT 211</td><td>Information Management 2</td><td>3.00</td><td>Passed</td><td>IT 201</td><td>Passed</td><td>No</td></tr>
                  <tr><td>2</td><td>2</td><td>NCST 002</td><td>Nation Builders (NCST Culture 2)</td><td>3.00</td><td>Passed</td><td>NCST 001</td><td>Passed</td><td>No</td></tr>
                  <tr><td>2</td><td>2</td><td>PATHFIT 4</td><td>Physical Activities Towards Health & Fitness 4</td><td>2.00</td><td>Passed</td><td></td><td></td><td>No</td></tr>
                  <tr><td>3</td><td>1</td><td>IT 301</td><td>Human Computer Interaction 2</td><td>3.00</td><td></td><td>IT 210</td><td>Passed</td><td>Yes</td></tr>
                  <tr><td>3</td><td>1</td><td>IT 302</td><td>Systems Integration and Architecture 1</td><td>3.00</td><td></td><td>IT 209</td><td>Passed</td><td>Yes</td></tr>
                  <tr><td>3</td><td>1</td><td>IT 302</td><td>Systems Integration and Architecture 1</td><td>3.00</td><td></td><td>IT 210</td><td>Passed</td><td>Yes</td></tr>
                  <tr><td>3</td><td>1</td><td>IT 303</td><td>Networking 2</td><td>3.00</td><td></td><td>IT 106</td><td>Passed</td><td>Yes</td></tr>
                  <tr><td>3</td><td>1</td><td>IT 304</td><td>Quantitative Method</td><td>3.00</td><td></td><td>IT 204</td><td>Passed</td><td>Yes</td></tr>
                  <tr><td>3</td><td>1</td><td>IT 305</td><td>Social Issues and Professional Practice</td><td>3.00</td><td></td><td>IT 101</td><td>Passed</td><td>Yes</td></tr>
                  <tr><td>3</td><td>1</td><td>IT 306</td><td>Integrative Programming Technologies 2</td><td>3.00</td><td></td><td>IT 203</td><td>Passed</td><td>Yes</td></tr>
                  <tr><td>3</td><td>2</td><td>IT 307</td><td>Networking 3</td><td>3.00</td><td></td><td>IT 303</td><td></td><td>No</td></tr>
                  <tr><td>3</td><td>2</td><td>IT 308</td><td>Systems Integration and Architecture 2</td><td>3.00</td><td></td><td>IT 302</td><td></td><td>No</td></tr>
                  <tr><td>3</td><td>2</td><td>IT 309</td><td>Mobile Systems and Application</td><td>3.00</td><td></td><td>IT 306</td><td></td><td>No</td></tr>
                  <tr><td>3</td><td>2</td><td>IT 310</td><td>Information Assurance and Security 1</td><td>3.00</td><td></td><td>IT 303</td><td></td><td>No</td></tr>
                  <tr><td>3</td><td>2</td><td>IT 311</td><td>IT Capstone Project 1</td><td>3.00</td><td></td><td>IT 302</td><td></td><td>No</td></tr>
                  <tr><td>3</td><td>2</td><td>IT 311</td><td>IT Capstone Project 1</td><td>3.00</td><td></td><td>IT 304</td><td></td><td>No</td></tr>
                  <tr><td>3</td><td>2</td><td>NCST 003</td><td>Nation Builders (NCST Culture 3)</td><td>1.00</td><td></td><td>NCST 002</td><td>Passed</td><td>Yes</td></tr>
                  <tr><td>4</td><td>1</td><td>GE 009</td><td>Rizal's Life and Works</td><td>3.00</td><td></td><td></td><td></td><td>Yes</td></tr>
                  <tr><td>4</td><td>1</td><td>IT 401</td><td>Information Assurance and Security 2</td><td>3.00</td><td></td><td>IT 310</td><td></td><td>No</td></tr>
                  <tr><td>4</td><td>1</td><td>IT 402</td><td>Technopreneurship</td><td>3.00</td><td></td><td>GELEC 009-IT</td><td>Passed</td><td>Yes</td></tr>
                  <tr><td>4</td><td>1</td><td>IT 402</td><td>Technopreneurship</td><td>3.00</td><td></td><td>IT 309</td><td></td><td>No</td></tr>
                  <tr><td>4</td><td>1</td><td>IT 403</td><td>System Administration and Maintenance</td><td>3.00</td><td></td><td>IT 310</td><td></td><td>No</td></tr>
                  <tr><td>4</td><td>1</td><td>IT 404</td><td>IT Capstone Project 2</td><td>3.00</td><td></td><td>IT 311</td><td></td><td>No</td></tr>
                  <tr><td>4</td><td>1</td><td>NCST 004</td><td>Nation Builders (NCST Culture 4)</td><td>1.00</td><td></td><td>NCST 003</td><td></td><td>No</td></tr>
                  <tr><td>4</td><td>2</td><td>IT 405</td><td>IT Practicum (486 Hours)</td><td>6.00</td><td></td><td>IT 404</td><td></td><td>No</td></tr>
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
                        <option>Section A</option>
                        <option>Section B</option>
                        <option>Section C</option>
                      </select>
                    </div>
                    <div id="sectionSchedule" class="mt-3"></div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-primary" onclick="
                    var section = document.getElementById('sectionSelect').value;
                    document.getElementById('enrollFormSection').textContent = section;
                    document.getElementById('enrollFormSchedule').innerHTML = schedules[section] || '';
                    var eFormModal = new bootstrap.Modal(document.getElementById('enrollFormModal'));
                    eFormModal.show();
                    var sModal = bootstrap.Modal.getInstance(document.getElementById('sectionModal'));
                    sModal.hide();
                  ">Confirm Section</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Enrollment Form Modal -->
          <div class="modal fade" id="enrollFormModal" tabindex="-1" aria-labelledby="enrollFormModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header bg-info text-white">
                  <h5 class="modal-title" id="enrollFormModalLabel">Enrollment Form</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form id="enrollmentForm" onsubmit="event.preventDefault(); showPrintQueue();">
                    <div class="mb-3 text-start">
                      <label class="form-label">Name</label>
                      <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3 text-start">
                      <label class="form-label">Student ID</label>
                      <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3 text-start">
                      <label class="form-label">Course</label>
                      <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3 text-start">
                      <label class="form-label">Section</label>
                      <span id="enrollFormSection" class="fw-bold text-primary"></span>
                    </div>
                    <div id="enrollFormSchedule" class="mb-3"></div>
                    <div class="text-end">
                      <button type="submit" class="btn btn-success">Submit Enrollment</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- Print Queue Modal -->
          <div class="modal fade" id="printQueueModal" tabindex="-1" aria-labelledby="printQueueModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header bg-warning">
                  <h5 class="modal-title" id="printQueueModalLabel">Print Queue</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                  <i class="bi bi-printer display-4 text-warning mb-3"></i>
                  <h4 class="mb-2">Enrollment Form Submitted</h4>
                  <p class="mb-1">Your print queue number: <span class="fw-bold text-primary">45</span></p>
                  <p class="mb-3">Please wait for your number to be called for printing.</p>
                  <div class="progress mb-2" style="height: 8px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" style="width: 60%"></div>
                  </div>
                  <small class="text-muted">Estimated wait time: 3 minutes</small>
                  <div class="text-end mt-3">
                    <button type="button" class="btn btn-primary" onclick="showPaymentQueue();">Done Printing</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Payment Queue Modal -->
          <div class="modal fade" id="paymentQueueModal" tabindex="-1" aria-labelledby="paymentQueueModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header bg-success text-white">
                  <h5 class="modal-title" id="paymentQueueModalLabel">Payment Queue</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                  <i class="bi bi-cash-coin display-4 text-success mb-3"></i>
                  <h4 class="mb-2">Proceed to Payment</h4>
                  <p class="mb-1">Your payment queue number: <span class="fw-bold text-primary">88</span></p>
                  <p class="mb-3">Please wait for your number to be called for payment.</p>
                  <div class="progress mb-2" style="height: 8px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 60%"></div>
                  </div>
                  <small class="text-muted">Estimated wait time: 4 minutes</small>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="accountabilities" role="tabpanel" aria-labelledby="accountabilities-tab">
            <div class="card mb-4">
              <div class="card-header bg-light text-center">
                <h5 class="mb-0 fw-bold">Student Accountabilities</h5>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                      <tr>
                        <th class="text-center">Department</th>
                        <th class="text-center">Accountability</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Accountabilities will be listed here -->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="grades" role="tabpanel" aria-labelledby="grades-tab">
            <div class="card mb-4">
              <div class="card-header bg-light text-center">
                <h5 class="mb-0 fw-bold">Academic Records</h5>
              </div>
              <div class="card-body">
                <form>
                  <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                      <tbody>
                        <tr>
                          <td class="fw-semibold" style="width: 30%;">School Year</td>
                          <td style="width: 70%;">
                            <select class="form-select w-auto d-inline-block" style="min-width: 180px;">
                              <option selected disabled>Select School Year</option>
                              <option>2025-2026</option>
                              <option>2024-2025</option>
                              <option>2023-2024</option>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td class="fw-semibold">Semester</td>
                          <td>
                            <select class="form-select w-auto d-inline-block" style="min-width: 180px;">
                              <option selected disabled>Select Semester</option>
                              <option>1st Semester</option>
                              <option>2nd Semester</option>
                              <option>Summer</option>
                            </select>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="evaluation" role="tabpanel" aria-labelledby="evaluation-tab">
            <div class="card mb-4">
              <div class="card-header bg-light text-center">
                <h5 class="mb-0 fw-bold">Instructor Evaluation</h5>
              </div>
              <div class="card-body">
                <div class="alert alert-info mb-4">
                  Please evaluate your instructors for the current semester. Your feedback is important for improving the quality of teaching.
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                      <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Instructor</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Instructor evaluation rows will be listed here -->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
const schedules = {
  "Section A": `
    <table class="table table-sm table-bordered">
      <thead><tr><th>Day</th><th>Time</th><th>Subject</th></tr></thead>
      <tbody>
        <tr><td>Mon/Wed</td><td>8:00-10:00 AM</td><td>IT 101</td></tr>
        <tr><td>Tue/Thu</td><td>10:00-12:00 NN</td><td>GE 003C</td></tr>
      </tbody>
    </table>
  `,
  "Section B": `
    <table class="table table-sm table-bordered">
      <thead><tr><th>Day</th><th>Time</th><th>Subject</th></tr></thead>
      <tbody>
        <tr><td>Mon/Wed</td><td>1:00-3:00 PM</td><td>IT 102</td></tr>
        <tr><td>Fri</td><td>8:00-11:00 AM</td><td>GE 005</td></tr>
      </tbody>
    </table>
  `,
  "Section C": `
    <table class="table table-sm table-bordered">
      <thead><tr><th>Day</th><th>Time</th><th>Subject</th></tr></thead>
      <tbody>
        <tr><td>Tue/Thu</td><td>1:00-3:00 PM</td><td>IT 103</td></tr>
        <tr><td>Fri</td><td>1:00-4:00 PM</td><td>GE 007</td></tr>
      </tbody>
    </table>
  `
};

document.getElementById('sectionSelect').addEventListener('change', function() {
  const selected = this.value;
  document.getElementById('sectionSchedule').innerHTML = schedules[selected] || '';
});

function showPrintQueue() {
  var eFormModal = bootstrap.Modal.getInstance(document.getElementById('enrollFormModal'));
  eFormModal.hide();
  var pModal = new bootstrap.Modal(document.getElementById('printQueueModal'));
  pModal.show();
}
function showPaymentQueue() {
  var pModal = bootstrap.Modal.getInstance(document.getElementById('printQueueModal'));
  pModal.hide();
  var payModal = new bootstrap.Modal(document.getElementById('paymentQueueModal'));
  payModal.show();
}
</script>
</body>
</html> 