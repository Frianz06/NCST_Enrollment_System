<?php
session_start();
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCST Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" type="image/x-icon" href="faviconn.ico">

    <style>
        body, html {
            height: 100%;
            font-family: 'Poppins', Arial, sans-serif;
            background: #f4f7fa;
        }
        /* Replace sidebar CSS to match admission dashboard */
        .sidebar {
            background: linear-gradient(180deg, #003399 0%, #0055cc 100%);
            min-height: 100vh;
            color: #fff;
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-top-right-radius: 40px;
            border-bottom-right-radius: 30px;
            box-shadow: 2px 0 8px rgba(0,0,0,0.07);
            z-index: 1051;
        }
        .sidebar .logo {
            width: 90px;
            margin-top: 32px;
            margin-bottom: 1.5rem;
        }
        .sidebar h5 {
            margin-bottom: 2rem;
            font-size: 1.4rem;
            font-weight: 600;
            text-align: center;
        }
        .sidebar .nav-link {
            color: #fff;
            font-weight: 500;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: background 0.15s, color 0.15s;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background: #FFD600;
            color: #003399;
        }
        .main-content {
            padding: 2.5rem 1.5rem;
        }
        .section-cards {
            margin-top: 1.5rem;
        }
        .section-cards .card {
            border-radius: 1.2rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            margin-bottom: 2rem;
        }
        .section-cards .card-title {
            font-weight: 700;
            letter-spacing: 1px;
        }
        @media (max-width: 991px) {
            .sidebar {
                border-radius: 0 0 30px 30px;
                min-height: auto;
            }
        }
        .custom-hamburger {
          display: flex;
          flex-direction: column;
          justify-content: center;
          align-items: center;
          height: 24px;
          width: 28px;
        }
        .custom-hamburger span {
          display: block;
          height: 3px;
          width: 70%;
          background: #fff;
          border-radius: 2px;
          margin: 4px auto;
          transition: all 0.3s;
        }
        .navbar-toggler-icon {
          background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255,255,255,0.95)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }
        /* NCST-themed modal */
        .modal-header.ncst-theme {
            background: #003399;
            color: #fff;
            border-bottom: 3px solid #FFD600;
        }
        .modal-footer .btn-ncst {
            background: #FFD600;
            color: #003399;
            font-weight: bold;
            border: none;
        }
        .modal-footer .btn-ncst:hover {
            background: #e6c200;
            color: #003399;
        }
        .modal-content {
            border-radius: 1.2rem;
        }
        .offcanvas-ncst {
            background: linear-gradient(180deg, #003399 0%, #0055cc 100%) !important;
            color: #fff !important;
            min-height: 100vh;
            padding-top: 2rem;
            border-top-right-radius: 30px;
            border-bottom-right-radius: 30px;
        }
        .offcanvas-ncst .logo {
            width: 60px;
            margin-bottom: 1rem;
        }
        .offcanvas-ncst .nav-link {
            color: #fff !important;
            font-weight: 500;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: background 0.15s, color 0.15s;
        }
        .offcanvas-ncst .nav-link.active, .offcanvas-ncst .nav-link:hover {
            background: #FFD600 !important;
            color: #003399 !important;
        }
        .offcanvas-ncst .offcanvas-title {
            color: #fff !important;
        }
        .offcanvas-ncst .btn-close {
            filter: invert(1) grayscale(1) brightness(2);
        }
        .hamburger-btn {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1051;
            background: #FFD600;
            border: none;
            border-radius: 16px;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            transition: background 0.2s;
        }
        .hamburger-btn .menu-icon {
            width: 28px;
            height: 28px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .hamburger-btn .menu-icon span {
            display: block;
            height: 4px;
            width: 100%;
            background: #fff;
            border-radius: 2px;
            margin: 3px 0;
            transition: 0.3s;
            position: relative;
        }
        .hamburger-btn.open .menu-icon span:nth-child(1) {
            transform: translateY(9px) rotate(45deg);
        }
        .hamburger-btn.open .menu-icon span:nth-child(2) {
            opacity: 0;
        }
        .hamburger-btn.open .menu-icon span:nth-child(3) {
            transform: translateY(-9px) rotate(-45deg);
        }
        @media (max-width: 991px) {
            .hamburger-btn {
                display: flex;
            }
        }
        .plain-navbar {
            position: relative;
            top: 0;
            left: 0;
            width: 100vw;
            height: 64px;
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            border-bottom-right-radius: 0;
            border-top-left-radius: 0;
        }
        @media (max-width: 767px) {
            .plain-navbar {
                height: 56px;
            }
            .sidebar {
                border-top-right-radius: 30px;
                border-bottom-right-radius: 20px;
            }
        }
        .main-content, .container-fluid {
            padding-top: 0 !important;
            /* Remove height and overflow so the whole page scrolls */
        }
        .is-invalid {
          border: 2.5px solid #dc3545 !important;
          background: #ffeaea !important;
          box-shadow: 0 0 0 2px #f8d7da;
        }
    </style>
</head>
<body>
    <div class="plain-navbar"></div>
    <!-- Hamburger Button for Mobile Only (top left, with more margin) -->
    <button class="hamburger-btn d-md-none position-fixed top-0 start-0 m-4 z-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
      <div class="menu-icon">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </button>
    <div class="container-fluid">
      <div class="row min-vh-100">
        <!-- Sidebar: visible only on md and up -->
        <div class="sidebar d-none d-md-flex flex-column align-items-center">
            <img src="images/ncst-logo.png" alt="NCST Logo" class="logo">
            <h5 class="mb-4">NCST Registration</h5>
            <ul class="nav flex-column w-100">
                <li class="nav-item mb-2">
                    <a class="nav-link active" id="nav-req" href="#" onclick="showSidebarSection('requirements')"><i class="bi bi-list-check"></i> Requirements</a>
                </li>
        </div>
        <!-- Offcanvas Sidebar for Mobile Only -->
        <div class="offcanvas offcanvas-start offcanvas-ncst d-md-none" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarMenuLabel">NCST Registration</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">
            <div class="text-center mb-4">
              <div class="logo d-inline-block">
                <img src="images/ncst-logo.png" alt="NCST Logo" style="max-width: 60px;">
              </div>
            </div>
            <ul class="nav flex-column">
              <li class="nav-item mb-2">
                <a class="nav-link active" id="nav-req-mobile" href="#" onclick="showSidebarSection('requirements'); var offcanvas = bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('sidebarMenu')); offcanvas.hide();"><i class="bi bi-list-check"></i> Requirements</a>
              </li>
              <li class="nav-item mb-2">
                <a class="nav-link" id="nav-shs-mobile" href="#" onclick="showSidebarSection('shs'); var offcanvas = bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('sidebarMenu')); offcanvas.hide();"><i class="bi bi-person-lines-fill"></i> Senior High School</a>
              </li>
              <li class="nav-item mb-2">
                <a class="nav-link" id="nav-college-mobile" href="#" onclick="showSidebarSection('college'); var offcanvas = bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('sidebarMenu')); offcanvas.hide();"><i class="bi bi-mortarboard"></i> College</a>
              </li>
            </ul>
          </div>
        </div>
        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 main-content">
                <!-- Requirements Section -->
                <div id="section-requirements">
                    <h2 class="fw-bold mb-4" style="color:#003399;">Enrollment Requirements</h2>
                    <div class="row section-cards">
                        <div class="col-md-4">
                            <div class="card border-warning h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-warning">Senior High School</h5>
                                    <ul class="mb-0">
                                        <li>F138/Report Card (Original & Photocopied)</li>
                                        <li>Good Moral Character (Original with Dry Seal & Photocopied)</li>
                                        <li>Moving up Certificate (Photocopy of Diploma)</li>
                                        <li>2pcs. Photocopied Birth Certificate (PSA)</li>
                                        <li>2pcs. Photocopied Marriage Contract (PSA), if married</li>
                                        <li>4pcs. 2x2 Picture (White background with name tag)</li>
                                        <li>1pc. Long Brown Envelope</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-primary h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">College Freshmen</h5>
                                    <ul class="mb-0">
                                        <li>Properly accomplished admission form</li>
                                        <li>Four (4) 2x2 recent, identical color pictures in white background with name tag</li>
                                        <li>Five (5) 1x1 recent, identical color pictures in white background with name tag</li>
                                        <li>Submit original and photocopied Form 138 / Report Card</li>
                                        <li>Submit original Good Moral Character certificate with dry seal and Photocopied</li>
                                        <li>If married, two (2) photocopies of marriage certificate duly signed by a priest / minister</li>
                                        <li>1pc. Long Brown Envelope</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-info h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-info">Transferees</h5>
                                    <ul class="mb-0">
                                        <li>Certificate of Transfer (Original & Photocopied)</li>
                                        <li>Certificate of Grades (Original & Photocopied)</li>
                                        <li>Good Moral Certificate (Original with Dry Seal & Photocopied)</li>
                                        <li>2pcs. Photocopied Birth Certificate (PSA)</li>
                                        <li>2pcs. Photocopied Marriage Contract (PSA), if married</li>
                                        <li>4pcs. 2x2 Picture (White background with name tag)</li>
                                        <li>2pcs. 1x1 Picture (White background)</li>
                                        <li>1pc. Long Brown Envelope</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-9 offset-md-3 col-lg-10 offset-lg-2">
          <div class="d-flex justify-content-end mt-5 mb-4">
            <a href="index.php" class="btn btn-warning fw-bold" style="color: #003399; min-width: 220px;"><i class="bi bi-arrow-left-circle me-2"></i>Go Back to NCST Main Page</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
