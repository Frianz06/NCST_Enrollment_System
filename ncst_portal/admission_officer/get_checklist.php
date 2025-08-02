<?php
require_once '../../db.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    echo '<div class="text-danger">Invalid applicant ID.</div>';
    exit;
}
$requirements = [
    "Properly accomplished admission form",
    "Four (4) 2x2 recent, identical color pictures in white background with name tag",
    "Five (5) 1x1 recent, identical color pictures in white background with name tag",
    "Submit original and photocopied Form 138 / Report Card",
    "Submit original Good Moral Character certificate with dry seal and Photocopied",
    // The marriage certificate will be handled separately
    "1pc. Long Brown Envelope"
];
$marriageReq = "If married, two (2) photocopies of marriage certificate duly signed by a priest / minister";
$res = $conn->query("SELECT requirements_checklist, civil_status FROM student_applications WHERE id=$id");
if ($row = $res->fetch_assoc()) {
    $savedChecklist = [];
    if (!empty($row['requirements_checklist'])) {
        $decoded = json_decode($row['requirements_checklist'], true);
        if (is_array($decoded)) $savedChecklist = $decoded;
    }
    $isMarried = (isset($row['civil_status']) && strtolower($row['civil_status']) === 'married');
    $marriedChecked = !empty($savedChecklist['married']) || $isMarried ? 'checked' : '';
    $marriageReqChecked = !empty($savedChecklist[$marriageReq]) ? 'checked' : '';
    $marriageReqDisabled = $marriedChecked ? '' : 'disabled';
    $marriageReqClass = $marriedChecked ? '' : 'text-secondary';
    echo '<form id="checklistForm">';
    foreach ($requirements as $req) {
        $checked = !empty($savedChecklist[$req]) ? 'checked' : '';
        echo '<div class="form-check mb-2">';
        echo '<input class="form-check-input" type="checkbox" data-req="' . htmlspecialchars($req) . '" id="req_' . md5($req) . '" ' . $checked . ' />';
        echo '<label class="form-check-label" for="req_' . md5($req) . '">' . htmlspecialchars($req) . '</label>';
        echo '</div>';
    }
    // Married? checkbox
    echo '<div class="form-check mb-2">';
    echo '<input class="form-check-input" type="checkbox" data-req="married" id="req_married" ' . $marriedChecked . ' />';
    echo '<label class="form-check-label" for="req_married">Married?</label>';
    echo '</div>';
    // Marriage certificate requirement
    echo '<div class="form-check mb-2">';
    echo '<input class="form-check-input" type="checkbox" data-req="' . htmlspecialchars($marriageReq) . '" id="req_' . md5($marriageReq) . '" ' . $marriageReqChecked . ' ' . $marriageReqDisabled . ' />';
    echo '<label class="form-check-label ' . $marriageReqClass . '" for="req_' . md5($marriageReq) . '" id="label_' . md5($marriageReq) . '">' . htmlspecialchars($marriageReq) . '</label>';
    echo '</div>';
    echo '</form>';
    // Add JS to handle enabling/disabling the marriage cert checkbox
    echo '<script>
    $(function() {
        function updateMarriageReq() {
            if($("#req_married").is(":checked")) {
                $("#req_' . md5($marriageReq) . '").prop("disabled", false);
                $("#label_' . md5($marriageReq) . '").removeClass("text-secondary");
            } else {
                $("#req_' . md5($marriageReq) . '").prop("disabled", true).prop("checked", false);
                $("#label_' . md5($marriageReq) . '").addClass("text-secondary");
            }
        }
        $("#req_married").on("change", updateMarriageReq);
        updateMarriageReq();
    });
    </script>';
} else {
    echo '<div class="text-danger">Applicant not found.</div>';
}
$conn->close(); 