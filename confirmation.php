<?php
session_start();
require_once __DIR__ . '/db.php';
$tracking_number = $_SESSION['tracking_number'] ?? null;
unset($_SESSION['tracking_number']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted - NCST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../faviconn.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body, html {
            min-height: 100vh;
            font-family: 'Poppins', Arial, sans-serif;
            background: linear-gradient(135deg, #003399 0%, #0055cc 100%);
        }
        .confirmation-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .confirmation-box {
            background: #fff;
            border-radius: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.13);
            padding: 3rem 2.5rem 2.5rem 2.5rem;
            text-align: center;
            max-width: 420px;
            width: 100%;
            margin: 2rem 0;
        }
        .confirmation-logo {
            background: #fff;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.2rem auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: relative;
            top: -30px;
        }
        .confirmation-logo img {
            width: 48px;
            height: 48px;
            object-fit: contain;
        }
        .confirmation-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 1.2rem auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e6f7e6;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(40,167,69,0.08);
        }
        .confirmation-icon svg {
            width: 40px;
            height: 40px;
            color: #28a745;
        }
        .confirmation-box h1 {
            color: #003399;
            font-weight: 800;
            margin-bottom: 0.7rem;
            font-size: 2.1rem;
            letter-spacing: 1px;
        }
        .confirmation-box p {
            font-size: 1.13rem;
            margin-bottom: 1.5rem;
            color: #222;
        }
        .divider {
            width: 60px;
            height: 4px;
            background: #FFD600;
            border-radius: 2px;
            margin: 1.2rem auto 1.5rem auto;
        }
        .next-steps {
            font-size: 1.01rem;
            color: #555;
            margin-bottom: 2rem;
        }
        .btn-primary {
            background: #FFD600;
            color: #003399;
            border: none;
            font-weight: 700;
            border-radius: 2rem;
            padding: 0.7rem 2.2rem;
            font-size: 1.08rem;
            box-shadow: 0 2px 8px rgba(255,214,0,0.08);
            transition: background 0.2s, color 0.2s;
        }
        .btn-primary:hover, .btn-primary:focus {
            background: #ffe066;
            color: #003399;
        }
        .btn-outline-secondary {
            border-color: #003399;
            color: #003399;
            border-radius: 2rem;
            font-weight: 600;
            padding: 0.7rem 2.2rem;
            font-size: 1.08rem;
            margin-top: 0.7rem;
        }
        .btn-outline-secondary:hover, .btn-outline-secondary:focus {
            background: #003399;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <div class="confirmation-box">
            <div class="confirmation-logo">
                <img src="images/ncst-logo.png" alt="NCST Logo">
            </div>
            <div class="confirmation-icon">
                <!-- Checkmark SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            </div>
            <h1>Thank You!</h1>
            <p>Your application has been <strong>submitted successfully</strong></p>
            <?php if ($tracking_number): ?>
                <div class="alert alert-info" style="font-size:1.1rem;">
                    <strong>Your Tracking/Reference Number:</strong><br>
                    <span style="font-size:1.3rem; color:#003399;"><?php echo htmlspecialchars($tracking_number); ?></span><br>
                   
                </div> Please keep this number and present it when submitting your requirements at NCST campus.
            <?php endif; ?>
            <a href="/enrollment_systemmm/index.php" class="btn btn-outline-secondary">Go to Main Page</a>
        </div>
    </div>
</body>
</html> 