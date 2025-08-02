<?php
$conn = new mysqli('localhost', 'root', '', 'enrollment_system');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?> 