<?php
session_start();
require_once '../../db.php';

// Check if user is logged in and is either admission officer or registration officer
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'registration' && $_SESSION['role'] !== 'admission')) {
    header('Location: ../ncst_login.php');
    exit();
}

// Get enrollment data from session or URL parameter
$enrollment_data = $_SESSION['enrollment_data'] ?? null;
$student_id = $_GET['student_id'] ?? '';

if (!$enrollment_data && !$student_id) {
    header('Location: enrollment_page.php');
    exit();
}

// If we have student_id, get the enrollment data
if ($student_id) {
    $stmt = $conn->prepare("SELECT * FROM student_applications WHERE id = ? AND status = 'enrolled'");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    
    if (!$student) {
        header('Location: enrollment_page.php');
        exit();
    }
    
    // Generate enrollment data
    $enrollment_data = [
        'student' => $student,
        'section' => $student['section'],
        'subjects' => [], // Will be populated based on section
        'total_units' => 0,
        'fees' => [
            'tuition' => 11332.44,
            'laboratory' => 4000.00,
            'miscellaneous' => 4623.26,
            'lms' => 1770.00,
            'nstp' => 0.00,
            'omr' => 240.00
        ]
    ];
    
    // Get subjects based on section
    $section_subjects = getSectionSubjects($student['section'], $student['course'] ?? $student['track']);
    $enrollment_data['subjects'] = $section_subjects;
    $enrollment_data['total_units'] = array_sum(array_column($section_subjects, 'units'));
}

function getSectionSubjects($section, $course) {
    // Define subjects based on course and section
    if (stripos($course, 'BSIT') !== false) {
        if (stripos($section, '1') !== false) {
            return [
                ['code' => 'IT 101', 'description' => 'Programming 1', 'units' => 3, 'type' => 'Lec', 'days' => 'MWF', 'start_time' => '8:00 AM', 'end_time' => '9:00 AM', 'room' => 'Computer Lab 1', 'instructor' => 'SANTOS, P.'],
                ['code' => 'IT 102', 'description' => 'Computer Fundamentals', 'units' => 3, 'type' => 'Lec', 'days' => 'MWF', 'start_time' => '9:00 AM', 'end_time' => '10:00 AM', 'room' => 'Computer Lab 1', 'instructor' => 'SANTOS, P.'],
                ['code' => 'MATH 101', 'description' => 'Mathematics for IT', 'units' => 3, 'type' => 'Lec', 'days' => 'TTH', 'start_time' => '1:00 PM', 'end_time' => '2:30 PM', 'room' => 'Room 101', 'instructor' => 'GARCIA, M.'],
                ['code' => 'ENG 101', 'description' => 'English 1', 'units' => 3, 'type' => 'Lec', 'days' => 'TTH', 'start_time' => '2:30 PM', 'end_time' => '4:00 PM', 'room' => 'Room 102', 'instructor' => 'REYES, A.'],
                ['code' => 'FIL 101', 'description' => 'Filipino 1', 'units' => 3, 'type' => 'Lec', 'days' => 'MWF', 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'room' => 'Room 103', 'instructor' => 'CRUZ, L.'],
                ['code' => 'PE 101', 'description' => 'PE 1', 'units' => 2, 'type' => 'Lec', 'days' => 'F', 'start_time' => '1:00 PM', 'end_time' => '3:00 PM', 'room' => 'Gym', 'instructor' => 'LOPEZ, R.'],
                ['code' => 'NSTP 101', 'description' => 'NSTP 1', 'units' => 3, 'type' => 'Lec', 'days' => 'S', 'start_time' => '8:00 AM', 'end_time' => '11:00 AM', 'room' => 'Room 104', 'instructor' => 'MARTINEZ, J.']
            ];
        }
    } elseif (stripos($course, 'ABM') !== false) {
        return [
            ['code' => 'ABM 101', 'description' => 'Fundamentals of Accountancy', 'units' => 3, 'type' => 'Lec', 'days' => 'MWF', 'start_time' => '8:00 AM', 'end_time' => '9:00 AM', 'room' => 'Room 301', 'instructor' => 'CRUZ, P.'],
            ['code' => 'ABM 102', 'description' => 'Business Mathematics', 'units' => 3, 'type' => 'Lec', 'days' => 'MWF', 'start_time' => '9:00 AM', 'end_time' => '10:00 AM', 'room' => 'Room 301', 'instructor' => 'CRUZ, P.'],
            ['code' => 'ENG 101', 'description' => 'English for Academic Purposes', 'units' => 3, 'type' => 'Lec', 'days' => 'TTH', 'start_time' => '1:00 PM', 'end_time' => '2:30 PM', 'room' => 'Room 302', 'instructor' => 'REYES, A.'],
            ['code' => 'FIL 101', 'description' => 'Filipino sa Piling Larang', 'units' => 3, 'type' => 'Lec', 'days' => 'TTH', 'start_time' => '2:30 PM', 'end_time' => '4:00 PM', 'room' => 'Room 302', 'instructor' => 'REYES, A.'],
            ['code' => 'PE 101', 'description' => 'PE and Health', 'units' => 2, 'type' => 'Lec', 'days' => 'F', 'start_time' => '1:00 PM', 'end_time' => '3:00 PM', 'room' => 'Gym', 'instructor' => 'LOPEZ, R.']
        ];
    }
    
    return [];
}

$student = $enrollment_data['student'];
$section = $enrollment_data['section'];
$subjects = $enrollment_data['subjects'];
$total_units = $enrollment_data['total_units'];
$fees = $enrollment_data['fees'];

$cash_total = $fees['tuition'] + $fees['laboratory'] + $fees['miscellaneous'] + $fees['lms'] + $fees['nstp'] + $fees['omr'];
$installment_charge = $cash_total * 0.05; // 5% installment charge
$installment_total = $cash_total + $installment_charge;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Registration | NCST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman:wght@400;700&display=swap" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; padding: 0; }
            .certificate { box-shadow: none; border: none; }
        }
        
        body { 
            font-family: 'Times New Roman', serif; 
            background: #f4f6fb; 
            font-size: 12px;
        }
        
        .certificate {
            background: white;
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.1);
            border: 2px solid #003399;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #003399;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .school-name {
            font-size: 18px;
            font-weight: bold;
            color: #003399;
            margin-bottom: 5px;
        }
        
        .school-address {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .certificate-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 15px 0;
        }
        
        .student-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .info-group {
            margin-bottom: 10px;
        }
        
        .info-label {
            font-weight: bold;
            color: #003399;
            font-size: 11px;
        }
        
        .info-value {
            font-size: 11px;
            border-bottom: 1px solid #ccc;
            padding: 2px 0;
            min-height: 15px;
        }
        
        .subjects-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 10px;
        }
        
        .subjects-table th,
        .subjects-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
        
        .subjects-table th {
            background: #f0f0f0;
            font-weight: bold;
            font-size: 9px;
        }
        
        .subjects-table .description {
            text-align: left;
            width: 25%;
        }
        
        .fees-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin: 20px 0;
        }
        
        .fees-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        
        .fees-table th,
        .fees-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }
        
        .fees-table th {
            background: #f0f0f0;
            font-weight: bold;
            font-size: 9px;
        }
        
        .payment-schedule {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        
        .payment-schedule th,
        .payment-schedule td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
        
        .payment-schedule th {
            background: #f0f0f0;
            font-weight: bold;
            font-size: 9px;
        }
        
        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
            text-align: center;
        }
        
        .signature-box {
            border: 1px solid #000;
            padding: 10px;
            min-height: 60px;
        }
        
        .signature-label {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .total-units {
            font-weight: bold;
            text-align: right;
            padding: 5px;
            background: #f0f0f0;
        }
        
        .notes {
            font-size: 9px;
            margin-top: 15px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="no-print text-center mb-3">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer"></i> Print Certificate
            </button>
            <a href="dashboard.php" class="btn btn-secondary ms-2">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        
        <div class="certificate">
            <!-- Header -->
            <div class="header">
                <div class="school-name">NATIONAL COLLEGE OF SCIENCE AND TECHNOLOGY</div>
                <div class="school-address">Amafel Building, Aguinaldo Highway, Dasmarinas, Cavite 4114</div>
                <div class="school-address">www.ncst.edu.ph</div>
                <div class="certificate-title">Certificate of Registration</div>
            </div>
            
            <!-- Student Information -->
            <div class="student-info">
                <div>
                    <div class="info-group">
                        <div class="info-label">Student No.:</div>
                        <div class="info-value"><?php echo htmlspecialchars($student['student_id'] ?? ''); ?></div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Family Name:</div>
                        <div class="info-value"><?php echo htmlspecialchars($student['last_name'] ?? ''); ?></div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Given Name:</div>
                        <div class="info-value"><?php echo htmlspecialchars($student['first_name'] ?? ''); ?></div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Middle Name:</div>
                        <div class="info-value"><?php echo htmlspecialchars($student['middle_name'] ?? ''); ?></div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Address:</div>
                        <div class="info-value"><?php echo htmlspecialchars($student['address'] ?? ''); ?></div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Contact No.:</div>
                        <div class="info-value"><?php echo htmlspecialchars($student['phone'] ?? ''); ?></div>
                    </div>
                </div>
                <div>
                    <div class="info-group">
                        <div class="info-label">Course Code:</div>
                        <div class="info-value"><?php echo htmlspecialchars($student['course'] ?? $student['track'] ?? ''); ?></div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Year Level:</div>
                        <div class="info-value"><?php echo htmlspecialchars($student['level'] ?? ''); ?></div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Gender:</div>
                        <div class="info-value"><?php echo htmlspecialchars($student['gender'] ?? ''); ?></div>
                    </div>
                    <?php if (stripos($student['course'] ?? '', 'BS') !== false): ?>
                    <div class="info-group">
                        <div class="info-label">Semester:</div>
                        <div class="info-value">1st Semester</div>
                    </div>
                    <?php endif; ?>
                    <div class="info-group">
                        <div class="info-label">S.Y.:</div>
                        <div class="info-value"><?php echo date('Y'); ?>-<?php echo date('Y') + 1; ?></div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Section:</div>
                        <div class="info-value"><?php echo htmlspecialchars($section); ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Subjects Table -->
            <table class="subjects-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th class="description">Description</th>
                        <th>Units</th>
                        <th>Type</th>
                        <th>Days</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Section</th>
                        <th>Room</th>
                        <th>Instructor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subjects as $subject): ?>
                    <tr>
                        <td><?php echo $subject['code']; ?></td>
                        <td class="description"><?php echo $subject['description']; ?></td>
                        <td><?php echo $subject['units']; ?></td>
                        <td><?php echo $subject['type']; ?></td>
                        <td><?php echo $subject['days']; ?></td>
                        <td><?php echo $subject['start_time']; ?></td>
                        <td><?php echo $subject['end_time']; ?></td>
                        <td><?php echo $section; ?></td>
                        <td><?php echo $subject['room']; ?></td>
                        <td><?php echo $subject['instructor']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="total-units">
                        <td colspan="2"><strong>TOTAL UNITS</strong></td>
                        <td><strong><?php echo $total_units; ?></strong></td>
                        <td colspan="7"></td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Fees and Payment Schedule -->
            <div class="fees-section">
                <!-- Assessment of Fees -->
                <div>
                    <h6 style="font-weight: bold; margin-bottom: 10px;">Assessment of Fees</h6>
                    <table class="fees-table">
                        <tr>
                            <th colspan="2">Cash Breakdown</th>
                        </tr>
                        <tr>
                            <td>Tuition Fee</td>
                            <td style="text-align: right;"><?php echo number_format($fees['tuition'], 2); ?></td>
                        </tr>
                        <tr>
                            <td>Laboratory Fee</td>
                            <td style="text-align: right;"><?php echo number_format($fees['laboratory'], 2); ?></td>
                        </tr>
                        <tr>
                            <td>Miscellaneous</td>
                            <td style="text-align: right;"><?php echo number_format($fees['miscellaneous'], 2); ?></td>
                        </tr>
                        <tr>
                            <td>LMS</td>
                            <td style="text-align: right;"><?php echo number_format($fees['lms'], 2); ?></td>
                        </tr>
                        <tr>
                            <td>NSTP/ROTC</td>
                            <td style="text-align: right;"><?php echo number_format($fees['nstp'], 2); ?></td>
                        </tr>
                        <tr>
                            <td>OMR</td>
                            <td style="text-align: right;"><?php echo number_format($fees['omr'], 2); ?></td>
                        </tr>
                        <tr style="font-weight: bold;">
                            <td>Cash Total</td>
                            <td style="text-align: right;"><?php echo number_format($cash_total, 2); ?></td>
                        </tr>
                        <tr>
                            <td>Installment Charge</td>
                            <td style="text-align: right;"><?php echo number_format($installment_charge, 2); ?></td>
                        </tr>
                        <tr style="font-weight: bold;">
                            <td>Installment Total</td>
                            <td style="text-align: right;"><?php echo number_format($installment_total, 2); ?></td>
                        </tr>
                    </table>
                    <div class="notes">
                        Installment charge does not apply to full-payment transaction(s).
                    </div>
                </div>
                
                <!-- Schedule of Payment -->
                <div>
                    <h6 style="font-weight: bold; margin-bottom: 10px;">Schedule of Payment(s)</h6>
                    <table class="payment-schedule">
                        <tr>
                            <th>Due Date</th>
                            <th>Amount</th>
                        </tr>
                        <tr>
                            <td>Upon Registration</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>PRELIM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>MIDTERM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>PREFINALS</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>FINALS</td>
                            <td></td>
                        </tr>
                    </table>
                    <div class="notes">
                        Outright payment of adding/dropping charge is required when adding/dropping class schedule(s).
                    </div>
                </div>
            </div>
            
            <!-- Signatures -->
            <div class="signatures">
                <div class="signature-box">
                    <div class="signature-label">FINANCE DEPARTMENT</div>
                </div>
                <div class="signature-box">
                    <div class="signature-label">FOR EXAMINATION</div>
                </div>
                <div class="signature-box">
                    <div class="signature-label">CASHIER</div>
                </div>
            </div>
            
            <div class="signatures" style="margin-top: 10px;">
                <div class="signature-box">
                    <div class="signature-label">PRELIM</div>
                </div>
                <div class="signature-box">
                    <div class="signature-label">MIDTERM</div>
                </div>
                <div class="signature-box">
                    <div class="signature-label">MAJOR SPECIAL</div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 