<?php
session_start();
require_once('config.php');

function logTaskAction($task_id, $action, $old_status = null, $new_status = null) {
    global $conn;
    
    $sql = "INSERT INTO task_history (task_id, user_id, action, old_status, new_status) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $user_id = $_SESSION['user_id'];
    $stmt->bind_param("iisss", $task_id, $user_id, $action, $old_status, $new_status);
    return $stmt->execute();
}

// Example usage in task update:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $new_status = $_POST['status'];
    
    // Get current status
    $status_sql = "SELECT status FROM tasks WHERE id = ?";
    $status_stmt = $conn->prepare($status_sql);
    $status_stmt->bind_param("i", $task_id);
    $status_stmt->execute();
    $result = $status_stmt->get_result();
    $task = $result->fetch_assoc();
    $old_status = $task['status'];
    
    // Update task status
    $update_sql = "UPDATE tasks SET status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $new_status, $task_id);
    
    if ($update_stmt->execute()) {
        // Log the change
        logTaskAction($task_id, "Status updated", $old_status, $new_status);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update task']);
    }
}
?>