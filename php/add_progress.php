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
$progressText = isset($_POST['progress_text']) ? trim($_POST['progress_text']) : '';

// Validate input
if (empty($progressText) || $taskId === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit();
}

// Check if the task belongs to the user
$check_sql = "SELECT id FROM tasks WHERE id = ? AND assigned_to = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $taskId, $_SESSION['user_id']);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(403);
    echo json_encode(['error' => 'You are not authorized to update this task']);
    exit();
}

// Insert progress update
$sql = "INSERT INTO task_progress (task_id, progress_text, updated_by) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isi", $taskId, $progressText, $_SESSION['user_id']);

if ($stmt->execute()) {
    // Get the inserted progress update with user details
    $select_sql = "SELECT tp.*, u.email as updated_by_email 
                   FROM task_progress tp 
                   LEFT JOIN users u ON tp.updated_by = u.id 
                   WHERE tp.id = LAST_INSERT_ID()";
    $result = $conn->query($select_sql);
    $update = $result->fetch_assoc();
    
    echo json_encode([
        'success' => true,
        'update' => [
            'progress_text' => $update['progress_text'],
            'updated_by' => $update['updated_by_email'],
            'created_at' => date('F j, Y g:i A', strtotime($update['created_at']))
        ]
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to add progress update']);
}

$stmt->close();
$conn->close();
?> 