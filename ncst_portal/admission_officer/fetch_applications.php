<?php
require_once '../../db.php';

$status = isset($_GET['status']) ? $_GET['status'] : 'all';

// Query to get applications with their corresponding student records
$sql = "SELECT sa.*, s.student_id 
        FROM student_applications sa 
        LEFT JOIN students s ON sa.id = s.application_id 
        ORDER BY sa.date_submitted DESC";

$res = $conn->query($sql);
$count = 0;

while ($row = $res->fetch_assoc()) {
    $count++;
            $fullName = $row['last_name'] . ', ' . $row['first_name'] . ' ' . ($row['middle_name'] ?? '');
    $type = isset($row['type']) && !empty($row['type']) ? $row['type'] : (stripos($row['course_or_track'], 'SHS') !== false ? 'Senior High' : 'College');
    
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['tracking_number'] ?? '') . '</td>';
    echo '<td>' . htmlspecialchars($row['student_id'] ?? 'N/A') . '</td>';
    echo '<td>' . htmlspecialchars($fullName) . '</td>';
    echo '<td>' . htmlspecialchars($row['course_or_track']) . '</td>';
    echo '<td>' . htmlspecialchars($row['email']) . '</td>';
    echo '<td><span class="badge bg-success">Approved</span></td>';
    echo '<td>' . htmlspecialchars(date('M d, Y', strtotime($row['date_submitted']))) . '</td>';
    echo '<td>';
    echo '<button class="btn btn-info btn-sm" onclick="showApplicationDetails(' . $row['id'] . ')">View Details</button>';
    echo '</td>';
    echo '</tr>';
}

if ($count === 0) {
    echo '<tr><td colspan="8" class="text-center"><div class="empty-state">';
    echo '<i class="bi bi-inbox"></i><h5>No Applications Found</h5><p>No applications have been submitted yet.</p>';
    echo '</div></td></tr>';
}

$conn->close();
?> 