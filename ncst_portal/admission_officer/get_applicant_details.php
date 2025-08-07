<?php
require_once '../../db.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    echo '<div class="text-danger">Invalid applicant ID.</div>';
    exit;
}
$res = $conn->query("SELECT * FROM student_applications WHERE id=$id");
if ($row = $res->fetch_assoc()) {
    // Personal Information
    echo '<h6 class="fw-bold text-primary mb-2">Personal Information</h6>';
    echo '<ul class="list-group mb-3">';
    echo '<li class="list-group-item border-0 p-1">Full Name: ' . htmlspecialchars($row['last_name'] . ', ' . $row['first_name'] . ' ' . ($row['middle_name'] ?? '')) . '</li>';
    echo '<li class="list-group-item border-0 p-1">Birthday: ' . (!empty($row['dob']) ? htmlspecialchars($row['dob']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Gender: ' . (!empty($row['gender']) ? htmlspecialchars($row['gender']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Civil Status: ' . (!empty($row['civil_status']) ? htmlspecialchars($row['civil_status']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Nationality: ' . (!empty($row['nationality']) ? htmlspecialchars($row['nationality']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Religion: ' . (!empty($row['religion']) ? htmlspecialchars($row['religion']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Place of Birth: ' . (!empty($row['pob']) ? htmlspecialchars($row['pob']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Contact Number: ' . (!empty($row['mobile']) ? htmlspecialchars($row['mobile']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Email Address: ' . (!empty($row['email']) ? htmlspecialchars($row['email']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Address: ' . (!empty($row['address']) ? htmlspecialchars($row['address']) : 'Not Specified') . '</li>';
    $location = array_filter([$row['barangay'] ?? '', $row['city'] ?? '', $row['province'] ?? '']);
    echo '<li class="list-group-item border-0 p-1">Location: ' . (!empty($location) ? htmlspecialchars(implode(', ', $location)) : 'Not Specified') . '</li>';
    echo '</ul>';
    // Course Information
    echo '<h6 class="fw-bold text-primary mb-2">Course Information</h6>';
    echo '<ul class="list-group mb-3">';
    echo '<li class="list-group-item border-0 p-1">Preferred Course/Strand: ' . (!empty($row['course_or_track']) ? htmlspecialchars($row['course_or_track']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Student Type: ' . (!empty($row['student_type']) ? htmlspecialchars($row['student_type']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Type: ' . (!empty($row['type']) ? htmlspecialchars($row['type']) : 'Not Specified') . '</li>';
    echo '</ul>';
    // Educational Background
    echo '<h6 class="fw-bold text-primary mb-2">Educational Background</h6>';
    echo '<ul class="list-group mb-3">';
    echo '<li class="list-group-item border-0 p-1">Elementary School: ' . (!empty($row['elementary_school']) ? htmlspecialchars($row['elementary_school']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Elementary Year Graduated: ' . (!empty($row['elementary_year_grad']) ? htmlspecialchars($row['elementary_year_grad']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">High School: ' . (!empty($row['high_school']) ? htmlspecialchars($row['high_school']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">High School Year Graduated: ' . (!empty($row['high_year_grad']) ? htmlspecialchars($row['high_year_grad']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Grade 10 Section: ' . (!empty($row['grade10_section']) ? htmlspecialchars($row['grade10_section']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Tertiary School: ' . (!empty($row['tertiary_school']) ? htmlspecialchars($row['tertiary_school']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Tertiary Year Graduated: ' . (!empty($row['tertiary_year_grad']) ? htmlspecialchars($row['tertiary_year_grad']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Course Graduated: ' . (!empty($row['course_graduated']) ? htmlspecialchars($row['course_graduated']) : 'Not Specified') . '</li>';
    echo '</ul>';
    // Academic Performance
    echo '<h6 class="fw-bold text-primary mb-2">Academic Performance</h6>';
    echo '<ul class="list-group mb-3">';
    echo '<li class="list-group-item border-0 p-1">Academic Achievement: ' . (!empty($row['academic_achievement']) ? htmlspecialchars($row['academic_achievement']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Educational Plan: ' . (!empty($row['educational_plan']) ? htmlspecialchars($row['educational_plan']) : 'Not Specified') . '</li>';
    echo '</ul>';
    // Work Experience
    echo '<h6 class="fw-bold text-primary mb-2">Work Experience</h6>';
    echo '<ul class="list-group mb-3">';
    echo '<li class="list-group-item border-0 p-1">Is Working: ' . (!empty($row['is_working']) ? ($row['is_working'] == '1' ? 'Yes' : 'No') : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Employer: ' . (!empty($row['employer']) ? htmlspecialchars($row['employer']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Work Position: ' . (!empty($row['work_position']) ? htmlspecialchars($row['work_position']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Work in Shifts: ' . (!empty($row['work_in_shifts']) ? ($row['work_in_shifts'] == '1' ? 'Yes' : 'No') : 'Not Specified') . '</li>';
    echo '</ul>';
    // Family Background
    echo '<h6 class="fw-bold text-primary mb-2">Family Background</h6>';
    echo '<ul class="list-group mb-3">';
    echo '<li class="list-group-item border-0 p-1">Number of Siblings: ' . (!empty($row['no_of_siblings']) ? htmlspecialchars($row['no_of_siblings']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Family Connected to NCST: ' . (!empty($row['family_connected_ncst']) ? ($row['family_connected_ncst'] == '1' ? 'Yes' : 'No') : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">NCST Relationship: ' . (!empty($row['ncst_relationship']) ? htmlspecialchars($row['ncst_relationship']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">How Did You Know NCST: ' . (!empty($row['how_did_you_know_ncst']) ? htmlspecialchars($row['how_did_you_know_ncst']) : 'Not Specified') . '</li>';
    echo '</ul>';
    // Guardian Information
    echo '<h6 class="fw-bold text-primary mb-2">Guardian Information</h6>';
    echo '<ul class="list-group mb-3">';
    $guardianName = trim(($row['guardian_family_name'] ?? '') . ' ' . ($row['guardian_given_name'] ?? ''));
    echo '<li class="list-group-item border-0 p-1">Guardian Name: ' . (!empty($guardianName) ? htmlspecialchars($guardianName) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Guardian Contact: ' . (!empty($row['guardian_mobile']) ? htmlspecialchars($row['guardian_mobile']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Guardian Address: ' . (!empty($row['guardian_address']) ? htmlspecialchars($row['guardian_address']) : 'Not Specified') . '</li>';
    echo '<li class="list-group-item border-0 p-1">Guardian Occupation: ' . (!empty($row['guardian_occupation']) ? htmlspecialchars($row['guardian_occupation']) : 'Not Specified') . '</li>';
    echo '</ul>';
} else {
    echo '<div class="text-danger">Applicant not found.</div>';
}
$conn->close();
