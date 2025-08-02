<?php
session_start();
require_once __DIR__ . '/../db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username && $password) {
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($user = $result->fetch_assoc()) {
            // If passwords are hashed, use password_verify only
            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                // Redirect by role
                if ($user['role'] === 'admin') {
                    header('Location: admin/dashboard.php'); exit;
                } elseif ($user['role'] === 'admission') {
                    header('Location: admission_officer/dashboard.php'); exit;
                } elseif ($user['role'] === 'registration') {
                    header('Location: registration_officer/dashboard.php'); exit;
                } else {
                    $error = 'Unauthorized role.';
                }
            } else {
                $error = 'Incorrect password.';
            }
        } else {
            $error = 'User not found.';
        }
    } else {
        $error = 'Please enter both username and password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCST Portal Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
body {
    min-height: 100vh;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(120deg, #003399 60%, #FFD600 100%);
}
.ncst-logo {
    width: 80px;
    margin-bottom: 1rem;
}
    </style>
    <link rel="icon" type="image/x-icon" href="../faviconn.ico">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4" style="max-width: 370px; width: 100%; border-radius: 16px;">
            <div class="text-center">
                <img src="../images/ncst-logo.png" alt="NCST Logo" class="ncst-logo">
                <h2 class="fw-bold mb-4" style="color: #1a237e;">NCST Portal Login</h2>
            </div>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-2"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="post" action="ncst_login.php" autocomplete="off">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required autofocus placeholder="Enter your username">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="togglePassword" onclick="togglePasswordVisibility()">
                    <label class="form-check-label" for="togglePassword">Show Password</label>
                </div>
                <button type="submit" class="btn btn-warning w-100 fw-bold" style="color: #1a237e;">Login</button>
            </form>
        </div>
    </div>
    <script>
        function togglePasswordVisibility() {
            var pw = document.getElementById('password');
            pw.type = pw.type === 'password' ? 'text' : 'password';
        }
    </script>
    <!-- Bootstrap 5 JS (optional, for some components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 