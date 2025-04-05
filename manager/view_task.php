<?php
session_start();
require_once('../php/config.php');

// Check if user is logged in and is a manager
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header('Location: ../index.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: view_allocations.php');
    exit();
}

$taskId = $_GET['id'];

// Get task details with staff information
$query = "SELECT t.*, u.email as staff_email 
          FROM tasks t 
          JOIN users u ON t.assigned_to = u.id 
          WHERE t.id = ? AND t.created_by = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $taskId, $_SESSION['user_id']);
$stmt->execute();
$task = $stmt->get_result()->fetch_assoc();

if (!$task) {
    header('Location: view_allocations.php');
    exit();
}

// Get task progress updates
$progress_sql = "SELECT tp.*, u.email as updated_by_email 
                FROM task_progress tp 
                LEFT JOIN users u ON tp.updated_by = u.id 
                WHERE tp.task_id = ? 
                ORDER BY tp.created_at DESC";
$progress_stmt = $conn->prepare($progress_sql);
$progress_stmt->bind_param("i", $taskId);
$progress_stmt->execute();
$progress_result = $progress_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Task</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include('includes/nav.php'); ?>

    <div class="container">
        <div class="task-details-card">
            <div class="card-header">
                <h2><?php echo htmlspecialchars($task['title']); ?></h2>
                <a href="view_allocations.php" class="btn-back">← Back to Tasks</a>
            </div>

            <div class="task-info">
                <div class="status-badge <?php echo $task['status']; ?>">
                    <?php echo ucfirst($task['status']); ?>
                </div>
            </div>

            <div class="task-section">
                <h3>Description</h3>
                <p><?php echo nl2br(htmlspecialchars($task['description'])); ?></p>
            </div>

            <div class="task-section">
                <h3>Details</h3>
                <div class="details-grid">
                    <div class="detail-item">
                        <label>Assigned To:</label>
                        <span><?php echo htmlspecialchars($task['staff_email']); ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Schedule:</label>
                        <span><?php echo htmlspecialchars($task['schedule']); ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Created At:</label>
                        <span><?php echo date('F j, Y g:i A', strtotime($task['created_at'])); ?></span>
                    </div>
                    <?php if ($task['completed_at']): ?>
                    <div class="detail-item">
                        <label>Completed At:</label>
                        <span><?php echo date('F j, Y g:i A', strtotime($task['completed_at'])); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="task-section">
                <h3>Progress Updates</h3>
                <div class="progress-updates-list">
                    <?php if ($progress_result->num_rows > 0): ?>
                        <?php while ($update = $progress_result->fetch_assoc()): ?>
                            <div class="progress-update-item">
                                <p class="progress-text"><?php echo nl2br(htmlspecialchars($update['progress_text'])); ?></p>
                                <div class="progress-meta">
                                    <small>Updated by: <?php echo htmlspecialchars($update['updated_by_email']); ?></small>
                                    <small>on <?php echo date('F j, Y g:i A', strtotime($update['created_at'])); ?></small>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="no-data">No progress updates yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <style>
    .task-details-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin: 2rem auto;
        max-width: 800px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .btn-back {
        color: #4a90e2;
        text-decoration: none;
        font-weight: 500;
    }

    .task-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #eee;
    }

    .task-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
    }

    .detail-item {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 6px;
    }

    .detail-item label {
        display: block;
        color: #6c757d;
        font-size: 0.9em;
        margin-bottom: 0.5rem;
    }

    .progress-updates-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .progress-update-item {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 6px;
    }

    .progress-text {
        margin-bottom: 0.5rem;
        line-height: 1.5;
    }

    .progress-meta {
        display: flex;
        justify-content: space-between;
        color: #6c757d;
        font-size: 0.85em;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
        margin-bottom: 2rem;
    }

    .status-badge.pending {
        background: #fff3e0;
        color: #ef6c00;
    }

    .status-badge.completed {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-badge.flagged {
        background: #ffebee;
        color: #c62828;
    }
    </style>
</body>
</html> 