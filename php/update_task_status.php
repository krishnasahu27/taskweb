<?php
session_start();
require_once('config.php');

// Check if user is logged in and request is POST
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

// Get POST data
$taskId = isset($_POST['task_id']) ? intval($_POST['task_id']) : 0;
$status = isset($_POST['status']) ? trim($_POST['status']) : '';

// Validate input
if ($taskId === 0 || !in_array($status, ['pending', 'completed', 'flagged'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit();
}

// Check if the task belongs to the user
$check_sql = "SELECT id, status FROM tasks WHERE id = ? AND assigned_to = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $taskId, $_SESSION['user_id']);
$check_stmt->execute();
$result = $check_stmt->get_result();
$task = $result->fetch_assoc();

if (!$task) {
    http_response_code(403);
    echo json_encode(['error' => 'You are not authorized to update this task']);
    exit();
}

// Don't update if task is already completed
if ($task['status'] === 'completed' && $status !== 'completed') {
    http_response_code(400);
    echo json_encode(['error' => 'Cannot update status of completed task']);
    exit();
}

// Start transaction
$conn->begin_transaction();

try {
    // Update task status
    $update_sql = "UPDATE tasks SET status = ? WHERE id = ?";
    if ($status === 'completed') {
        $update_sql = "UPDATE tasks SET status = ?, completed_at = CURRENT_TIMESTAMP WHERE id = ?";
    }
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $status, $taskId);
    
    if (!$update_stmt->execute()) {
        throw new Exception($update_stmt->error);
    }

    // Add to task history
    $history_sql = "INSERT INTO task_history (task_id, user_id, action, old_status, new_status) 
                   VALUES (?, ?, 'status_update', ?, ?)";
    $history_stmt = $conn->prepare($history_sql);
    $history_stmt->bind_param("iiss", $taskId, $_SESSION['user_id'], $task['status'], $status);
    
    if (!$history_stmt->execute()) {
        throw new Exception($history_stmt->error);
    }

    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update task status: ' . $e->getMessage()]);
}

$conn->close();
?>