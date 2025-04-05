<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $report_type = $_POST['report_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    switch($report_type) {
        case 'task_summary':
            $sql = "SELECT 
                    COUNT(*) as total_tasks,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,
                    SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as ongoing_tasks,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_tasks
                    FROM tasks 
                    WHERE created_at BETWEEN ? AND ?";
            break;
            
        case 'user_performance':
            $sql = "SELECT 
                    u.username,
                    COUNT(t.task_id) as total_assigned,
                    SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) as completed_tasks
                    FROM users u
                    LEFT JOIN tasks t ON u.user_id = t.assigned_to
                    WHERE t.created_at BETWEEN ? AND ?
                    GROUP BY u.user_id";
            break;
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $data = [];
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $data]);
}
?>