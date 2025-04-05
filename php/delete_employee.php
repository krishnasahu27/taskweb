<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // First delete related tasks
        $delete_tasks = $conn->prepare("DELETE FROM tasks WHERE assigned_to = ? OR created_by = ?");
        $delete_tasks->bind_param("ii", $user_id, $user_id);
        $delete_tasks->execute();
        
        // Then delete the user
        $delete_user = $conn->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
        $delete_user->bind_param("i", $user_id);
        $delete_user->execute();
        
        if ($delete_user->affected_rows > 0) {
            $conn->commit();
            $_SESSION['success_message'] = "Employee deleted successfully";
        } else {
            throw new Exception("Failed to delete employee");
        }
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
    }
    
    $conn->close();
    header('Location: ../admin/delete_employee.php');
    exit();
}
?>