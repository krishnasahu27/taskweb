<?php
session_start();
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION['user_id'];

    // Verify current password
    $query = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $current_password === $user['password']) {
        if ($new_password === $confirm_password) {
            // Update password
            $update_query = "UPDATE users SET password = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("si", $new_password, $user_id);
            
            if ($update_stmt->execute()) {
                $_SESSION['success_message'] = "Password updated successfully!";
            } else {
                $_SESSION['error'] = "Error updating password!";
            }
        } else {
            $_SESSION['error'] = "New passwords do not match!";
        }
    } else {
        $_SESSION['error'] = "Current password is incorrect!";
    }

    // Redirect based on user role
    $redirect_path = $_SESSION['role'] === 'admin' ? '/taskweb/admin/change_password.php' : '/taskweb/manager/change_password.php';
    header("Location: " . $redirect_path);
    exit();
}
?>