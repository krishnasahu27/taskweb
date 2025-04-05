<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $assigned_to = $_POST['assigned_to'];
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];
    $assigned_by = $_SESSION['user_id'];

    $sql = "INSERT INTO tasks (title, description, category, assigned_by, assigned_to, priority, due_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiiss", $title, $description, $category, $assigned_by, $assigned_to, $priority, $due_date);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Task created successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error creating task']);
    }
    
    $stmt->close();
    $conn->close();
}
?>