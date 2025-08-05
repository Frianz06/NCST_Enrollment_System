<?php
session_start();
require_once __DIR__ . '/db.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit;
}

$student_id = $_SESSION['student_id'];
$section_id = $_GET['section_id'] ?? null;

if (!$section_id) {
    header('Location: student_portal.php');
    exit;
}

// Get student details
$stmt = $conn->prepare('
    SELECT s.*, sa.first_name, sa.middle_name, sa.last_name, sa.address, sa.mobile, sa.landline, sa.gender
    FROM students s 
    LEFT JOIN student_applications sa ON s.student_id = sa.student_id 
    WHERE s.student_id = ?
');
$stmt->bind_param('s', $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

// Get section details
$stmt = $conn->prepare('SELECT * FROM sections WHERE id = ?');
$stmt->bind_param('i', $section_id);
$stmt->execute();
$section = $stmt->get_result()->fetch_assoc();

// Get subjects for the student's program and year level
$stmt = $conn->prepare('SELECT * FROM subjects WHERE program_id = ? AND year_level = ? AND semester = ? ORDER BY subject_code');
$stmt->bind_param('iss', $student['program_id'], $section['year_level'], $section['semester']);
$stmt->execute();
$subjects = $stmt->get_result();

// Calculate total units
$total_units = 0;
$enrolled_subjects = [];
while ($subject = $subjects->fetch_assoc()) {
    $total_units += $subject['units'];
    $enrolled_subjects[] = $subject;
}

// Generate reference number
$reference = date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

// Calculate fees
$tuition_fee = $total_units * 630.69; // Per unit rate
$laboratory_fee = 4000.00;
$miscellaneous = 4623.26;
$lms = 1770.00;
$nstp_rotc = 0.00;
$omr = 240.00;
$cash_total = $tuition_fee + $laboratory_fee + $miscellaneous + $lms + $nstp_rotc + $omr;
$installment_charge = $cash_total * 0.0473; // 4.73% installment charge
$installment_total = $cash_total + $installment_charge;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Registration - <?php echo htmlspecialchars($student['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="faviconn.ico" type="image/x-icon">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            background: #f8f9fa;
        }
        .certificate {
            background: white;
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #003399;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #003399;
            margin-bottom: 5px;
        }
        .certificate-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .student-info {
            margin-bottom: 30px;
        }
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            width: 120px;
            min-width: 120px;
        }
        .info-value {
            flex: 1;
        }
        .subjects-table {
            margin-bottom: 30px;
        }
        .subjects-table th {
            background: #003399;
            color: white;
            font-size: 12px;
            padding: 8px 4px;
            text-align: center;
        }
        .subjects-table td {
            font-size: 11px;
            padding: 6px 4px;
            vertical-align: middle;
        }
        .fees-table {
            margin-bottom: 20px;
        }
        .fees-table th {
            background: #f8f9fa;
            font-size: 12px;
            padding: 8px;
        }
        .fees-table td {
            font-size: 12px;
            padding: 8px;
        }
        .total-row {
            font-weight: bold;
            background: #e9ecef;
        }
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        @media print {
            .print-btn {
                display: none;
            }
            body {
                background: white;
            }
            .certificate {
                box-shadow: none;
                margin: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="print-btn">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="bi bi-printer"></i> Print
        </button>
        <a href="student_portal.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="certificate">
        <!-- Header -->
        <div class="header">
            <div class="school-name">NATIONAL COLLEGE OF SCIENCE AND TECHNOLOGY</div>
            <div style="font-size: 12px; color: #666;">
                Amafel Building, Aguinaldo Highway, Dasmarinas, Cavite 4114<br>
                www.ncst.edu.ph
            </div>
            <div class="certificate-title">CERTIFICATE OF REGISTRATION</div>
        </div>

        <!-- Student Information -->
        <div class="student-info">
            <div class="info-row">
                <div class="info-label">Reference:</div>
                <div class="info-value"><?php echo $reference; ?></div>
                <div class="info-label" style="margin-left: 40px;">Student No.:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['student_id']); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Last Name:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['last_name'] ?? $student['name']); ?></div>
                <div class="info-label" style="margin-left: 40px;">First Name:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['first_name'] ?? ''); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Middle Name:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['middle_name'] ?? ''); ?></div>
                <div class="info-label" style="margin-left: 40px;">Address:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['address'] ?? ''); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Contact No.:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['mobile'] ?? ''); ?></div>
                <div class="info-label" style="margin-left: 40px;">Gender:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['gender'] ?? ''); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Course Code:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['course']); ?></div>
                <div class="info-label" style="margin-left: 40px;">Semester:</div>
                <div class="info-value"><?php echo htmlspecialchars($section['semester']); ?> Semester</div>
            </div>
            <div class="info-row">
                <div class="info-label">Year Level:</div>
                <div class="info-value"><?php echo htmlspecialchars($section['year_level']); ?></div>
                <div class="info-label" style="margin-left: 40px;">SY:</div>
                <div class="info-value"><?php echo date('Y'); ?>-<?php echo date('Y') + 1; ?></div>
            </div>
        </div>

        <!-- Enrolled Subjects/Schedule -->
        <div class="subjects-table">
            <h6 class="fw-bold mb-2">Enrolled Subjects/Schedule</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Units</th>
                            <th>Type</th>
                            <th>Days</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Section</th>
                            <th>Room</th>
                            <th>Instructor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($enrolled_subjects as $subject): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($subject['subject_code']); ?></td>
                            <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($subject['units']); ?></td>
                            <td class="text-center">Lec</td>
                            <td class="text-center">M</td>
                            <td class="text-center">8:00AM</td>
                            <td class="text-center">9:00AM</td>
                            <td class="text-center"><?php echo htmlspecialchars($section['section_name']); ?></td>
                            <td class="text-center">Room 101</td>
                            <td class="text-center">TBA</td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="table-light">
                            <td colspan="2" class="text-end fw-bold">Total Units:</td>
                            <td class="text-center fw-bold"><?php echo $total_units; ?></td>
                            <td colspan="7"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Assessment of Fees -->
        <div class="fees-table">
            <h6 class="fw-bold mb-2">Assessment of Fees</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Fee Type</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Cash</td>
                            <td class="text-end"></td>
                        </tr>
                        <tr>
                            <td>Tuition Fee</td>
                            <td class="text-end">₱<?php echo number_format($tuition_fee, 2); ?></td>
                        </tr>
                        <tr>
                            <td>Laboratory Fee</td>
                            <td class="text-end">₱<?php echo number_format($laboratory_fee, 2); ?></td>
                        </tr>
                        <tr>
                            <td>Miscellaneous</td>
                            <td class="text-end">₱<?php echo number_format($miscellaneous, 2); ?></td>
                        </tr>
                        <tr>
                            <td>LMS</td>
                            <td class="text-end">₱<?php echo number_format($lms, 2); ?></td>
                        </tr>
                        <tr>
                            <td>NSTP/ROTC</td>
                            <td class="text-end">₱<?php echo number_format($nstp_rotc, 2); ?></td>
                        </tr>
                        <tr>
                            <td>OMR</td>
                            <td class="text-end">₱<?php echo number_format($omr, 2); ?></td>
                        </tr>
                        <tr class="total-row">
                            <td><strong>Cash Total:</strong></td>
                            <td class="text-end"><strong>₱<?php echo number_format($cash_total, 2); ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Installment -->
        <div class="fees-table">
            <h6 class="fw-bold mb-2">Installment</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <tbody>
                        <tr>
                            <td>Installment Charge</td>
                            <td class="text-end">₱<?php echo number_format($installment_charge, 2); ?></td>
                        </tr>
                        <tr class="total-row">
                            <td><strong>Installment Total:</strong></td>
                            <td class="text-end"><strong>₱<?php echo number_format($installment_total, 2); ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Get Queue Number Button -->
        <div class="text-center mt-4">
            <button class="btn btn-warning btn-lg" onclick="getQueueNumber()">
                <i class="bi bi-ticket-perforated"></i> Get Queue Number
            </button>
        </div>
    </div>

    <!-- Queue Number Modal -->
    <div class="modal fade" id="queueModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Queue Number for Evaluation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="queueNumberDisplay">
                        <h2 class="text-primary mb-3">Your Queue Number</h2>
                        <div class="display-4 fw-bold text-success mb-3" id="queueNumber">E-001</div>
                        <p class="text-muted">Please wait for your number to be called.</p>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            Queue cutoff time: 5:00 PM daily
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="queue_display.php" class="btn btn-primary" target="_blank">View Live Queue</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function getQueueNumber() {
            // Show loading
            document.getElementById('queueNumberDisplay').innerHTML = '<div class="spinner-border text-primary" role="status"></div><p>Generating queue number...</p>';
            
            // Make AJAX request to get queue number
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
                    document.getElementById('queueNumberDisplay').innerHTML = `
                        <h2 class="text-primary mb-3">Your Queue Number</h2>
                        <div class="display-4 fw-bold text-success mb-3">${data.queue_number}</div>
                        <p class="text-muted">Please wait for your number to be called.</p>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            Queue cutoff time: 5:00 PM daily
                        </div>
                    `;
                } else {
                    document.getElementById('queueNumberDisplay').innerHTML = `
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            ${data.message}
                        </div>
                    `;
                }
                
                // Show modal
                new bootstrap.Modal(document.getElementById('queueModal')).show();
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('queueNumberDisplay').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-x-circle"></i>
                        Error generating queue number. Please try again.
                    </div>
                `;
                new bootstrap.Modal(document.getElementById('queueModal')).show();
            });
        }
    </script>
</body>
</html> 