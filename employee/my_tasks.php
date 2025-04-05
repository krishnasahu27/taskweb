<?php
include('includes/header.php');
require_once('../php/config.php');

// Get tasks assigned to the current employee
$query = "SELECT t.*, u.email as assigned_by_email 
          FROM tasks t 
          JOIN users u ON t.created_by = u.id 
          WHERE t.assigned_to = ?
          ORDER BY t.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container">
    <h2>My Tasks</h2>
    
    <table class="task-table">
        <thead>
            <tr>
                <th>Task Title</th>
                <th>Description</th>
                <th>Schedule</th>
                <th>Status</th>
                <th>Assigned By</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars($row['schedule']); ?></td>
                    <td>
                        <span class="status-badge <?php echo $row['status']; ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($row['assigned_by_email']); ?></td>
                    <td><?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?></td>
                    <td class="task-actions">
                        <a href="view_task.php?id=<?php echo $row['id']; ?>" class="btn-view">View</a>
                        <?php if ($row['status'] !== 'completed'): ?>
                            <button onclick="updateTaskStatus(<?php echo $row['id']; ?>, 'completed')" class="btn-complete">Complete</button>
                            <button onclick="updateTaskStatus(<?php echo $row['id']; ?>, 'flagged')" class="btn-flag">Flag</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="no-data">No tasks assigned yet</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
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

<style>
.task-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    background: white;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.task-table th,
.task-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.task-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #2c3e50;
}

.task-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

.btn-view,
.btn-complete,
.btn-flag {
    padding: 6px 12px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.2s;
}

.btn-view {
    background-color: #4a90e2;
    color: white;
    text-decoration: none;
}

.btn-complete {
    background-color: #2ecc71;
    color: white;
}

.btn-flag {
    background-color: #f1c40f;
    color: #2c3e50;
}

.btn-view:hover {
    background-color: #357abd;
}

.btn-complete:hover {
    background-color: #27ae60;
}

.btn-flag:hover {
    background-color: #f39c12;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.85em;
    font-weight: 500;
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

.no-data {
    text-align: center;
    color: #666;
    padding: 20px;
}
</style>
</body>
</html>