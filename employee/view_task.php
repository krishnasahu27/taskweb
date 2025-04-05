<?php
session_start();
require_once('../php/config.php');

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$taskId = $_GET['id'];
$sql = "SELECT t.*, u.email as assigned_by_email 
        FROM tasks t 
        LEFT JOIN users u ON t.created_by = u.id 
        WHERE t.id = ? AND t.assigned_to = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $taskId, $_SESSION['user_id']);
$stmt->execute();
$task = $stmt->get_result()->fetch_assoc();

if (!$task) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Task</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/task-features.css">
</head>
<body>
    <?php require_once('includes/nav.php'); ?>

    <div class="container">
        <div class="task-details-card">
            <h2><?php echo htmlspecialchars($task['title']); ?></h2>
            
            <div class="task-info">
                <div class="status-badge <?php echo $task['status']; ?>">
                    <?php echo ucfirst($task['status']); ?>
                </div>
            </div>

            <div class="task-description">
                <h3>Description</h3>
                <p><?php echo nl2br(htmlspecialchars($task['description'])); ?></p>
            </div>

            <div class="task-metadata">
                <p><strong>Assigned By:</strong> <?php echo htmlspecialchars($task['assigned_by_email']); ?></p>
                <p><strong>Schedule:</strong> <?php echo htmlspecialchars($task['schedule']); ?></p>
                <p><strong>Created At:</strong> <?php echo date('F j, Y g:i A', strtotime($task['created_at'])); ?></p>
            </div>

            <!-- Task Progress Updates Section -->
            <div class="task-progress-section">
                <h3>Progress Updates</h3>
                <?php
                // Fetch progress updates
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
                
                <div class="progress-updates-list">
                    <?php while ($update = $progress_result->fetch_assoc()): ?>
                        <div class="progress-update-item">
                            <p class="progress-text"><?php echo nl2br(htmlspecialchars($update['progress_text'])); ?></p>
                            <div class="progress-meta">
                                <small>Updated by: <?php echo htmlspecialchars($update['updated_by_email']); ?></small>
                                <small>on <?php echo date('F j, Y g:i A', strtotime($update['created_at'])); ?></small>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <?php if ($task['status'] !== 'completed'): ?>
                <div class="add-progress-update">
                    <h4>Add Progress Update</h4>
                    <form id="progress-form" class="progress-form">
                        <input type="hidden" name="task_id" value="<?php echo $taskId; ?>">
                        <textarea name="progress_text" id="progress_text" rows="3" 
                                placeholder="Enter your progress update..." required></textarea>
                        <button type="submit" class="btn-primary">Add Update</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>

            <?php if ($task['status'] !== 'completed'): ?>
            <div class="task-actions">
                <button onclick="updateTaskStatus(<?php echo $taskId; ?>, 'completed')" class="btn-complete">Mark as Complete</button>
                <button onclick="updateTaskStatus(<?php echo $taskId; ?>, 'flagged')" class="btn-flag">Flag Task</button>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const progressForm = document.getElementById('progress-form');
        if (progressForm) {
            progressForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch('../php/add_progress.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        throw new Error(data.error || 'Failed to add progress update');
                    }
                })
                .catch(error => {
                    alert(error.message);
                });
            });
        }
    });

    function updateTaskStatus(taskId, status) {
        if (!confirm('Are you sure you want to mark this task as ' + status + '?')) {
            return;
        }
        
        fetch('../php/update_task_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `task_id=${taskId}&status=${status}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating task status: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            alert('Error updating task status: ' + error.message);
        });
    }
    </script>
</body>
</html>