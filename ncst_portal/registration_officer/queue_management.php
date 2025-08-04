<?php
session_start();
require_once __DIR__ . '/../../db.php';

// Check if registration officer is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'registration') {
    header('Location: ../ncst_login.php');
    exit;
}

// Handle queue actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'next_queue':
            // Get the next waiting queue
            $stmt = $conn->prepare('SELECT * FROM queue_system WHERE status = "waiting" AND queue_date = CURDATE() ORDER BY created_at ASC LIMIT 1');
            $stmt->execute();
            $next_queue = $stmt->get_result()->fetch_assoc();
            
            if ($next_queue) {
                // Update status to processing
                $stmt = $conn->prepare('UPDATE queue_system SET status = "processing", processed_at = NOW() WHERE id = ?');
                $stmt->bind_param('i', $next_queue['id']);
                $stmt->execute();
                
                echo json_encode(['success' => true, 'queue_number' => $next_queue['queue_number']]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No more students in queue']);
            }
            break;
            
        case 'complete_queue':
            $queue_id = $_POST['queue_id'] ?? '';
            if ($queue_id) {
                $stmt = $conn->prepare('UPDATE queue_system SET status = "completed" WHERE id = ?');
                $stmt->bind_param('i', $queue_id);
                $stmt->execute();
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Queue ID required']);
            }
            break;
            
        case 'cancel_queue':
            $queue_id = $_POST['queue_id'] ?? '';
            if ($queue_id) {
                $stmt = $conn->prepare('UPDATE queue_system SET status = "cancelled" WHERE id = ?');
                $stmt->bind_param('i', $queue_id);
                $stmt->execute();
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Queue ID required']);
            }
            break;
    }
    exit;
}

// Get current queue status
$stmt = $conn->prepare('
    SELECT q.*, s.name as student_name, s.course 
    FROM queue_system q 
    JOIN students s ON q.student_id = s.student_id 
    WHERE q.queue_date = CURDATE() 
    ORDER BY q.created_at ASC
');
$stmt->execute();
$queues = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get current processing queue
$stmt = $conn->prepare('SELECT * FROM queue_system WHERE status = "processing" AND queue_date = CURDATE() LIMIT 1');
$stmt->execute();
$current_queue = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Management - Registration Office</title>
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
            border-radius: 10px;
            text-align: center;
            margin-bottom: 2rem;
        }
        .queue-number {
            font-size: 4rem;
            font-weight: bold;
            color: #ffcd00;
        }
        .status-waiting { color: #ffc107; }
        .status-processing { color: #17a2b8; }
        .status-completed { color: #28a745; }
        .status-cancelled { color: #dc3545; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background:#003399;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-person-check me-2"></i>Registration Office - Queue Management
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard.php">
                    <i class="bi bi-house me-1"></i>Dashboard
                </a>
                <a class="nav-link" href="../logout.php">
                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ol me-2"></i>Current Queue
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Queue #</th>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Course</th>
                                        <th>Status</th>
                                        <th>Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="queueTableBody">
                                    <?php foreach ($queues as $queue): ?>
                                        <tr data-queue-id="<?php echo $queue['id']; ?>">
                                            <td><strong><?php echo htmlspecialchars($queue['queue_number']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($queue['student_id']); ?></td>
                                            <td><?php echo htmlspecialchars($queue['student_name']); ?></td>
                                            <td><?php echo htmlspecialchars($queue['course']); ?></td>
                                            <td>
                                                <span class="badge status-<?php echo $queue['status']; ?>">
                                                    <?php echo ucfirst($queue['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('h:i A', strtotime($queue['created_at'])); ?></td>
                                            <td>
                                                <?php if ($queue['status'] === 'processing'): ?>
                                                    <button class="btn btn-sm btn-success" onclick="completeQueue(<?php echo $queue['id']; ?>)">
                                                        <i class="bi bi-check"></i> Complete
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="cancelQueue(<?php echo $queue['id']; ?>)">
                                                        <i class="bi bi-x"></i> Cancel
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="bi bi-display me-2"></i>Queue Display
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="queue-display">
                            <h4>Now Serving</h4>
                            <div class="queue-number" id="currentQueueNumber">
                                <?php echo $current_queue ? $current_queue['queue_number'] : '---'; ?>
                            </div>
                            <p class="mt-2" id="currentStudentInfo">
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
                        
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary btn-lg" onclick="nextQueue()">
                                <i class="bi bi-arrow-right me-2"></i>Next Queue
                            </button>
                            <button class="btn btn-outline-secondary" onclick="refreshQueue()">
                                <i class="bi bi-arrow-clockwise me-2"></i>Refresh
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Queue Statistics
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="text-warning">
                                    <h4 id="waitingCount">0</h4>
                                    <small>Waiting</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-info">
                                    <h4 id="processingCount">0</h4>
                                    <small>Processing</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-success">
                                    <h4 id="completedCount">0</h4>
                                    <small>Completed</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function nextQueue() {
            fetch('queue_management.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=next_queue'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Now serving: ' + data.queue_number);
                    refreshQueue();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                alert('Error processing queue');
            });
        }

        function completeQueue(queueId) {
            if (confirm('Mark this queue as completed?')) {
                fetch('queue_management.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=complete_queue&queue_id=' + queueId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        refreshQueue();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    alert('Error completing queue');
                });
            }
        }

        function cancelQueue(queueId) {
            if (confirm('Cancel this queue?')) {
                fetch('queue_management.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=cancel_queue&queue_id=' + queueId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        refreshQueue();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    alert('Error cancelling queue');
                });
            }
        }

        function refreshQueue() {
            location.reload();
        }

        // Auto-refresh every 30 seconds
        setInterval(refreshQueue, 30000);

        // Update statistics on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateStatistics();
        });

        function updateStatistics() {
            const waiting = document.querySelectorAll('.status-waiting').length;
            const processing = document.querySelectorAll('.status-processing').length;
            const completed = document.querySelectorAll('.status-completed').length;
            
            document.getElementById('waitingCount').textContent = waiting;
            document.getElementById('processingCount').textContent = processing;
            document.getElementById('completedCount').textContent = completed;
        }
    </script>
</body>
</html> 