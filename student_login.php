<?php
session_start();
require_once __DIR__ . '/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if ($student_id && $password) {
        // Query the students table for college students only
        $stmt = $conn->prepare('SELECT * FROM students WHERE student_id = ? AND student_type = "college" LIMIT 1');
        $stmt->bind_param('s', $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($student = $result->fetch_assoc()) {
            // Verify password (password is hashed surname)
            if (password_verify(strtolower($password), $student['password'])) {
                session_regenerate_id(true);
                $_SESSION['student_id'] = $student['student_id'];
                $_SESSION['student_name'] = $student['name'];
                $_SESSION['student_type'] = $student['student_type'];
                $_SESSION['student_course'] = $student['course'];
                $_SESSION['student_email'] = $student['email'];
                $_SESSION['program_id'] = $student['program_id'];
                header('Location: student_portal.php');
                exit;
            } else {
                $error = 'Incorrect password.';
            }
        } else {
            $error = 'Student not found.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="shortcut icon" href="faviconn.ico" type="image/x-icon">
  <style>
body, html {
    height: 100%;
    margin: 0;
    padding: 0;
    font-family: 'Poppins', 'Roboto', Arial, sans-serif;
    background: #f4f7fa;
}
.login-bg {
  background: linear-gradient(120deg, #003399 60%, #ffcd00 100%);
  min-height: 100vh;
}
.btn-primary, .btn-warning, .form-select, .form-control, .form-label, .fw-semibold, .text-primary {
  color: #003399 !important;
}
.btn-warning, .bg-warning {
  background-color: #ffcd00 !important;
  border-color: #ffcd00 !important;
  color: #003399 !important;
}
.btn-warning:hover, .btn-warning:focus {
  background-color: #ffb300 !important;
  border-color: #ffb300 !important;
  color: #003399 !important;
}
  </style>
</head>
<body class="login-bg d-flex align-items-center justify-content-center min-vh-100">
  <div class="card shadow p-4" style="max-width: 370px; width: 100%; border-radius: 12px;">
    <div class="text-center mb-3">
      <img src="images/ncst-logo.png" alt="School Logo" style="width: 80px;">
    </div>
    <h2 class="text-center mb-4" style="color: #003399;">Student Login</h2>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger py-2"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post" action="student_login.php" autocomplete="off">
      <div class="mb-3 text-start">
        <label for="student_id" class="form-label fw-semibold" style="color: #003399;">Student ID</label>
        <input type="text" id="student_id" name="student_id" class="form-control" placeholder="Enter your Student ID (e.g. 2025-00001)" required style="border-color: #003399; color: #003399;">
      </div>
      <div class="mb-3 text-start">
        <label for="password" class="form-label fw-semibold" style="color: #003399;">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your surname" required style="border-color: #003399; color: #003399;">
      </div>
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="agree" required>
        <label class="form-check-label" for="agree">
          I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">terms of service</a>
        </label>
      </div>
     <button type="submit" class="btn w-100 fw-bold mb-2" style="background: #ffcd00; color: #003399; border-radius: 5px; font-size: 1.1rem;">Login</button>
      <div class="text-end">
        <a href="#" class="small" style="color: #003399;">Forgot password?</a>
      </div>
      </form>
      <!-- Terms of Service Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="termsModalLabel">Terms of Service</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="font-size: 0.95rem;">
        Users agreed that No action shall be taken to impose unreasonable or disproportionately large load on the infrastructure of the Site or NCST's systems or networks, or any systems or networks connected to the Site or to NCST in general. You may not attempt to gain unauthorized access to any portion or feature of the Site, or any other systems or networks connected to the Site or to any NCST server, or to any of the services offered on or through the Site, by hacking, password "mining" or any other illegitimate means. Users may not use anyone else's login credential, password or account at any time without the express permission and consent of the holder of that login credential, password or account. NCST cannot and will not be liable for any loss or damage arising from your failure to comply with these obligations. Additionally, by using the Site, you acknowledge and agree that Internet transmissions are never completely private or secure. You understand that any message or information you send to the Site may be read or intercepted by others. NCST provides the use of this Site on an "as-is" basis without warranting any aspect of its Services. Therefore, Users are on notice that they access and use the Site at their own risk. Using NCST's Site and remote servers constitutes full agreement and understanding of this policy, NCST reserves the right to modify this policy without permission or consent of its users or recipients.
        </div>
  </div>
</body>
</html> 