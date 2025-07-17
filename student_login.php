<?php include 'backend/config.php'?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Login</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="login-bg d-flex align-items-center justify-content-center min-vh-100">
  <div class="card shadow p-4" style="max-width: 370px; width: 100%; border-radius: 12px;">
    <div class="text-center mb-3">
      <img src="images/ncst-logo.png" alt="School Logo" style="width: 80px;">
    </div>
    <h2 class="text-center mb-4" style="color: #003399;">Student Login</h2>
    <form>
      <div class="mb-3 text-start">
        <label for="student_type" class="form-label fw-semibold" style="color: #003399;">Student Type</label>
        <select id="student_type" name="student_type" class="form-select" required style="border-color: #003399; color: #003399;">
          <option value="" disabled selected>Select type</option>
          <option value="senior_high">Senior High</option>
          <option value="college">College</option>
        </select>
      </div>
      <div class="mb-3 text-start">
        <label for="student_id" class="form-label fw-semibold" style="color: #003399;">Student ID</label>
        <input type="text" id="student_id" name="student_id" class="form-control" placeholder="Enter your Student ID" required style="border-color: #003399; color: #003399;">
      </div>
      <div class="mb-3 text-start">
        <label for="password" class="form-label fw-semibold" style="color: #003399;">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your Password" required style="border-color: #003399; color: #003399;">
      </div>
      <button type="submit" class="btn w-100 fw-bold mb-2" style="background: #ffcd00; color: #003399; border-radius: 5px; font-size: 1.1rem;">Login</button>
      <div class="text-end">
        <a href="#" class="small" style="color: #003399;">Forgot password?</a>
      </div>
    </form>
  </div>
</body>
</html> 