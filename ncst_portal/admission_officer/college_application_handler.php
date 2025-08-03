<?php
session_start();
require_once '../../db.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in and has admission officer role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admission') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Debug: Log POST data
error_log("College application POST data received: " . print_r($_POST, true));

function naIfEmptyOrSelect($val) {
    return (empty($val) || $val === '-- Select --') ? null : $val;
}

// Define required fields for College applications
$required_fields = [
    'course', 'last_name', 'first_name', 'dob', 'gender', 'civil_status', 
    'region', 'province', 'city', 'barangay', 'address', 'zip_code', 
    'mobile', 'email', 'student_type'
];

// Additional required fields based on student type
$additional_required_fields = [];
if (isset($_POST['student_type'])) {
    if ($_POST['student_type'] === 'New') {
        $additional_required_fields = ['elementary_school', 'elementary_year_grad', 'high_school', 'high_year_grad'];
    } elseif ($_POST['student_type'] === 'Transferee') {
        $additional_required_fields = ['elementary_school', 'elementary_year_grad', 'high_school', 'high_year_grad', 'tertiary_school'];
    }
}

// Combine all required fields
$all_required_fields = array_merge($required_fields, $additional_required_fields);

// Validate required fields
$missing = [];
foreach ($all_required_fields as $field) {
    if (!isset($_POST[$field]) || trim($_POST[$field]) === '' || $_POST[$field] === '-- Select --') {
        $missing[] = $field;
    }
}

// Check if guardian information is required
$guardian_required = false;
if ((empty($_POST['father_family_name']) && empty($_POST['mother_family_name'])) || 
    (isset($_POST['guardian_required']) && $_POST['guardian_required'] == '1')) {
    $guardian_required = true;
    $guardian_fields = ['guardian_family_name', 'guardian_given_name', 'guardian_relationship', 
                       'guardian_address', 'guardian_mobile', 'guardian_occupation'];
    foreach ($guardian_fields as $gfield) {
        if (!isset($_POST[$gfield]) || trim($_POST[$gfield]) === '') {
            $missing[] = $gfield;
        }
    }
}

if (count($missing) > 0) {
    echo json_encode([
        'success' => false, 
        'message' => 'Please fill in all required fields: ' . implode(', ', $missing)
    ]);
    exit;
}

try {
    // List of all columns in the student_applications table that can be filled by the form
    $columns = [
        'name', 'email', 'course_or_track', 'gender', 'civil_status', 'type', 'admission_type', 'status', 
        'nationality', 'religion', 'region', 'province', 'city', 'barangay',
        'last_name', 'first_name', 'middle_name', 'suffix', 'address', 'zip_code', 'mobile', 'landline', 
        'dob', 'pob', 'dialect',
        'elementary_school', 'elementary_year_grad', 'high_school', 'high_year_grad', 'grade10_section',
        'father_family_name', 'father_given_name', 'father_middle_name', 'father_deceased', 
        'father_address', 'father_mobile', 'father_landline', 'father_occupation',
        'mother_family_name', 'mother_given_name', 'mother_middle_name', 'mother_deceased', 
        'mother_maiden_family_name', 'mother_maiden_given_name', 'mother_maiden_middle_name', 
        'mother_address', 'mother_mobile', 'mother_landline', 'mother_occupation',
        'guardian_family_name', 'guardian_given_name', 'guardian_middle_name', 'guardian_relationship', 
        'guardian_address', 'guardian_mobile', 'guardian_landline', 'guardian_occupation',
        'requirements_status', 'student_type', 'tertiary_school', 'tertiary_year_grad', 'course_graduated', 
        'educational_plan', 'academic_achievement',
        'is_working', 'employer', 'work_in_shifts', 'work_position', 'family_connected_ncst', 

        'ncst_employee', 'ncst_relationship', 'no_of_siblings', 'how_did_you_know_ncst'
=======
        'ncst_relationship', 'no_of_siblings', 'how_did_you_know_ncst'

    ];
    
    // Compose name for legacy column
    $name = trim(($_POST['last_name'] ?? '') . ', ' . ($_POST['first_name'] ?? '') . ' ' . ($_POST['middle_name'] ?? '') . ' ' . ($_POST['suffix'] ?? ''));
    $_POST['name'] = $name;
    
    // Set course_or_track and type for college applications
    $_POST['course_or_track'] = $_POST['course'];
    $_POST['type'] = 'College';
    
    // Handle checkboxes and defaults

    $checkbox_fields = ['is_working', 'work_in_shifts', 'family_connected_ncst', 'ncst_employee', 'father_deceased', 'mother_deceased'];
=======
    $checkbox_fields = ['is_working', 'work_in_shifts', 'family_connected_ncst', 'father_deceased', 'mother_deceased'];

    foreach ($checkbox_fields as $cb) {
        if (!isset($_POST[$cb])) {
            $_POST[$cb] = 0;
        }
    }
    
    // Set default values for optional fields

    $optional_fields_defaults = [
        'nationality' => 'Filipino',
        'religion' => 'Not Specified',
        'pob' => 'Not Specified',
        'dialect' => 'Not Specified',
        'grade10_section' => 'Not Applicable',
        'educational_plan' => 'Not Specified',
        'academic_achievement' => 'Not Specified',
        'no_of_siblings' => '0',
        'how_did_you_know_ncst' => 'Not Specified',
        'employer' => 'Not Specified',
        'work_position' => 'Not Specified',
        'ncst_relationship' => 'Not Specified',
        'tertiary_school' => 'Not Specified',
        'tertiary_year_grad' => 'Not Specified',
        'course_graduated' => 'Not Specified'
    ];
    
    foreach ($optional_fields_defaults as $field => $default_value) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $_POST[$field] = $default_value;
        }
=======
    if (!isset($_POST['nationality']) || empty($_POST['nationality'])) {
        $_POST['nationality'] = 'Filipino';
    }
    if (!isset($_POST['religion']) || empty($_POST['religion'])) {
        $_POST['religion'] = 'Not Specified';
    }
    if (!isset($_POST['pob']) || empty($_POST['pob'])) {
        $_POST['pob'] = 'Not Specified';
    }
    if (!isset($_POST['grade10_section']) || empty($_POST['grade10_section'])) {
        $_POST['grade10_section'] = 'Not Applicable';
    }
    if (!isset($_POST['educational_plan']) || empty($_POST['educational_plan'])) {
        $_POST['educational_plan'] = 'Not Specified';
    }
    if (!isset($_POST['no_of_siblings']) || empty($_POST['no_of_siblings'])) {
        $_POST['no_of_siblings'] = 0;
    }
    if (!isset($_POST['how_did_you_know_ncst']) || empty($_POST['how_did_you_know_ncst'])) {
        $_POST['how_did_you_know_ncst'] = 'Not Specified';
    }
    if (!isset($_POST['academic_achievement']) || empty($_POST['academic_achievement'])) {
        $_POST['academic_achievement'] = 'Not Specified';
    }
    if (!isset($_POST['is_working']) || empty($_POST['is_working'])) {
        $_POST['is_working'] = 'Not Specified';
    }
    if (!isset($_POST['employer']) || empty($_POST['employer'])) {
        $_POST['employer'] = 'Not Specified';
    }
    if (!isset($_POST['work_position']) || empty($_POST['work_position'])) {
        $_POST['work_position'] = 'Not Specified';
    }
    if (!isset($_POST['work_in_shifts']) || empty($_POST['work_in_shifts'])) {
        $_POST['work_in_shifts'] = 'Not Specified';
    }
    if (!isset($_POST['family_connected_ncst']) || empty($_POST['family_connected_ncst'])) {
        $_POST['family_connected_ncst'] = 'Not Specified';
    }
    if (!isset($_POST['ncst_relationship']) || empty($_POST['ncst_relationship'])) {
        $_POST['ncst_relationship'] = 'Not Specified';
    }
    if (!isset($_POST['tertiary_school']) || empty($_POST['tertiary_school'])) {
        $_POST['tertiary_school'] = 'Not Specified';
    }
    if (!isset($_POST['tertiary_year_grad']) || empty($_POST['tertiary_year_grad'])) {
        $_POST['tertiary_year_grad'] = 'Not Specified';
    }
    if (!isset($_POST['course_graduated']) || empty($_POST['course_graduated'])) {
        $_POST['course_graduated'] = 'Not Specified';

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
    

=======
    // Add submitted by information
    $db_fields[] = 'admission_type';
    $placeholders[] = '?';
    $values[] = 'College';
    

    $sql = "INSERT INTO student_applications (" . implode(",", $db_fields) . ") VALUES (" . implode(",", $placeholders) . ")";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database prepare error: ' . $conn->error);
    }
    
    $stmt->bind_param(str_repeat('s', count($values)), ...$values);
    
    if ($stmt->execute()) {
        $new_applicant_id = $conn->insert_id;
        $tracking_number = 'NCST-' . date('Y') . '-' . str_pad($new_applicant_id, 5, '0', STR_PAD_LEFT);
        
        // Update the record with tracking number
        $update_stmt = $conn->prepare("UPDATE student_applications SET tracking_number = ? WHERE id = ?");
        $update_stmt->bind_param('si', $tracking_number, $new_applicant_id);
        $update_stmt->execute();
        $update_stmt->close();
        
        // Log success
        error_log("College application submitted successfully with tracking number: " . $tracking_number . " by admission officer: " . $_SESSION['username']);
        
        echo json_encode([
            'success' => true,
            'message' => 'College application submitted successfully!',
            'tracking_number' => $tracking_number,
            'applicant_id' => $new_applicant_id
        ]);
        
    } else {
        throw new Exception('Database execution error: ' . $stmt->error);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    error_log("College application submission error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'There was an error submitting the application. Please try again.'
    ]);
}

$conn->close();
?>