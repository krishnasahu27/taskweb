<?php
session_start();
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $user = $result->fetch_assoc()) {
            // For the sample users with password 'admin123'
            if ($password === 'admin123') {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                $redirect = '';
                switch($user['role']) {
                    case 'admin':
                        $redirect = '/taskweb/admin/dashboard.php';
                        break;
                    case 'manager':
                        $redirect = '/taskweb/manager/dashboard.php';
                        break;
                    case 'staff':
                        $redirect = '/taskweb/employee/dashboard.php';
                        break;
                }
                
                echo json_encode(['success' => true, 'redirect' => $redirect]);
                exit;
            }
        }
    }
    
    echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
    exit;
}
?>