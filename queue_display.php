<?php
session_start();
require_once __DIR__ . '/db.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit;
}

$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'];

// Get current queue status for the student
$stmt = $conn->prepare('SELECT * FROM queue_system WHERE student_id = ? AND queue_date = CURDATE() ORDER BY created_at DESC LIMIT 1');
$stmt->bind_param('s', $student_id);
$stmt->execute();
$student_queue = $stmt->get_result()->fetch_assoc();

// Get current processing queue
$stmt = $conn->prepare('SELECT * FROM queue_system WHERE status = "processing" AND queue_date = CURDATE() LIMIT 1');
$stmt->execute();
$current_queue = $stmt->get_result()->fetch_assoc();

// Get queue statistics
$stmt = $conn->prepare('SELECT status, COUNT(*) as count FROM queue_system WHERE queue_date = CURDATE() GROUP BY status');
$stmt->execute();
$stats = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$stats_lookup = [];
foreach ($stats as $stat) {
    $stats_lookup[$stat['status']] = $stat['count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Status - <?php echo htmlspecialchars($student_name); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background: #f4f7fa;
        }
        .queue-display {
            background: #003399;
            color: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .queue-number {
            font-size: 5rem;
            font-weight: bold;
            color: #ffcd00;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        .status-waiting { color: #ffc107; }
        .status-processing { color: #17a2b8; }
        .status-completed { color: #28a745; }
        .status-cancelled { color: #dc3545; }
        .student-queue {
            background: linear-gradient(135deg, #ffcd00, #ffb300);
            color: #003399;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        .refresh-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background:#003399;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-display me-2"></i>Queue Display
            </a>
            <div class="navbar-nav ms-auto">
                <span class="nav-link">
                    <i class="bi bi-person-circle me-1"></i>
                    <?php echo htmlspecialchars($student_name); ?>
                </span>
                <a class="nav-link" href="student_portal.php">
                    <i class="bi bi-arrow-left me-1"></i>Back to Portal
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Current Queue Display -->
                <div class="queue-display">
                    <h3>Now Serving</h3>
                    <div class="queue-number" id="currentQueueNumber">
                        <?php echo $current_queue ? $current_queue['queue_number'] : '---'; ?>
                    </div>
                    <p class="mt-3" id="currentStudentInfo">
                        <?php if ($current_queue): ?>
                            <?php 
                            $stmt = $conn->prepare('SELECT name, course FROM students WHERE student_id = ?');
                            $stmt->bind_param('s', $current_queue['student_id']);
                            $stmt->execute();
                            $student = $stmt->get_result()->fetch_assoc();
                            echo htmlspecialchars($student['name']) . '<br>' . htmlspecialchars($student['course']);
                            ?>
                        <?php else: ?>
                            No student currently being served
                        <?php endif; ?>
                    </p>
                </div>

                <!-- Student's Queue Status -->
                <?php if ($student_queue): ?>
                    <div class="student-queue">
                        <h4><i class="bi bi-person-check me-2"></i>Your Queue Status</h4>
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h2 class="text-primary"><?php echo htmlspecialchars($student_queue['queue_number']); ?></h2>
                                <small>Your Queue Number</small>
                            </div>
                            <div class="col-md-4">
                                <h2 class="status-<?php echo $student_queue['status']; ?>">
                                    <?php echo ucfirst($student_queue['status']); ?>
                                </h2>
                                <small>Status</small>
                            </div>
                            <div class="col-md-4">
                                <h2 class="text-info">
                                    <?php echo date('h:i A', strtotime($student_queue['created_at'])); ?>
                                </h2>
                                <small>Time Joined</small>
                            </div>
                        </div>
                        
                        <?php if ($student_queue['status'] === 'waiting'): ?>
                            <div class="alert alert-info mt-3">
                                <i class="bi bi-info-circle me-2"></i>
                                Please wait for your number to be called. You will be notified when it's your turn.
                            </div>
                        <?php elseif ($student_queue['status'] === 'processing'): ?>
                            <div class="alert alert-warning mt-3">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>It's your turn!</strong> Please proceed to the registration counter.
                            </div>
                        <?php elseif ($student_queue['status'] === 'completed'): ?>
                            <div class="alert alert-success mt-3">
                                <i class="bi bi-check-circle me-2"></i>
                                <strong>Enrollment completed!</strong> Thank you for using our system.
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning text-center">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        You don't have an active queue number for today.
                    </div>
                <?php endif; ?>

                <!-- Queue Statistics -->
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>Today's Queue Statistics
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-3">
                                <div class="text-warning">
                                    <h3><?php echo $stats_lookup['waiting'] ?? 0; ?></h3>
                                    <small>Waiting</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-info">
                                    <h3><?php echo $stats_lookup['processing'] ?? 0; ?></h3>
                                    <small>Processing</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-success">
                                    <h3><?php echo $stats_lookup['completed'] ?? 0; ?></h3>
                                    <small>Completed</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-danger">
                                    <h3><?php echo $stats_lookup['cancelled'] ?? 0; ?></h3>
                                    <small>Cancelled</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="card mt-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Instructions
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li><i class="bi bi-clock text-warning me-2"></i><strong>Waiting:</strong> Your number is in the queue</li>
                            <li><i class="bi bi-arrow-right text-info me-2"></i><strong>Processing:</strong> Please proceed to the counter</li>
                            <li><i class="bi bi-check text-success me-2"></i><strong>Completed:</strong> Your enrollment is finished</li>
                            <li><i class="bi bi-x text-danger me-2"></i><strong>Cancelled:</strong> Your queue was cancelled</li>
                        </ul>
                        <div class="alert alert-info mt-3">
                            <small>
                                <i class="bi bi-clock me-1"></i>
                                <strong>Queue Hours:</strong> 8:00 AM - 5:00 PM (Monday to Friday)<br>
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                <strong>Note:</strong> Queue numbers are only valid until 5:00 PM today
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Refresh Button -->
    <button class="btn btn-primary btn-lg refresh-btn" onclick="refreshDisplay()">
        <i class="bi bi-arrow-clockwise"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function refreshDisplay() {
            location.reload();
        }

        // Auto-refresh every 10 seconds
        setInterval(refreshDisplay, 10000);

        // Add some visual feedback for the refresh button
        document.querySelector('.refresh-btn').addEventListener('click', function() {
            this.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i>';
            setTimeout(() => {
                this.innerHTML = '<i class="bi bi-arrow-clockwise"></i>';
            }, 1000);
        });
    </script>
</body>
</html> 