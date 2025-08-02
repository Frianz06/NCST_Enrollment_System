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
    $selected_section = $_POST['section'] ?? '';
    $enrollment_notes = $_POST['enrollment_notes'] ?? '';
    
    if ($selected_section) {
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
        
        // Update student record
        $update_stmt = $conn->prepare("UPDATE student_applications SET status = 'enrolled', student_id = ?, section = ?, enrollment_notes = ?, enrollment_type = 'section' WHERE id = ?");
        $update_stmt->bind_param('sssi', $student_id_generated, $selected_section, $enrollment_notes, $student_id);
        
        if ($update_stmt->execute()) {
            $_SESSION['enrollment_success'] = "Student enrolled successfully with block section! Student ID: " . $student_id_generated;
            header('Location: print_enrollment.php?student_id=' . $student_id);
            exit();
        } else {
            $error = "Error enrolling student: " . $update_stmt->error;
        }
    } else {
        $error = "Please select a section.";
    }
}

// Get available sections with detailed information
$course = $student['course'] ?? $student['track'] ?? '';
$level = $student['level'] ?? '';

$sections_data = [];
if ($level === 'College' || stripos($course, 'BS') !== false) {
    if (stripos($course, 'BSIT') !== false) {
        $sections_data = [
            [
                'section' => 'BSIT-1A',
                'capacity' => 30,
                'enrolled' => 25,
                'available' => 5,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Computer Lab 1',
                'adviser' => 'Prof. Santos',
                'subjects' => ['Programming 1', 'Computer Fundamentals', 'Mathematics for IT', 'English 1', 'Filipino 1', 'PE 1', 'NSTP 1']
            ],
            [
                'section' => 'BSIT-1B',
                'capacity' => 30,
                'enrolled' => 28,
                'available' => 2,
                'schedule' => 'MWF 1:00-5:00, TTH 8:00-12:00',
                'room' => 'Computer Lab 2',
                'adviser' => 'Prof. Garcia',
                'subjects' => ['Programming 1', 'Computer Fundamentals', 'Mathematics for IT', 'English 1', 'Filipino 1', 'PE 1', 'NSTP 1']
            ],
            [
                'section' => 'BSIT-1C',
                'capacity' => 30,
                'enrolled' => 20,
                'available' => 10,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Computer Lab 3',
                'adviser' => 'Prof. Lopez',
                'subjects' => ['Programming 2', 'Database Management', 'Web Development', 'English 2', 'Filipino 2', 'PE 2', 'NSTP 2']
            ],
            [
                'section' => 'BSIT-1D',
                'capacity' => 30,
                'enrolled' => 15,
                'available' => 15,
                'schedule' => 'MWF 1:00-5:00, TTH 8:00-12:00',
                'room' => 'Computer Lab 4',
                'adviser' => 'Prof. Martinez',
                'subjects' => ['Programming 2', 'Database Management', 'Web Development', 'English 2', 'Filipino 2', 'PE 2', 'NSTP 2']
            ]
        ];
    } elseif (stripos($course, 'BSIS') !== false) {
        $sections_data = [
            [
                'section' => 'BSIS-1A',
                'capacity' => 25,
                'enrolled' => 20,
                'available' => 5,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Computer Lab 5',
                'adviser' => 'Prof. Cruz',
                'subjects' => ['Information Systems', 'Business Analytics', 'Database Systems', 'English 1', 'Filipino 1', 'PE 1', 'NSTP 1']
            ],
            [
                'section' => 'BSIS-1B',
                'capacity' => 25,
                'enrolled' => 18,
                'available' => 7,
                'schedule' => 'MWF 1:00-5:00, TTH 8:00-12:00',
                'room' => 'Computer Lab 6',
                'adviser' => 'Prof. Reyes',
                'subjects' => ['Information Systems', 'Business Analytics', 'Database Systems', 'English 1', 'Filipino 1', 'PE 1', 'NSTP 1']
            ],
            [
                'section' => 'BSIS-1C',
                'capacity' => 25,
                'enrolled' => 12,
                'available' => 13,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Computer Lab 7',
                'adviser' => 'Prof. Santos',
                'subjects' => ['Systems Analysis', 'Business Intelligence', 'IT Project Management', 'English 2', 'Filipino 2', 'PE 2', 'NSTP 2']
            ]
        ];
    } elseif (stripos($course, 'BSCS') !== false) {
        $sections_data = [
            [
                'section' => 'BSCS-1A',
                'capacity' => 25,
                'enrolled' => 22,
                'available' => 3,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Computer Lab 8',
                'adviser' => 'Prof. Garcia',
                'subjects' => ['Computer Science Fundamentals', 'Programming Logic', 'Discrete Mathematics', 'English 1', 'Filipino 1', 'PE 1', 'NSTP 1']
            ],
            [
                'section' => 'BSCS-1B',
                'capacity' => 25,
                'enrolled' => 19,
                'available' => 6,
                'schedule' => 'MWF 1:00-5:00, TTH 8:00-12:00',
                'room' => 'Computer Lab 9',
                'adviser' => 'Prof. Lopez',
                'subjects' => ['Computer Science Fundamentals', 'Programming Logic', 'Discrete Mathematics', 'English 1', 'Filipino 1', 'PE 1', 'NSTP 1']
            ],
            [
                'section' => 'BSCS-1C',
                'capacity' => 25,
                'enrolled' => 15,
                'available' => 10,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Computer Lab 10',
                'adviser' => 'Prof. Martinez',
                'subjects' => ['Data Structures', 'Algorithms', 'Software Engineering', 'English 2', 'Filipino 2', 'PE 2', 'NSTP 2']
            ]
        ];
    } else {
        $sections_data = [
            [
                'section' => 'Section A',
                'capacity' => 30,
                'enrolled' => 25,
                'available' => 5,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 101',
                'adviser' => 'Prof. Cruz',
                'subjects' => ['English 1', 'Filipino 1', 'Mathematics 1', 'Science 1', 'Social Studies 1', 'PE 1', 'NSTP 1']
            ],
            [
                'section' => 'Section B',
                'capacity' => 30,
                'enrolled' => 28,
                'available' => 2,
                'schedule' => 'MWF 1:00-5:00, TTH 8:00-12:00',
                'room' => 'Room 102',
                'adviser' => 'Prof. Reyes',
                'subjects' => ['English 1', 'Filipino 1', 'Mathematics 1', 'Science 1', 'Social Studies 1', 'PE 1', 'NSTP 1']
            ],
            [
                'section' => 'Section C',
                'capacity' => 30,
                'enrolled' => 20,
                'available' => 10,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 103',
                'adviser' => 'Prof. Santos',
                'subjects' => ['English 2', 'Filipino 2', 'Mathematics 2', 'Science 2', 'Social Studies 2', 'PE 2', 'NSTP 2']
            ],
            [
                'section' => 'Section D',
                'capacity' => 30,
                'enrolled' => 15,
                'available' => 15,
                'schedule' => 'MWF 1:00-5:00, TTH 8:00-12:00',
                'room' => 'Room 104',
                'adviser' => 'Prof. Garcia',
                'subjects' => ['English 2', 'Filipino 2', 'Mathematics 2', 'Science 2', 'Social Studies 2', 'PE 2', 'NSTP 2']
            ]
        ];
    }
} else { // Senior High
    if (stripos($course, 'STEM') !== false) {
        $sections_data = [
            [
                'section' => 'STEM 11-1',
                'capacity' => 25,
                'enrolled' => 20,
                'available' => 5,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 201',
                'adviser' => 'Prof. Cruz',
                'subjects' => ['General Mathematics', 'Pre-Calculus', 'General Biology 1', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'STEM 11-2',
                'capacity' => 25,
                'enrolled' => 18,
                'available' => 7,
                'schedule' => 'MWF 1:00-5:00, TTH 8:00-12:00',
                'room' => 'Room 202',
                'adviser' => 'Prof. Reyes',
                'subjects' => ['General Mathematics', 'Pre-Calculus', 'General Chemistry 1', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'STEM 11-3',
                'capacity' => 25,
                'enrolled' => 15,
                'available' => 10,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 203',
                'adviser' => 'Prof. Santos',
                'subjects' => ['General Mathematics', 'Pre-Calculus', 'General Physics 1', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'STEM 12-1',
                'capacity' => 25,
                'enrolled' => 22,
                'available' => 3,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 204',
                'adviser' => 'Prof. Garcia',
                'subjects' => ['Basic Calculus', 'General Biology 2', 'Practical Research 1', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'STEM 12-2',
                'capacity' => 25,
                'enrolled' => 19,
                'available' => 6,
                'schedule' => 'MWF 1:00-5:00, TTH 8:00-12:00',
                'room' => 'Room 205',
                'adviser' => 'Prof. Lopez',
                'subjects' => ['Basic Calculus', 'General Chemistry 2', 'Practical Research 1', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'STEM 12-3',
                'capacity' => 25,
                'enrolled' => 16,
                'available' => 9,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 206',
                'adviser' => 'Prof. Martinez',
                'subjects' => ['Basic Calculus', 'General Physics 2', 'Practical Research 1', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ]
        ];
    } elseif (stripos($course, 'ABM') !== false) {
        $sections_data = [
            [
                'section' => 'ABM 11-1',
                'capacity' => 25,
                'enrolled' => 20,
                'available' => 5,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 301',
                'adviser' => 'Prof. Cruz',
                'subjects' => ['Fundamentals of Accountancy', 'Business Mathematics', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'ABM 11-2',
                'capacity' => 25,
                'enrolled' => 18,
                'available' => 7,
                'schedule' => 'MWF 1:00-5:00, TTH 8:00-12:00',
                'room' => 'Room 302',
                'adviser' => 'Prof. Reyes',
                'subjects' => ['Fundamentals of Accountancy', 'Business Mathematics', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'ABM 11-3',
                'capacity' => 25,
                'enrolled' => 15,
                'available' => 10,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 303',
                'adviser' => 'Prof. Santos',
                'subjects' => ['Fundamentals of Accountancy', 'Business Mathematics', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'ABM 12-1',
                'capacity' => 25,
                'enrolled' => 22,
                'available' => 3,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 304',
                'adviser' => 'Prof. Garcia',
                'subjects' => ['Business Finance', 'Principles of Marketing', 'Applied Economics', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'ABM 12-2',
                'capacity' => 25,
                'enrolled' => 19,
                'available' => 6,
                'schedule' => 'MWF 1:00-5:00, TTH 8:00-12:00',
                'room' => 'Room 305',
                'adviser' => 'Prof. Lopez',
                'subjects' => ['Business Finance', 'Principles of Marketing', 'Applied Economics', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'ABM 12-3',
                'capacity' => 25,
                'enrolled' => 16,
                'available' => 9,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 306',
                'adviser' => 'Prof. Martinez',
                'subjects' => ['Business Finance', 'Principles of Marketing', 'Applied Economics', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ]
        ];
    } elseif (stripos($course, 'HUMSS') !== false) {
        $sections_data = [
            [
                'section' => 'HUMSS 11-1',
                'capacity' => 25,
                'enrolled' => 20,
                'available' => 5,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 401',
                'adviser' => 'Prof. Cruz',
                'subjects' => ['Introduction to World Religions', 'Creative Writing', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'HUMSS 11-2',
                'capacity' => 25,
                'enrolled' => 18,
                'available' => 7,
                'schedule' => 'MWF 1:00-5:00, TTH 8:00-12:00',
                'room' => 'Room 402',
                'adviser' => 'Prof. Reyes',
                'subjects' => ['Introduction to World Religions', 'Creative Writing', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'HUMSS 11-3',
                'capacity' => 25,
                'enrolled' => 15,
                'available' => 10,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 403',
                'adviser' => 'Prof. Santos',
                'subjects' => ['Introduction to World Religions', 'Creative Writing', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'HUMSS 12-1',
                'capacity' => 25,
                'enrolled' => 22,
                'available' => 3,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 404',
                'adviser' => 'Prof. Garcia',
                'subjects' => ['Creative Nonfiction', 'Philippine Politics and Governance', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'HUMSS 12-2',
                'capacity' => 25,
                'enrolled' => 19,
                'available' => 6,
                'schedule' => 'MWF 1:00-5:00, TTH 8:00-12:00',
                'room' => 'Room 405',
                'adviser' => 'Prof. Lopez',
                'subjects' => ['Creative Nonfiction', 'Philippine Politics and Governance', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'HUMSS 12-3',
                'capacity' => 25,
                'enrolled' => 16,
                'available' => 9,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 406',
                'adviser' => 'Prof. Martinez',
                'subjects' => ['Creative Nonfiction', 'Philippine Politics and Governance', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ]
        ];
    } elseif (stripos($course, 'GAS') !== false) {
        $sections_data = [
            [
                'section' => 'GAS 11-1',
                'capacity' => 25,
                'enrolled' => 20,
                'available' => 5,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 501',
                'adviser' => 'Prof. Cruz',
                'subjects' => ['Humanities 1', 'Social Sciences 1', 'Natural Sciences 1', 'Mathematics', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'GAS 11-2',
                'capacity' => 25,
                'enrolled' => 18,
                'available' => 7,
                'schedule' => 'MWF 1:00-5:00, TTH 8:00-12:00',
                'room' => 'Room 502',
                'adviser' => 'Prof. Reyes',
                'subjects' => ['Humanities 1', 'Social Sciences 1', 'Natural Sciences 1', 'Mathematics', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'GAS 11-3',
                'capacity' => 25,
                'enrolled' => 15,
                'available' => 10,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 503',
                'adviser' => 'Prof. Santos',
                'subjects' => ['Humanities 1', 'Social Sciences 1', 'Natural Sciences 1', 'Mathematics', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'GAS 12-1',
                'capacity' => 25,
                'enrolled' => 22,
                'available' => 3,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 504',
                'adviser' => 'Prof. Garcia',
                'subjects' => ['Practical Research 1', 'Contemporary Philippine Arts', 'Media and Information Literacy', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'GAS 12-2',
                'capacity' => 25,
                'enrolled' => 19,
                'available' => 6,
                'schedule' => 'MWF 1:00-5:00, TTH 8:00-12:00',
                'room' => 'Room 505',
                'adviser' => 'Prof. Lopez',
                'subjects' => ['Practical Research 1', 'Contemporary Philippine Arts', 'Media and Information Literacy', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ],
            [
                'section' => 'GAS 12-3',
                'capacity' => 25,
                'enrolled' => 16,
                'available' => 9,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 506',
                'adviser' => 'Prof. Martinez',
                'subjects' => ['Practical Research 1', 'Contemporary Philippine Arts', 'Media and Information Literacy', 'English for Academic Purposes', 'Filipino sa Piling Larang', 'PE and Health']
            ]
        ];
    } else {
        $sections_data = [
            [
                'section' => 'Section A',
                'capacity' => 30,
                'enrolled' => 25,
                'available' => 5,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 101',
                'adviser' => 'Prof. Cruz',
                'subjects' => ['English 1', 'Filipino 1', 'Mathematics 1', 'Science 1', 'Social Studies 1', 'PE 1', 'NSTP 1']
            ],
            [
                'section' => 'Section B',
                'capacity' => 30,
                'enrolled' => 28,
                'available' => 2,
                'schedule' => 'MWF 1:00-5:00, TTH 8:00-12:00',
                'room' => 'Room 102',
                'adviser' => 'Prof. Reyes',
                'subjects' => ['English 1', 'Filipino 1', 'Mathematics 1', 'Science 1', 'Social Studies 1', 'PE 1', 'NSTP 1']
            ],
            [
                'section' => 'Section C',
                'capacity' => 30,
                'enrolled' => 20,
                'available' => 10,
                'schedule' => 'MWF 8:00-12:00, TTH 1:00-5:00',
                'room' => 'Room 103',
                'adviser' => 'Prof. Santos',
                'subjects' => ['English 2', 'Filipino 2', 'Mathematics 2', 'Science 2', 'Social Studies 2', 'PE 2', 'NSTP 2']
            ]
        ];
    }
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
    $semester = ''; // No semester for Senior High
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Section-Based Enrollment | NCST</title>
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
        .section-card {
            border: 2px solid #e9ecef;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
            text-align: center;
        }
        .section-card:hover {
            border-color: #003399;
            background: rgba(0,51,153,0.05);
            transform: translateY(-2px);
        }
        .section-card.selected {
            border-color: #003399;
            background: rgba(0,51,153,0.1);
        }
        .form-check-input:checked {
            background-color: #003399;
            border-color: #003399;
        }
        .form-select-lg {
            font-size: 1.1rem;
            padding: 0.75rem 1rem;
        }
        .form-select:focus {
            border-color: #003399;
            box-shadow: 0 0 0 0.2rem rgba(0, 51, 153, 0.25);
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
                        <h1 class="mb-0">Section-Based Enrollment</h1>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8">
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
                                    <?php if (!empty($semester)): ?>
                                        <p class="mb-1"><strong>Semester:</strong> <?php echo $semester; ?></p>
                                    <?php endif; ?>
                                    <p class="mb-1"><strong>Academic Year:</strong> <?php echo $academic_year; ?></p>
                                    <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($student['email'] ?? 'N/A'); ?></p>
                                    <p class="mb-0"><strong>Status:</strong> <span class="badge bg-warning">Pending Section Enrollment</span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Section Offerings and Enrollment -->
                        <div class="row">
                            <!-- Section Offerings Table -->
                            <div class="col-lg-12">
                                <div class="card ncst-card">
                                    <div class="card-body p-4">
                                        <h4 class="card-title mb-4">
                                            <i class="bi bi-people"></i> Section Offerings for <?php echo htmlspecialchars($student['course'] ?? $student['track'] ?? 'Student'); ?>
                                        </h4>
                                        
                                        <?php if (isset($error)): ?>
                                            <div class="alert alert-danger"><?php echo $error; ?></div>
                                        <?php endif; ?>

                                        <!-- Section Dropdown -->
                                        <div class="mb-4">
                                            <label for="sectionSelect" class="form-label fw-bold">Select Your Section:</label>
                                            <select class="form-select form-select-lg" id="sectionSelect" name="section">
                                                <option value="">Select a section...</option>
                                                <?php foreach ($sections_data as $section): ?>
                                                    <option value="<?php echo $section['section']; ?>" 
                                                            data-section='<?php echo json_encode($section); ?>'
                                                            <?php echo $section['available'] <= 0 ? 'disabled' : ''; ?>>
                                                        <?php echo $section['section']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="form-text">
                                                <i class="bi bi-info-circle"></i> 
                                                Only sections with available slots are shown. Full sections are disabled.
                                            </div>
                                        </div>

                                        <!-- Section Details (Hidden by default) -->
                                        <div id="sectionDetails" style="display: none;">
                                            <div class="alert alert-info">
                                                <h6 class="mb-3"><i class="bi bi-info-circle"></i> Section Information</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>Section:</strong> <span id="selectedSectionName">-</span></p>
                                                        <p class="mb-1"><strong>Schedule:</strong> <span id="selectedSectionSchedule">-</span></p>
                                                        <p class="mb-1"><strong>Room:</strong> <span id="selectedSectionRoom">-</span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>Adviser:</strong> <span id="selectedSectionAdviser">-</span></p>
                                                        <p class="mb-1"><strong>Capacity:</strong> <span id="selectedSectionCapacity">-</span></p>
                                                        <p class="mb-0"><strong>Available Slots:</strong> <span id="selectedSectionAvailable">-</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Subjects List (Hidden by default) -->
                                        <div id="subjectsList" style="display: none;">
                                            <h6 class="fw-bold mb-3">
                                                <i class="bi bi-book"></i> Subjects in this Section:
                                            </h6>
                                            <div id="subjectsContainer">
                                                <!-- Subjects will be loaded here -->
                                            </div>
                                        </div>

                                        <!-- Enroll Section Button (Hidden by default) -->
                                        <div id="enrollSectionBtn" style="display: none;" class="mt-4">
                                            <div class="d-grid">
                                                <button type="button" class="btn btn-success btn-lg" id="enrollSectionButton">
                                                    <i class="bi bi-check-circle"></i> Enroll Section
                                                </button>
                                            </div>
                                            <div class="text-center mt-2">
                                                <small class="text-muted">
                                                    <i class="bi bi-info-circle"></i> 
                                                    This will enroll the student in ALL subjects for this section
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Back Button -->
                                        <div class="text-center mt-4">
                                            <a href="enrollment_page.php" class="btn btn-secondary">
                                                <i class="bi bi-arrow-left"></i> Back to Enrollment
                                            </a>
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
            let selectedSection = null;

            // Section dropdown change
            $('#sectionSelect').change(function() {
                const selectedOption = $(this).find('option:selected');
                const sectionData = selectedOption.data('section');
                
                if (sectionData) {
                    // Set selected section
                    selectedSection = sectionData;
                    
                    // Update UI
                    updateSectionDetails();
                    loadSubjectsList();
                    showEnrollButton();
                } else {
                    // Clear selection
                    selectedSection = null;
                    hideSectionDetails();
                    hideEnrollButton();
                }
            });

            // Update section details
            function updateSectionDetails() {
                if (selectedSection) {
                    // Update section details
                    $('#selectedSectionName').text(selectedSection.section);
                    $('#selectedSectionSchedule').text(selectedSection.schedule);
                    $('#selectedSectionRoom').text(selectedSection.room);
                    $('#selectedSectionAdviser').text(selectedSection.adviser);
                    $('#selectedSectionCapacity').text(selectedSection.capacity);
                    $('#selectedSectionAvailable').text(selectedSection.available);
                    
                    // Show section details
                    $('#sectionDetails').show();
                    $('#subjectsList').show();
                }
            }

            // Load subjects list
            function loadSubjectsList() {
                if (selectedSection && selectedSection.subjects) {
                    let html = '<div class="list-group">';
                    
                    selectedSection.subjects.forEach((subject, index) => {
                        html += `
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">${subject}</h6>
                                        <small class="text-muted">Subject ${index + 1}</small>
                                    </div>
                                    <span class="badge bg-primary">3 units</span>
                                </div>
                            </div>
                        `;
                    });
                    
                    html += '</div>';
                    $('#subjectsContainer').html(html);
                }
            }

            // Show enroll button
            function showEnrollButton() {
                $('#enrollSectionBtn').show();
            }

            // Hide section details
            function hideSectionDetails() {
                $('#sectionDetails').hide();
                $('#subjectsList').hide();
            }

            // Hide enroll button
            function hideEnrollButton() {
                $('#enrollSectionBtn').hide();
            }

            // Enroll section button click
            $('#enrollSectionButton').click(function() {
                if (!selectedSection) {
                    alert('Please select a section first.');
                    return;
                }

                // Show confirmation
                if (confirm(`Are you sure you want to enroll this student in ${selectedSection.section}?\n\nThis will enroll the student in ALL subjects for this section.`)) {
                    // Submit the form
                    submitEnrollment();
                }
            });

            // Submit enrollment
            function submitEnrollment() {
                // Create form data
                const formData = new FormData();
                formData.append('section', selectedSection.section);
                formData.append('enrollment_notes', ''); // Empty notes since we removed the field
                
                // Show loading state
                const button = $('#enrollSectionButton');
                const originalText = button.html();
                button.html('<i class="bi bi-hourglass-split"></i> Enrolling...');
                button.prop('disabled', true);

                // Submit via AJAX
                $.ajax({
                    url: window.location.href,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Redirect to printable certificate on success
                        window.location.href = 'print_enrollment.php?student_id=<?php echo $student_id; ?>';
                    },
                    error: function() {
                        alert('Error enrolling student. Please try again.');
                        button.html(originalText);
                        button.prop('disabled', false);
                    }
                });
            }

            // Form submission (fallback)
            $('#enrollmentForm').submit(function(e) {
                if (!selectedSection) {
                    e.preventDefault();
                    alert('Please select a section before proceeding.');
                    return false;
                }
                
                // Add selected section to form
                $(this).append(`<input type="hidden" name="section" value="${selectedSection.section}">`);
            });
        });
    </script>
</body>
</html> 