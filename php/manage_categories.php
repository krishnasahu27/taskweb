<?php
session_start();
require_once('config.php');

if ($_SESSION['role'] !== 'admin') {
    exit('Access denied');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    
    switch($action) {
        case 'add':
            $name = $_POST['name'];
            $description = $_POST['description'];
            
            $sql = "INSERT INTO task_categories (name, description) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $name, $description);
            break;
            
        case 'update':
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            
            $sql = "UPDATE task_categories SET name = ?, description = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $name, $description, $id);
            break;
            
        case 'delete':
            $id = $_POST['id'];
            
            $sql = "DELETE FROM task_categories WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            break;
    }
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Operation failed']);
    }
}
?>