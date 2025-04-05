<?php
session_start();
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $completion_notes = $_POST['notes'];
    $user_id = $_SESSION['user_id'];

    $conn->begin_transaction();
    
    try {
        // Update task status and add completion details
        $sql = "UPDATE tasks SET 
                status = 'completed',
                completed_at = CURRENT_TIMESTAMP,
                completion_notes = ?
                WHERE id = ? AND assigned_to = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $completion_notes, $task_id, $user_id);
        $stmt->execute();

        // Log in history
        $history_sql = "INSERT INTO task_history (task_id, user_id, action, old_status, new_status) 
                       VALUES (?, ?, 'Task completed', 'pending', 'completed')";
        $history_stmt = $conn->prepare($history_sql);
        $history_stmt->bind_param("ii", $task_id, $user_id);
        $history_stmt->execute();

        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>