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
</script>
</body>
</html> 