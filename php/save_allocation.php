<?php
session_start();
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id = $_POST['staff_id'];
    $task_id = $_POST['task_id'];
    $manager_id = $_SESSION['user_id'];

    // Update task assignment
    $query = "UPDATE tasks SET assigned_to = ?, assigned_by = ?, status = 'pending' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $staff_id, $manager_id, $task_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Task allocated successfully!";
    } else {
        $_SESSION['error'] = "Error allocating task!";
    }

    header('Location: ../manager/allocate_task.php');
    exit();
}
?>