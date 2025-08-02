<?php
require_once __DIR__ . '/db.php';

$count = 0;
$sql = "SELECT id, password FROM users";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $password = $row['password'];
        // Check if already hashed (password_hash always starts with $2y$ or $2a$ for bcrypt)
        if (!preg_match('/^\$2[ayb]\$/', $password)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $stmt->bind_param('si', $hashed, $id);
            $stmt->execute();
            $count++;
        }
    }
    echo "<h2>Migration complete.</h2><p>Updated $count user password(s) to hashed format.</p>";
} else {
    echo "<p>Failed to fetch users: " . htmlspecialchars($conn->error) . "</p>";
}
?> 