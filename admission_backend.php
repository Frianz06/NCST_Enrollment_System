<?php include 'backend/config.php'?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    
    $desired_course = $_POST['desired_course'];

    // Personal Information
    $last_name = $_POST['last_name']; 
    $first_name = $_POST['first_name']; 
    $middle_name = $_POST['middle_name'];
    $suffix = $_POST['suffix'];
    $complete_address = $_POST['complete_address'];
    $zip_code = $_POST['zip_code'];
    $region = $_POST['region'];
    $province = $_POST['province'];
    $municipality = $_POST['municipality'];
    $barangay = $_POST['barangay'];
    $landline = $_POST['landline'];
    $mobile_no = $_POST['mobile_no'];
    $gender = $_POST['gender'];
    $civil_status = $_POST['civil_status'];
    $nationality = $_POST['nationality'];
    $date_of_birth = $_POST['date_of_birth'];
    $place_of_birth = $_POST['place_of_birth'];
    $email_address = $_POST['email_address'];
    $religion = $_POST['religion'];

    // Education Information
    $primary_school = $_POST['primary_school'];
    $primary_year_graduated = $_POST['primary_year_graduated'];
    $secondary_school = $_POST['secondary_school'];
    $secondary_year_graduated = $_POST['secondary_year_graduated'];
    $tertiary_school = $_POST['tertiary_school'];
    $tertiary_year_graduated = $_POST['tertiary_year_graduated'];
    $tertiary_course = $_POST['tertiary_course'];
    $educational_plan = $_POST['educational_plan'];
    $academic_achievement = $_POST['academic_achievement'];

    // Optional Checkboxes
    $working = isset($_POST['working']) ? 1 : 0;
    $work_shift = isset($_POST['work_shift']) ? 1 : 0;
    $ncst_student = isset($_POST['ncst_student']) ? 1 : 0;
    $ncst_employee = isset($_POST['ncst_employee']) ? 1 : 0;
    $transferee = isset($_POST['transferee']) ? 1 : 0;
    $als_graduate = isset($_POST['als_graduate']) ? 1 : 0;
    $returnee = isset($_POST['returnee']) ? 1 : 0;
    $dts_student = isset($_POST['dts_student']) ? 1 : 0;
    $cross_enrollee = isset($_POST['cross_enrollee']) ? 1 : 0;
    $foreign_student = isset($_POST['foreign_student']) ? 1 : 0;
    $father_deceased = isset($_POST['father_deceased']) ? 1 : 0;
    $mother_deceased = isset($_POST['mother_deceased']) ? 1 : 0;

    $employer = $_POST['employer'];
    $work_position = $_POST['work_position'];
    $number_of_siblings = $_POST['number_of_siblings'];
    $ncst_relationship = $_POST['ncst_relationship'];
    $how_know_ncst = $_POST['how_know_ncst'];

    // Parent / Guardian Information
    $father_family_name = $_POST['father_family_name'];
    $father_given_name = $_POST['father_given_name'];
    $father_middle_name = $_POST['father_middle_name'];
    $father_address = $_POST['father_address'];
    $father_landline = $_POST['father_landline'];
    $father_mobile = $_POST['father_mobile'];
    $father_occupation = $_POST['father_occupation'];

    $mother_family_name = $_POST['mother_family_name'];
    $mother_given_name = $_POST['mother_given_name'];
    $mother_middle_name = $_POST['mother_middle_name'];
    $mother_maiden_family_name = $_POST['mother_maiden_family_name'];
    $mother_maiden_given_name = $_POST['mother_maiden_given_name'];
    $mother_maiden_middle_name = $_POST['mother_maiden_middle_name'];
    $mother_address = $_POST['mother_address'];
    $mother_landline = $_POST['mother_landline'];
    $mother_mobile = $_POST['mother_mobile'];
    $mother_occupation = $_POST['mother_occupation'];

    $guardian_family_name = $_POST['guardian_family_name'];
    $guardian_given_name = $_POST['guardian_given_name'];
    $guardian_middle_name = $_POST['guardian_middle_name'];
    $guardian_address = $_POST['guardian_address'];
    $guardian_landline = $_POST['guardian_landline'];
    $guardian_mobile = $_POST['guardian_mobile'];
    $guardian_occupation = $_POST['guardian_occupation'];
    $guardian_relationship = $_POST['guardian_relationship'];

    $required_fields = [
        'desired_course', 'last_name', 'first_name', 'complete_address',
        'mobile_no', 'gender', 'civil_status', 'date_of_birth',
        'email_address', 'father_family_name', 'father_given_name',
        'mother_family_name', 'mother_given_name'
    ];

    $missing_fields = [];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
            $missing_fields[] = $field;
        }
    }

    if (!empty($missing_fields)) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Incomplete Submission',
                html: 'Please fill out all required fields before submitting.',
                confirmButtonColor: '#d33'
            });
        </script>";
        return;
    } 
    
        // Prepare the SQL statement
    $stmt = $db->prepare("INSERT INTO college_admission (
        desired_course,
        last_name, first_name, middle_name, suffix,
        complete_address, zip_code, region, province, municipality, barangay,
        landline, mobile_no, gender, civil_status, nationality,
        date_of_birth, place_of_birth, email_address, religion,

        primary_school, primary_year_graduated,
        secondary_school, secondary_year_graduated,
        tertiary_school, tertiary_year_graduated, tertiary_course,
        educational_plan, academic_achievement,

        working, employer, work_shift, work_position,

        ncst_student, number_of_siblings, ncst_employee, ncst_relationship,
        how_know_ncst,

        transferee, als_graduate, returnee, dts_student, cross_enrollee, foreign_student,

        father_family_name, father_given_name, father_middle_name,
        father_deceased, father_address, father_landline, father_mobile, father_occupation,

        mother_family_name, mother_given_name, mother_middle_name,
        mother_deceased, mother_maiden_family_name, mother_maiden_given_name,
        mother_maiden_middle_name, mother_address, mother_landline,
        mother_mobile, mother_occupation,

        guardian_family_name, guardian_given_name, guardian_middle_name,
        guardian_address, guardian_landline, guardian_mobile,
        guardian_occupation, guardian_relationship
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?, 
        ?, ?, ?, ?, ?, ?, ?, ?,
        ?, ?, ?,
        ?, ?, ?, ?, ?, ?, ?, ?, ?, 
        ?, ?, ?,
        ?, ?, ?, ?, ?
    )");

    // Convert date properly
    $date_of_birth = date('Y-m-d', strtotime($date_of_birth));

    // Bind the parameters
    $stmt->bind_param(
        "sssssssssssssssssssssssssssssiisiiiiiiiiiiiiiiiiiiiiiiiiiiiiissssssssssssssss",
        $desired_course,
        $last_name, $first_name, $middle_name, $suffix,
        $complete_address, $zip_code, $region, $province, $municipality, $barangay,
        $landline, $mobile_no, $gender, $civil_status, $nationality,
        $date_of_birth, $place_of_birth, $email_address, $religion,

        $primary_school, $primary_year_graduated,
        $secondary_school, $secondary_year_graduated,
        $tertiary_school, $tertiary_year_graduated, $tertiary_course,
        $educational_plan, $academic_achievement,

        $working, $employer, $work_shift, $work_position,

        $ncst_student, $number_of_siblings, $ncst_employee, $ncst_relationship,
        $how_know_ncst,

        $transferee, $als_graduate, $returnee, $dts_student, $cross_enrollee, $foreign_student,

        $father_family_name, $father_given_name, $father_middle_name,
        $father_deceased, $father_address, $father_landline, $father_mobile, $father_occupation,

        $mother_family_name, $mother_given_name, $mother_middle_name,
        $mother_deceased, $mother_maiden_family_name, $mother_maiden_given_name,
        $mother_maiden_middle_name, $mother_address, $mother_landline,
        $mother_mobile, $mother_occupation,

        $guardian_family_name, $guardian_given_name, $guardian_middle_name,
        $guardian_address, $guardian_landline, $guardian_mobile,
        $guardian_occupation, $guardian_relationship
    );

    // Execute and give feedback
    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Your application has been submitted!',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                window.location.href = 'success_page.php'; // change if needed
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Database Error',
                html: 'Something went wrong while saving your data. <br>Error: " . $stmt->error . "',
                confirmButtonColor: '#d33'
            });
        </script>";
    }

    $stmt->close();
    $db->close();
}
?>
