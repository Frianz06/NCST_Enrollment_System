<?php
session_start();
require_once '../../db.php';

// Check if user is logged in and is either admission officer or registration officer
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'registration' && $_SESSION['role'] !== 'admission')) {
    header('Location: ../ncst_login.php');
    exit();
}

// Get student ID from URL parameter
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';

if (empty($student_id)) {
    header('Location: enrollment_page.php');
    exit();
}

// Get student information
$stmt = $conn->prepare("SELECT * FROM student_applications WHERE id = ? AND status = 'approved'");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    header('Location: enrollment_page.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enrolled_subjects = $_POST['enrolled_subjects'] ?? [];
    $enrollment_notes = $_POST['enrollment_notes'] ?? '';
    
    if (!empty($enrolled_subjects)) {
        // Validate enrollment
        $errors = [];
        $total_units = 0;
        $schedule_conflicts = [];
        
        // Check for schedule conflicts
        for ($i = 0; $i < count($enrolled_subjects); $i++) {
            for ($j = $i + 1; $j < count($enrolled_subjects); $j++) {
                $subject1 = $enrolled_subjects[$i];
                $subject2 = $enrolled_subjects[$j];
                
                if ($subject1['schedule'] === $subject2['schedule']) {
                    $schedule_conflicts[] = $subject1['name'] . ' and ' . $subject2['name'];
                }
            }
            
            // Calculate total units
            $total_units += $subject_offerings[array_search($enrolled_subjects[$i]['code'], array_column($subject_offerings, 'code'))]['units'];
        }
        
        // Validation checks
        if ($total_units < 12) {
            $errors[] = "Minimum of 12 units required. Current total: " . $total_units . " units.";
        }
        
        if ($total_units > 24) {
            $errors[] = "Maximum of 24 units allowed. Current total: " . $total_units . " units.";
        }
        
        if (!empty($schedule_conflicts)) {
            $errors[] = "Schedule conflicts detected: " . implode(', ', $schedule_conflicts);
        }
        
        if (empty($errors)) {
            // Generate student ID sequentially
            $type = '';
            if (!empty($student['student_type'])) {
                $type = strtolower($student['student_type']);
            } elseif (!empty($student['admission_type'])) {
                $type = strtolower($student['admission_type']);
            } elseif (!empty($student['course']) && stripos($student['course'], 'shs') !== false) {
                $type = 'senior high';
            } else {
                $type = 'college';
            }
            
            if ($type === 'senior high' || $type === 'shs' || $type === 'seniorhigh' || in_array(strtoupper($student['course'] ?? ''), ['STEM','ABM','HUMSS','GAS'])) {
                $prefix = '25-';
                $id_query = $conn->query("SELECT student_id FROM student_applications WHERE student_id LIKE '25-%' ORDER BY student_id DESC LIMIT 1");
            } else {
                $prefix = '2025-';
                $id_query = $conn->query("SELECT student_id FROM student_applications WHERE student_id LIKE '2025-%' ORDER BY student_id DESC LIMIT 1");
            }
            $next_number = 1;
            if ($id_query && $row = $id_query->fetch_assoc()) {
                $last_id = $row['student_id'];
                $last_num = intval(substr($last_id, strpos($last_id, '-')+1));
                $next_number = $last_num + 1;
            }
            $student_id_generated = $prefix . str_pad($next_number, 5, '0', STR_PAD_LEFT);
            
            // Convert enrolled subjects to JSON for storage
            $enrolled_subjects_json = json_encode($enrolled_subjects);
            
            // Update student record
            $update_stmt = $conn->prepare("UPDATE student_applications SET status = 'enrolled', student_id = ?, subjects = ?, enrollment_notes = ?, enrollment_type = 'manual', total_units = ?, academic_year = ? WHERE id = ?");
            $update_stmt->bind_param('sssssi', $student_id_generated, $enrolled_subjects_json, $enrollment_notes, $total_units, $academic_year, $student_id);
            
            if ($update_stmt->execute()) {
                $_SESSION['enrollment_success'] = "Student enrolled successfully! Student ID: " . $student_id_generated . " | Total Units: " . $total_units;
                header('Location: dashboard.php');
                exit();
            } else {
                $error = "Error enrolling student: " . $update_stmt->error;
            }
        } else {
            $error = "Enrollment validation failed:<br>" . implode('<br>', $errors);
        }
    } else {
        $error = "Please select at least one subject.";
    }
}

// Define subject offerings with detailed information
$subject_offerings = [];
$course = $student['course'] ?? $student['track'] ?? '';

if (stripos($course, 'BSIT') !== false) {
    $subject_offerings = [
        [
            'code' => 'IT101',
            'name' => 'Programming 1',
            'units' => 3,
            'section' => 'BSIT-1A',
            'schedule' => 'MWF 8:00-9:30',
            'room' => 'Computer Lab 1',
            'instructor' => 'Prof. Santos',
            'slots' => 15,
            'enrolled' => 12,
            'available' => 3
        ],
        [
            'code' => 'IT102',
            'name' => 'Computer Fundamentals',
            'units' => 3,
            'section' => 'BSIT-1A',
            'schedule' => 'MWF 9:30-11:00',
            'room' => 'Computer Lab 1',
            'instructor' => 'Prof. Santos',
            'slots' => 15,
            'enrolled' => 15,
            'available' => 0
        ],
        [
            'code' => 'IT103',
            'name' => 'Database Management',
            'units' => 3,
            'section' => 'BSIT-1B',
            'schedule' => 'TTH 8:00-9:30',
            'room' => 'Computer Lab 2',
            'instructor' => 'Prof. Garcia',
            'slots' => 15,
            'enrolled' => 10,
            'available' => 5
        ],
        [
            'code' => 'IT104',
            'name' => 'Web Development',
            'units' => 3,
            'section' => 'BSIT-1B',
            'schedule' => 'TTH 9:30-11:00',
            'room' => 'Computer Lab 2',
            'instructor' => 'Prof. Garcia',
            'slots' => 15,
            'enrolled' => 8,
            'available' => 7
        ],
        [
            'code' => 'GE101',
            'name' => 'English 1',
            'units' => 3,
            'section' => 'BSIT-1A',
            'schedule' => 'MWF 1:00-2:30',
            'room' => 'Room 201',
            'instructor' => 'Prof. Cruz',
            'slots' => 30,
            'enrolled' => 25,
            'available' => 5
        ],
        [
            'code' => 'GE102',
            'name' => 'Mathematics for IT',
            'units' => 3,
            'section' => 'BSIT-1A',
            'schedule' => 'MWF 2:30-4:00',
            'room' => 'Room 202',
            'instructor' => 'Prof. Reyes',
            'slots' => 30,
            'enrolled' => 20,
            'available' => 10
        ]
    ];
} elseif (stripos($course, 'BSIS') !== false) {
    $subject_offerings = [
        [
            'code' => 'IS101',
            'name' => 'Information Systems',
            'units' => 3,
            'section' => 'BSIS-1A',
            'schedule' => 'MWF 8:00-9:30',
            'room' => 'Computer Lab 3',
            'instructor' => 'Prof. Lopez',
            'slots' => 15,
            'enrolled' => 12,
            'available' => 3
        ],
        [
            'code' => 'IS102',
            'name' => 'Business Analytics',
            'units' => 3,
            'section' => 'BSIS-1A',
            'schedule' => 'MWF 9:30-11:00',
            'room' => 'Computer Lab 3',
            'instructor' => 'Prof. Lopez',
            'slots' => 15,
            'enrolled' => 10,
            'available' => 5
        ],
        [
            'code' => 'GE101',
            'name' => 'English 1',
            'units' => 3,
            'section' => 'BSIS-1A',
            'schedule' => 'TTH 1:00-2:30',
            'room' => 'Room 203',
            'instructor' => 'Prof. Cruz',
            'slots' => 30,
            'enrolled' => 25,
            'available' => 5
        ]
    ];
} elseif (stripos($course, 'BSCS') !== false) {
    $subject_offerings = [
        [
            'code' => 'CS101',
            'name' => 'Computer Science Fundamentals',
            'units' => 3,
            'section' => 'BSCS-1A',
            'schedule' => 'MWF 8:00-9:30',
            'room' => 'Computer Lab 4',
            'instructor' => 'Prof. Martinez',
            'slots' => 15,
            'enrolled' => 12,
            'available' => 3
        ],
        [
            'code' => 'CS102',
            'name' => 'Programming Logic',
            'units' => 3,
            'section' => 'BSCS-1A',
            'schedule' => 'MWF 9:30-11:00',
            'room' => 'Computer Lab 4',
            'instructor' => 'Prof. Martinez',
            'slots' => 15,
            'enrolled' => 10,
            'available' => 5
        ],
        [
            'code' => 'GE101',
            'name' => 'English 1',
            'units' => 3,
            'section' => 'BSCS-1A',
            'schedule' => 'TTH 1:00-2:30',
            'room' => 'Room 204',
            'instructor' => 'Prof. Cruz',
            'slots' => 30,
            'enrolled' => 25,
            'available' => 5
        ]
    ];
} elseif (stripos($course, 'STEM') !== false) {
    $subject_offerings = [
        [
            'code' => 'STEM101',
            'name' => 'General Mathematics',
            'units' => 3,
            'section' => 'STEM 11A',
            'schedule' => 'MWF 8:00-9:30',
            'room' => 'Room 301',
            'instructor' => 'Prof. Reyes',
            'slots' => 25,
            'enrolled' => 20,
            'available' => 5
        ],
        [
            'code' => 'STEM102',
            'name' => 'General Biology 1',
            'units' => 3,
            'section' => 'STEM 11A',
            'schedule' => 'MWF 9:30-11:00',
            'room' => 'Science Lab 1',
            'instructor' => 'Prof. Santos',
            'slots' => 25,
            'enrolled' => 18,
            'available' => 7
        ],
        [
            'code' => 'GE101',
            'name' => 'English for Academic Purposes',
            'units' => 3,
            'section' => 'STEM 11A',
            'schedule' => 'TTH 1:00-2:30',
            'room' => 'Room 302',
            'instructor' => 'Prof. Cruz',
            'slots' => 30,
            'enrolled' => 25,
            'available' => 5
        ]
    ];
} else {
    // Default subject offerings for other courses
    $subject_offerings = [
        [
            'code' => 'GE101',
            'name' => 'English 1',
            'units' => 3,
            'section' => 'Section A',
            'schedule' => 'MWF 8:00-9:30',
            'room' => 'Room 101',
            'instructor' => 'Prof. Cruz',
            'slots' => 30,
            'enrolled' => 25,
            'available' => 5
        ],
        [
            'code' => 'GE102',
            'name' => 'Mathematics 1',
            'units' => 3,
            'section' => 'Section A',
            'schedule' => 'MWF 9:30-11:00',
            'room' => 'Room 102',
            'instructor' => 'Prof. Reyes',
            'slots' => 30,
            'enrolled' => 20,
            'available' => 10
        ]
    ];
}

// Get student's year level and semester
$year_level = '';
$semester = '';
$academic_year = date('Y') . '-' . (date('Y') + 1);

if (stripos($course, 'BSIT') !== false || stripos($course, 'BSIS') !== false || stripos($course, 'BSCS') !== false) {
    $year_level = '1st Year';
    $semester = '1st Semester';
} elseif (stripos($course, 'STEM') !== false || stripos($course, 'ABM') !== false || stripos($course, 'HUMSS') !== false || stripos($course, 'GAS') !== false) {
    $year_level = 'Grade 11';
    $semester = '1st Semester';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="../admin/admin.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../../faviconn.ico">
    <style>
        body { 
            font-family: 'Poppins', Arial, sans-serif; 
            background: #f4f6fb; 
        }
        .ncst-header {
            background: #003399;
            color: #fff;
            padding: 1.5rem 0 1rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 1.5rem 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        }
        .ncst-card {
            border-radius: 1.2rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            border: none;
        }
        .btn-ncst {
            background: #FFD600;
            color: #003399;
            font-weight: bold;
            border: none;
        }
        .btn-ncst:hover {
            background: #e6c200;
            color: #003399;
        }
        .student-info {
            background: linear-gradient(135deg, #003399 0%, #0055cc 100%);
            color: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .form-select:focus {
            border-color: #003399;
            box-shadow: 0 0 0 0.2rem rgba(0, 51, 153, 0.25);
        }
        .table th {
            background: #003399 !important;
            color: white !important;
            border-color: #003399;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 51, 153, 0.05);
        }
        .subject-checkbox:checked {
            background-color: #003399;
            border-color: #003399;
        }
        .btn-outline-primary {
            border-color: #003399;
            color: #003399;
        }
        .btn-outline-primary:hover {
            background-color: #003399;
            border-color: #003399;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="ncst-header text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <img src="../../images/ncst-logo.png" alt="NCST Logo" style="height: 60px; margin-right: 1.5rem;">
                        <h1 class="mb-0">Manual Subject Enrollment</h1>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <!-- Student Information -->
                        <div class="student-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3"><i class="bi bi-person-circle"></i> Student Information</h5>
                                    <p class="mb-1"><strong>Full Name:</strong> <?php echo htmlspecialchars($student['first_name'] ?? '') . ' ' . htmlspecialchars($student['last_name'] ?? ''); ?></p>
                                    <p class="mb-1"><strong>Student Type:</strong> <?php echo htmlspecialchars($student['student_type'] ?? $student['admission_type'] ?? 'New'); ?></p>
                                    <p class="mb-1"><strong>Program/Course:</strong> <?php echo htmlspecialchars($student['course'] ?? $student['track'] ?? 'N/A'); ?></p>
                                    <p class="mb-1"><strong>Year Level:</strong> <?php echo $year_level; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="mb-3"><i class="bi bi-info-circle"></i> Academic Details</h5>
                                    <p class="mb-1"><strong>Semester:</strong> <?php echo $semester; ?></p>
                                    <p class="mb-1"><strong>Academic Year:</strong> <?php echo $academic_year; ?></p>
                                    <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($student['email'] ?? 'N/A'); ?></p>
                                    <p class="mb-0"><strong>Status:</strong> <span class="badge bg-warning">Pending Enrollment</span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Subject Offerings and Enrollment -->
                        <div class="row">
                            <!-- Subject Offerings Table -->
                            <div class="col-lg-8">
                                <div class="card ncst-card">
                                    <div class="card-body p-4">
                                        <h4 class="card-title mb-4">
                                            <i class="bi bi-book"></i> Subject Offerings
                                        </h4>
                                        
                                        <?php if (isset($error)): ?>
                                            <div class="alert alert-danger"><?php echo $error; ?></div>
                                        <?php endif; ?>

                                        <!-- Filters -->
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Filter by Section:</label>
                                                <select class="form-select" id="sectionFilter">
                                                    <option value="">All Sections</option>
                                                    <?php 
                                                    $sections = array_unique(array_column($subject_offerings, 'section'));
                                                    foreach ($sections as $section): 
                                                    ?>
                                                        <option value="<?php echo $section; ?>"><?php echo $section; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Filter by Schedule:</label>
                                                <select class="form-select" id="scheduleFilter">
                                                    <option value="">All Schedules</option>
                                                    <option value="MWF">MWF</option>
                                                    <option value="TTH">TTH</option>
                                                    <option value="M">Monday</option>
                                                    <option value="T">Tuesday</option>
                                                    <option value="W">Wednesday</option>
                                                    <option value="TH">Thursday</option>
                                                    <option value="F">Friday</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Show Only:</label>
                                                <select class="form-select" id="availabilityFilter">
                                                    <option value="">All Subjects</option>
                                                    <option value="available">Available Slots Only</option>
                                                    <option value="full">Full Classes</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Subject Offerings Table -->
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped" id="subjectOfferingsTable">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th width="8%">Subject Code</th>
                                                        <th width="25%">Subject Name</th>
                                                        <th width="8%">Units</th>
                                                        <th width="12%">Section</th>
                                                        <th width="12%">Schedule</th>
                                                        <th width="12%">Room</th>
                                                        <th width="15%">Instructor</th>
                                                        <th width="8%">Slots</th>
                                                        <th width="10%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($subject_offerings as $subject): ?>
                                                        <tr data-section="<?php echo $subject['section']; ?>" 
                                                            data-schedule="<?php echo $subject['schedule']; ?>"
                                                            data-available="<?php echo $subject['available']; ?>">
                                                            <td><strong><?php echo $subject['code']; ?></strong></td>
                                                            <td><?php echo $subject['name']; ?></td>
                                                            <td><?php echo $subject['units']; ?></td>
                                                            <td><span class="badge bg-info"><?php echo $subject['section']; ?></span></td>
                                                            <td><?php echo $subject['schedule']; ?></td>
                                                            <td><?php echo $subject['room']; ?></td>
                                                            <td><?php echo $subject['instructor']; ?></td>
                                                            <td>
                                                                <span class="badge <?php echo $subject['available'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                                                    <?php echo $subject['enrolled']; ?>/<?php echo $subject['slots']; ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <?php if ($subject['available'] > 0): ?>
                                                                    <button type="button" class="btn btn-sm btn-outline-primary enroll-btn" 
                                                                            data-subject='<?php echo json_encode($subject); ?>'>
                                                                        <i class="bi bi-plus-circle"></i> Enroll
                                                                    </button>
                                                                <?php else: ?>
                                                                    <button type="button" class="btn btn-sm btn-secondary" disabled>
                                                                        <i class="bi bi-x-circle"></i> Full
                                                                    </button>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Enrolled Subjects Panel -->
                            <div class="col-lg-4">
                                <div class="card ncst-card">
                                    <div class="card-body p-4">
                                        <h4 class="card-title mb-4">
                                            <i class="bi bi-check-circle"></i> Enrolled Subjects
                                        </h4>
                                        
                                        <form method="POST" id="enrollmentForm">
                                            <div id="enrolledSubjectsList">
                                                <div class="text-center text-muted">
                                                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                                    <p class="mt-2">No subjects enrolled yet</p>
                                                    <small>Select subjects from the offerings table</small>
                                                </div>
                                            </div>

                                            <!-- Enrollment Summary -->
                                            <div class="mt-4" id="enrollmentSummary" style="display: none;">
                                                <div class="alert alert-info">
                                                    <h6 class="mb-2"><i class="bi bi-info-circle"></i> Enrollment Summary</h6>
                                                    <p class="mb-1"><strong>Total Subjects:</strong> <span id="totalSubjects">0</span></p>
                                                    <p class="mb-1"><strong>Total Units:</strong> <span id="totalUnits">0</span></p>
                                                    <p class="mb-0"><strong>Status:</strong> <span id="enrollmentStatus" class="badge bg-warning">Pending</span></p>
                                                </div>
                                            </div>

                                            <!-- Enrollment Notes -->
                                            <div class="mb-4">
                                                <label for="enrollment_notes" class="form-label fw-bold">Enrollment Notes (Optional):</label>
                                                <textarea class="form-control" id="enrollment_notes" name="enrollment_notes" rows="3" 
                                                          placeholder="Add any special notes or requirements..."></textarea>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="d-grid gap-2">
                                                <button type="submit" class="btn btn-ncst" id="finalizeBtn" disabled>
                                                    <i class="bi bi-check-circle"></i> Finalize Enrollment
                                                </button>
                                                <a href="enrollment_page.php" class="btn btn-secondary">
                                                    <i class="bi bi-arrow-left"></i> Back to Enrollment
                                                </a>
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let enrolledSubjects = [];
            let scheduleConflicts = [];

            // Enroll button click
            $('.enroll-btn').click(function() {
                const subjectData = $(this).data('subject');
                
                // Check for schedule conflicts
                const hasConflict = checkScheduleConflict(subjectData);
                
                if (hasConflict) {
                    alert('Schedule conflict detected! This subject conflicts with an already enrolled subject.');
                    return;
                }
                
                // Add to enrolled subjects
                enrolledSubjects.push(subjectData);
                
                // Update UI
                updateEnrolledSubjectsList();
                updateEnrollmentSummary();
                updateEnrollButtons();
                
                // Change button state
                $(this).removeClass('btn-outline-primary').addClass('btn-success')
                       .html('<i class="bi bi-check-circle-fill"></i> Enrolled')
                       .prop('disabled', true);
            });

            // Check for schedule conflicts
            function checkScheduleConflict(newSubject) {
                for (let subject of enrolledSubjects) {
                    if (subject.schedule === newSubject.schedule) {
                        return true;
                    }
                }
                return false;
            }

            // Update enrolled subjects list
            function updateEnrolledSubjectsList() {
                const container = $('#enrolledSubjectsList');
                
                if (enrolledSubjects.length === 0) {
                    container.html(`
                        <div class="text-center text-muted">
                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                            <p class="mt-2">No subjects enrolled yet</p>
                            <small>Select subjects from the offerings table</small>
                        </div>
                    `);
                } else {
                    let html = '<div class="list-group">';
                    
                    enrolledSubjects.forEach((subject, index) => {
                        html += `
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">${subject.name}</h6>
                                    <small class="text-muted">
                                        ${subject.code} | ${subject.units} units | ${subject.section}<br>
                                        ${subject.schedule} | ${subject.room} | ${subject.instructor}
                                    </small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-subject" data-index="${index}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        `;
                    });
                    
                    html += '</div>';
                    container.html(html);
                }
            }

            // Remove enrolled subject
            $(document).on('click', '.remove-subject', function() {
                const index = $(this).data('index');
                const removedSubject = enrolledSubjects[index];
                
                // Remove from array
                enrolledSubjects.splice(index, 1);
                
                // Update UI
                updateEnrolledSubjectsList();
                updateEnrollmentSummary();
                updateEnrollButtons();
                
                // Reset button state
                $(`.enroll-btn[data-subject*="${removedSubject.code}"]`)
                    .removeClass('btn-success').addClass('btn-outline-primary')
                    .html('<i class="bi bi-plus-circle"></i> Enroll')
                    .prop('disabled', false);
            });

            // Update enrollment summary
            function updateEnrollmentSummary() {
                const totalSubjects = enrolledSubjects.length;
                const totalUnits = enrolledSubjects.reduce((sum, subject) => sum + subject.units, 0);
                
                $('#totalSubjects').text(totalSubjects);
                $('#totalUnits').text(totalUnits);
                
                let status = 'Pending';
                let statusClass = 'bg-warning';
                
                if (totalUnits >= 12 && totalUnits <= 24) {
                    status = 'Valid';
                    statusClass = 'bg-success';
                    $('#finalizeBtn').prop('disabled', false);
                } else if (totalUnits < 12) {
                    status = 'Insufficient Units';
                    statusClass = 'bg-danger';
                    $('#finalizeBtn').prop('disabled', true);
                } else {
                    status = 'Too Many Units';
                    statusClass = 'bg-danger';
                    $('#finalizeBtn').prop('disabled', true);
                }
                
                $('#enrollmentStatus').text(status).removeClass().addClass('badge ' + statusClass);
                
                if (totalSubjects > 0) {
                    $('#enrollmentSummary').show();
                } else {
                    $('#enrollmentSummary').hide();
                }
            }

            // Update enroll buttons based on conflicts
            function updateEnrollButtons() {
                $('.enroll-btn').each(function() {
                    const subjectData = $(this).data('subject');
                    const isEnrolled = enrolledSubjects.some(subject => subject.code === subjectData.code);
                    const hasConflict = checkScheduleConflict(subjectData);
                    
                    if (isEnrolled) {
                        $(this).removeClass('btn-outline-primary').addClass('btn-success')
                               .html('<i class="bi bi-check-circle-fill"></i> Enrolled')
                               .prop('disabled', true);
                    } else if (hasConflict) {
                        $(this).removeClass('btn-outline-primary').addClass('btn-warning')
                               .html('<i class="bi bi-exclamation-triangle"></i> Conflict')
                               .prop('disabled', true);
                    } else {
                        $(this).removeClass('btn-success btn-warning').addClass('btn-outline-primary')
                               .html('<i class="bi bi-plus-circle"></i> Enroll')
                               .prop('disabled', false);
                    }
                });
            }

            // Filter functionality
            $('#sectionFilter, #scheduleFilter, #availabilityFilter').change(function() {
                const sectionFilter = $('#sectionFilter').val();
                const scheduleFilter = $('#scheduleFilter').val();
                const availabilityFilter = $('#availabilityFilter').val();
                
                $('#subjectOfferingsTable tbody tr').each(function() {
                    const section = $(this).data('section');
                    const schedule = $(this).data('schedule');
                    const available = $(this).data('available');
                    
                    let show = true;
                    
                    if (sectionFilter && section !== sectionFilter) show = false;
                    if (scheduleFilter && !schedule.includes(scheduleFilter)) show = false;
                    if (availabilityFilter === 'available' && available <= 0) show = false;
                    if (availabilityFilter === 'full' && available > 0) show = false;
                    
                    $(this).toggle(show);
                });
            });

            // Form submission
            $('#enrollmentForm').submit(function(e) {
                if (enrolledSubjects.length === 0) {
                    e.preventDefault();
                    alert('Please enroll at least one subject before proceeding.');
                    return false;
                }
                
                // Add enrolled subjects to form
                enrolledSubjects.forEach((subject, index) => {
                    $(this).append(`<input type="hidden" name="enrolled_subjects[${index}][code]" value="${subject.code}">`);
                    $(this).append(`<input type="hidden" name="enrolled_subjects[${index}][name]" value="${subject.name}">`);
                    $(this).append(`<input type="hidden" name="enrolled_subjects[${index}][units]" value="${subject.units}">`);
                    $(this).append(`<input type="hidden" name="enrolled_subjects[${index}][section]" value="${subject.section}">`);
                    $(this).append(`<input type="hidden" name="enrolled_subjects[${index}][schedule]" value="${subject.schedule}">`);
                    $(this).append(`<input type="hidden" name="enrolled_subjects[${index}][room]" value="${subject.room}">`);
                    $(this).append(`<input type="hidden" name="enrolled_subjects[${index}][instructor]" value="${subject.instructor}">`);
                });
            });
        });
    </script>
</body>
</html> 