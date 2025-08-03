<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Requirements | NCST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="faviconn.ico">
    <style>
        body { 
            font-family: 'Poppins', Arial, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .ncst-header {
            background: #003399;
            color: #fff;
            padding: 1.5rem 0 1rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 1.5rem 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        }
        .ncst-logo {
            height: 60px;
            margin-right: 1.5rem;
        }
        .dashboard-title {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: #ffd700;
            margin-bottom: 0;
        }
        .card {
            border-radius: 1.2rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            border: none;
            margin-bottom: 2rem;
        }
        .requirements-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
        }
        .requirements-title {
            color: #003399;
            font-weight: 600;
            border-bottom: 3px solid #ffd700;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
        .requirement-item {
            padding: 0.5rem 0;
            border-left: 4px solid #003399;
            padding-left: 1rem;
            margin-bottom: 0.5rem;
            background: rgba(0, 51, 153, 0.05);
            border-radius: 0 0.5rem 0.5rem 0;
        }
        .btn-ncst {
            background: #ffd700;
            color: #003399;
            border: none;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        .btn-ncst:hover {
            background: #e6c200;
            color: #003399;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
        }
        .important-note {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 1.5rem;
            border-radius: 1rem;
            margin: 2rem 0;
        }
        .contact-info {
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            color: white;
            padding: 1.5rem;
            border-radius: 1rem;
            margin: 2rem 0;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="ncst-header">
            <div class="container">
                <div class="d-flex align-items-center">
                    <img src="images/ncst-logo.png" alt="NCST Logo" class="ncst-logo">
                    <div>
                        <h1 class="dashboard-title">NCST Student Requirements</h1>
                        <p class="mb-0" style="color: #ffd700; opacity: 0.9;">Required Documents for Admission</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="important-note">
                        <h4><i class="bi bi-exclamation-triangle-fill me-2"></i>Important Notice</h4>
                        <p class="mb-0">All application forms are now processed through the NCST Portal by authorized personnel. Please prepare the following requirements and visit the NCST campus for application processing.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card requirements-card">
                                <div class="card-body">
                                    <h5 class="requirements-title">College Students</h5>
                                    <div class="requirement-item">Properly accomplished admission form</div>
                                    <div class="requirement-item">Four (4) 2x2 recent, identical color pictures in white background with name tag</div>
                                    <div class="requirement-item">Photocopy of birth certificate (NSO/PSA)</div>
                                    <div class="requirement-item">Original copy of Form 138 (Report Card)</div>
                                    <div class="requirement-item">Certificate of Good Moral Character</div>
                                    <div class="requirement-item">Medical Certificate</div>
                                    <div class="requirement-item">Photocopy of Barangay Certificate of Residency</div>
                                    <div class="requirement-item">For Transferees: Certificate of Transfer Credential/Honorable Dismissal and Transcript of Records</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card requirements-card">
                                <div class="card-body">
                                    <h5 class="requirements-title">Senior High School Students</h5>
                                    <div class="requirement-item">Properly accomplished admission form</div>
                                    <div class="requirement-item">Four (4) 2x2 recent, identical color pictures in white background with name tag</div>
                                    <div class="requirement-item">Photocopy of birth certificate (NSO/PSA)</div>
                                    <div class="requirement-item">Original copy of Form 138 (Report Card)</div>
                                    <div class="requirement-item">Certificate of Good Moral Character</div>
                                    <div class="requirement-item">Medical Certificate</div>
                                    <div class="requirement-item">Photocopy of Barangay Certificate of Residency</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="contact-info">
                        <h4><i class="bi bi-info-circle-fill me-2"></i>How to Apply</h4>
                        <p>Visit the NCST campus during office hours to submit your requirements and complete your application. Our admission officers will assist you with the application process.</p>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Office Hours:</strong><br>
                                Monday - Friday: 8:00 AM - 5:00 PM<br>
                                Saturday: 8:00 AM - 12:00 PM
                            </div>
                            <div class="col-md-6">
                                <strong>Contact Information:</strong><br>
                                Phone: (046) 123-4567<br>
                                Email: admissions@ncst.edu.ph
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="index.php" class="btn btn-ncst">
                            <i class="bi bi-arrow-left-circle me-2"></i>Back to Main Page
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
