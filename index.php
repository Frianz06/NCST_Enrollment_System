<?php include 'backend/config.php'?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCST Enrollment System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="faviconn.ico" type="image/x-icon">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background:#003399; box-shadow:0 2px 12px rgba(0,51,153,0.08);">
      <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="#">
          <img src="images/ncst-logo.png" alt="NCST Logo" width="40" height="40" class="me-2">
          NCST Enrollment System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#about">About NCST</a></li>
            <li class="nav-item"><a class="nav-link" href="#programs">Programs Offered</a></li>
            <li class="nav-item"><a class="nav-link" href="#contact">Contact Us</a></li>
            <li class="nav-item"><a class="nav-link" href="#faq">FAQ / Help</a></li>
            <li class="nav-item d-lg-none mt-2">
          </ul>
        </div>
      </div>
    </nav>
    <section class="landing-full-bg d-flex align-items-center justify-content-center min-vh-100 position-relative">
        <div class="landing-overlay"></div>
        <div class="landing-content position-absolute top-50 start-50 translate-middle w-100 px-3">
            <div class="hero-card text-center">
                <img src="images/ncst-logo.png" alt="NCST Logo" class="mb-4" style="max-width: 120px; background: rgba(255,255,255,0.85); border-radius: 1rem; padding: 0.5rem;">
                <div class="landing-title">
                    <h4 class="text-primary text-uppercase mb-2" style="letter-spacing:2px;">Welcome to</h4>
                    <h1 class="display-2 fw-bold text-dark text-uppercase mb-0" style="line-height:1.1; text-shadow:2px 2px 8px rgba(0,0,0,0.08);">National College of<br>Science & Technology</h1>
                    <div class="fw-semibold fst-italic text-warning mb-3" style="font-size:1.25rem; letter-spacing:1px;">Ang batang magaling, sa NCST galing!</div>
                </div>
                <p class="school-desc text-secondary my-4" style="max-width:600px; margin-left:auto; margin-right:auto;">
                    The National College of Science and Technology (NCST) is one of the leading educational institutions in the vast growing locality of Dasmariñas, Cavite.
                </p>
                <div class="row g-2">
                  <div class="col-12 col-md-6">
                    <a href="student_login.php" class="btn enroll-btn w-100 mb-2 mb-md-0">
                      <i class="bi bi-pencil-square me-2"></i>Student Portal
                    </a>
                  </div>
                  <div class="col-12 col-md-6">
                    <a href="admission.php" class="btn admission-btn w-100">
                      <i class="bi bi-person-plus me-2"></i>Admission Portal
                    </a>
                  </div>
                </div>
            </div>
        </div>
    </section>
    <!-- News & Announcements Section -->
    <section class="container my-5">
      <h2 class="text-primary fw-bold mb-4" style="letter-spacing:1px;"><i class="bi bi-megaphone-fill me-2"></i>News & Announcements</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
              <h5 class="card-title text-warning fw-bold"><i class="bi bi-calendar-event me-2"></i>Upcoming Enrollment</h5>
              <p class="card-text mb-2">Enrollment for SY 2024-2025 starts <strong>June 10, 2024</strong>! Don’t miss the early registration period.</p>
              <span class="badge bg-primary">Open Soon</span>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
              <h5 class="card-title text-warning fw-bold"><i class="bi bi-clipboard-check me-2"></i>Requirements Reminder</h5>
              <p class="card-text mb-2">Prepare your Form 138, Good Moral Certificate, PSA Birth Certificate, and other documents ahead of time.</p>
              <span class="badge bg-warning text-dark">Reminder</span>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
              <h5 class="card-title text-warning fw-bold"><i class="bi bi-geo-alt me-2"></i>Campus Tour Dates</h5>
              <p class="card-text mb-2">Join our guided campus tours every <strong>Saturday, 9AM</strong>. Register at the Admissions Office or online.</p>
              <span class="badge bg-success">Open to All</span>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Footer -->
    <footer class="bg-primary text-white pt-4 pb-2 mt-0">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
            <img src="images/ncst-logo.png" alt="NCST Logo" width="48" class="mb-2">
            <div><strong>National College of Science and Technology</strong></div>
            <div class="small">Gen. Mariano Alvarez, Cavite, Philippines</div>
          </div>
          <div class="col-md-4 text-center mb-3 mb-md-0">
            <div class="mb-1"><i class="bi bi-envelope me-2"></i><a href="mailto:admissions@ncst.edu.ph" class="footer-link">admissions@ncst.edu.ph</a></div>
            <div class="mb-1"><i class="bi bi-telephone me-2"></i>(046) 416-6278</div>
            <div class="footer-social mt-2">
              <a href="#"><i class="bi bi-facebook"></i></a>
              <a href="#"><i class="bi bi-twitter"></i></a>
              <a href="#"><i class="bi bi-instagram"></i></a>
            </div>
          </div>
          <div class="col-md-4 text-center text-md-end">
            <a href="#" class="footer-link d-block mb-1">Privacy Policy</a>
            <span class="small">&copy; 2024 NCST. All rights reserved.</span>
          </div>
        </div>
      </div>
    </footer>
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 