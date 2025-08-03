<?php
require_once '../../db.php';

$status = isset($_GET['status']) ? $_GET['status'] : '';
$valid = ['new', 'pending', 'approved', 'rejected'];
if (!in_array($status, $valid)) {
    http_response_code(400);
    exit('Invalid status');
}

$requirements = [
    "Properly accomplished admission form",
    "Four (4) 2x2 recent, identical color pictures in white background with name tag",
    "Five (5) 1x1 recent, identical color pictures in white background with name tag",
    "Submit original and photocopied Form 138 / Report Card",
    "Submit original Good Moral Character certificate with dry seal and Photocopied",
    "If married, two (2) photocopies of marriage certificate duly signed by a priest / minister",
    "1pc. Long Brown Envelope"
];

$res = $conn->query("SELECT * FROM student_applications WHERE status='$status' ORDER BY date_submitted DESC");
$count = 0;
while ($row = $res->fetch_assoc()) {
    $count++;
    $fullName = $row['name'];
    $type = isset($row['type']) && !empty($row['type']) ? $row['type'] : (stripos($row['course_or_track'], 'SHS') !== false ? 'Senior High' : 'College');
    $savedChecklist = [];
    if (!empty($row['requirements_checklist'])) {
        $decoded = json_decode($row['requirements_checklist'], true);
        if (is_array($decoded)) $savedChecklist = $decoded;
    }
    $missing = array_filter($requirements, function($i) use ($savedChecklist) {
        return empty($savedChecklist[$i]);
    }, ARRAY_FILTER_USE_KEY);
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['tracking_number'] ?? '') . '</td>';
    echo '<td>' . htmlspecialchars($fullName) . '</td>';
    echo '<td>' . htmlspecialchars($type) . '</td>';
    echo '<td>' . htmlspecialchars($row['course_or_track']) . '</td>';
    // Status column
    if ($status === 'approved') {
        echo '<td><span class="badge bg-success">For Enrollment</span></td>';
    } elseif ($status === 'rejected') {
        echo '<td><span class="badge bg-danger">Rejected</span></td>';
    } else {
        echo '<td><span id="req-status-' . $row['id'] . '" class="badge ' . (empty($missing) ? 'bg-success' : 'bg-danger') . '">' . (empty($missing) ? 'Complete' : 'Incomplete') . '</span></td>';
    }
    // Action column
    echo '<td>';
    echo '<button class="btn btn-info btn-sm" onclick="showApplicantDetails(' . $row['id'] . ')">View</button>';
    if ($status === 'new' || $status === 'pending') {
        echo '<button class="btn btn-success btn-sm ms-1" onclick="approveApplicant(' . $row['id'] . ')">Approve</button>';
        echo '<button class="btn btn-danger btn-sm ms-1" onclick="rejectApplicant(' . $row['id'] . ')">Reject</button>';
        echo '<button class="btn btn-warning btn-sm ms-1" onclick="showChecklistModal(' . $row['id'] . ')">Checklist</button>';
    }
    echo '</td>';
    echo '</tr>';
    // Modals are not included here; they are static in dashboard.php
}
if ($count === 0) {
    $colspan = ($status === 'approved') ? 6 : 6;
    echo '<tr><td colspan="' . $colspan . '" class="text-center"><div class="empty-state">';
    if ($status === 'new' || $status === 'pending') {
        echo '<i class="bi bi-clock"></i><h5>No Pending Applications</h5><p>All applications have been processed.</p>';
    } elseif ($status === 'approved') {
        echo '<i class="bi bi-check-circle"></i><h5>No Approved Applications</h5><p>No applications have been approved yet.</p>';
    } elseif ($status === 'rejected') {
        echo '<i class="bi bi-x-circle"></i><h5>No Rejected Applications</h5><p>No applications have been rejected yet.</p>';
    }
    echo '</div></td></tr>';
}
$conn->close(); 