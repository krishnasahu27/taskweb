<?php
session_start();
require_once('config.php');

// Debug information
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log the received data
file_put_contents('debug.log', 
    "Session: " . print_r($_SESSION, true) . "\n" .
    "POST: " . print_r($_POST, true) . "\n", 
    FILE_APPEND
);

// Check if user is logged in and has appropriate role
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'manager' && $_SESSION['role'] !== 'admin')) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Get POST data
$task_name = $_POST['title'] ?? '';
$task_description = $_POST['description'] ?? '';
$assigned_to = $_POST['assigned_to'] ?? '';
$schedule = $_POST['schedule'] ?? '';

// Validate inputs
if (empty($task_name) || empty($task_description) || empty($assigned_to) || empty($schedule)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit();
}

try {
    // Insert task into database
    $stmt = $conn->prepare("INSERT INTO tasks (title, description, assigned_to, schedule, status, created_by) VALUES (?, ?, ?, ?, 'pending', ?)");
    $stmt->bind_param("ssisi", $task_name, $task_description, $assigned_to, $schedule, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Task allocated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error allocating task']);
    }
    
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$conn->close();
?>