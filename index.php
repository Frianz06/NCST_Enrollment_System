<?php
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCST Enrollment System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="faviconn.ico" type="image/x-icon">
    <style>
/* --- Landing Page Styles (keep for custom layout/branding) --- */
body, html {
    height: 100%;
    margin: 0;
    padding: 0;
    font-family: 'Poppins', 'Roboto', Arial, sans-serif;
    background: #f4f7fa;
}
.landing {
    background: linear-gradient(120deg, #003399 60%, #ffcd00 100%);
    min-height: 100vh;
    padding-top: 60px;
    padding-bottom: 60px;
}
.landing h1, .landing p {
    color: #fff;
    text-shadow: 1px 1px 8px rgba(0,0,0,0.2);
}
.landing .btn-warning {
    font-weight: bold;
    font-size: 1.2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.landing-full-bg {
    position: relative;
    min-height: 100vh;
    width: 100%;
    overflow: hidden;
    background: url('images/ncst-campus.jpg') center center/cover no-repeat;
}
.landing-bg {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: url('images/ncst-campus.jpg') center center/cover no-repeat;
    z-index: 0;
}
.landing-overlay {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0, 51, 153, 0.75); /* slightly darker for more contrast */
    z-index: 1;
}
.landing-content {
    z-index: 2;
}
.landing-title {
    opacity: 0;
    transform: translateY(60px);
    animation: slideFadeIn 1.2s cubic-bezier(0.23, 1, 0.32, 1) 0.2s forwards;
}
@keyframes slideFadeIn {
    from {
        opacity: 0;
        transform: translateY(60px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.landing-content h1, .landing-content h4, .school-desc {
    text-shadow: 2px 2px 8px rgba(0,0,0,0.4);
}
.enroll-btn {
    font-size: 1.5rem;
    border-radius: 2rem;
    letter-spacing: 1px;
    transition: transform 0.15s, box-shadow 0.15s, background 0.15s, color 0.15s;
    margin-bottom: 0.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.enroll-btn.btn-warning {
    background: #ffcd00;
    color: #003399;
    border: none;
}
.enroll-btn.btn-warning:hover, .enroll-btn.btn-warning:focus {
    transform: scale(1.07);
    box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    background: #ffb300;
    color: #003399;
}
.btn-outline-light.enroll-btn {
    border: 2px solid #ffcd00;
    color: #fff;
    background: transparent;
}
.btn-outline-light.enroll-btn:hover, .btn-outline-light.enroll-btn:focus {
    background: #ffcd00;
    color: #003399;
    border-color: #ffcd00;
}
.landing-content > *:not(:last-child) {
    margin-bottom: 1.2rem;
}
@media (max-width: 768px) {
    .landing-content h1 {
        font-size: 2rem;
    }
    .landing-content h4 {
        font-size: 1rem;
    }
    .landing-content img {
        max-width: 80px;
    }
    .school-desc {
        font-size: 0.95rem;
    }
} 

/* --- Bootstrap Color Overrides for Branding --- */
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

.hero-card {
  background: rgba(255,255,255,0.85);
  border-radius: 1.5rem;
  box-shadow: 0 8px 32px rgba(0,0,0,0.12);
  padding: 1rem 1.5rem;
  max-width: 600px;
  margin: auto;
}
.enroll-btn, .admission-btn {
  font-weight: 700;
  font-size: 1.15rem;
  border-radius: 0.7rem;
  padding: 0.85rem 0;
  transition: all 0.18s cubic-bezier(.4,0,.2,1);
  box-shadow: 0 2px 8px rgba(0,51,153,0.07);
  letter-spacing: 1px;
}
.enroll-btn {
  background: #ffc72c;
  color: #003399;
  border: none;
}
.enroll-btn:hover, .enroll-btn:focus {
  background: #003399;
  color: #fff;
  box-shadow: 0 4px 16px rgba(0,51,153,0.18);
}
.admission-btn {
  background: #003399;
  color: #fff;
  border: none;
}
.admission-btn:hover, .admission-btn:focus {
  background: #ffc72c;
  color: #003399;
  box-shadow: 0 4px 16px rgba(255,199,44,0.18);
}
.landing-title h1, .landing-title h4 {
  font-weight: 700;
}
.footer-link {
  color: #ffc72c;
  text-decoration: none;
  transition: color 0.15s;
}
.footer-link:hover {
  color: #fff;
  text-decoration: underline;
}
.footer-social a {
  color: #ffc72c;
  font-size: 1.3rem;
  margin: 0 0.5rem;
  transition: color 0.15s;
}
.footer-social a:hover {
  color: #fff;
}
@media (max-width: 576px) {
  .hero-card { padding: 0.7rem 0.3rem; }
  .display-2 { font-size: 2.2rem; }
} 
    </style>
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
                    <a href="student_login.php" class="d-block">
                      <button class="enroll-btn w-100 py-3">
                        <i class="bi bi-pencil-square me-2"></i> Student Portal
                      </button>
                    </a>
                  </div>
                  <div class="col-12 col-md-6">
                    <a href="student_applications.php" class="d-block">
                      <button class="admission-btn w-100 py-3">
                        <i class="bi bi-person-badge me-2"></i> Admission Portal
                      </button>
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
              <p class="card-text mb-2">Enrollment for SY 2025-2026 is still <strong>Ongoing!</strong>! Take the first step toward your future—become one of the NCST Builders.</p>
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
            <div class="small">Amafel Bldg., Aguinaldo Hi-way,, Dasmariñas, Philippines</div>
          </div>
          <div class="col-md-4 text-center mb-3 mb-md-0">
            <div class="mb-1"><i class="bi bi-envelope me-2"></i><a href="mailto:admissions@ncst.edu.ph" class="footer-link">publicrelations@ncst.edu.ph</a></div>
            <div class="mb-1"><i class="bi bi-telephone me-2"></i>0918 888 6278</div>
            <div class="footer-social mt-2">
              <a href="https://www.facebook.com/NCST.OfficialPage"><i class="bi bi-facebook"></i></a>
              <a href="https://x.com/NCSTOFFICIAL"><i class="bi bi-twitter"></i></a>
              <a href="https://www.instagram.com/ncstofficial"><i class="bi bi-instagram"></i></a>
            </div>
          </div>
          <div class="col-md-4 text-center text-md-end">
            <a href="#" class="footer-link d-block mb-1">Privacy Policy</a>
            <span class="small">&copy; 2024 NCST. All rights reserved.</span>
          </div>
        </div>
      </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 