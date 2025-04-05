<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /taskweb/index.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Task Management System</title>
    <link rel="stylesheet" href="/taskweb/css/style.css">
</head>
<body>
    <nav class="top-nav">
        <div class="nav-links">
            <a href="/taskweb/admin/dashboard.php" <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'class="active"' : ''; ?>>Dashboard</a>
            <a href="/taskweb/admin/register_employee.php" <?php echo basename($_SERVER['PHP_SELF']) == 'register_employee.php' ? 'class="active"' : ''; ?>>Register Employee</a>
            <a href="/taskweb/admin/allocate_task.php" <?php echo basename($_SERVER['PHP_SELF']) == 'allocate_task.php' ? 'class="active"' : ''; ?>>Allocate Task</a>
            <a href="/taskweb/admin/view_allocations.php" <?php echo basename($_SERVER['PHP_SELF']) == 'view_allocations.php' ? 'class="active"' : ''; ?>>View Allocations</a>
            <a href="/taskweb/admin/delete_employee.php" <?php echo basename($_SERVER['PHP_SELF']) == 'delete_employee.php' ? 'class="active"' : ''; ?>>Delete Employee</a>
        </div>
        <div class="right-nav">
            <a href="/taskweb/admin/change_password.php">Change Password</a>
            <a href="/taskweb/php/logout.php">Logout</a>
        </div>
    </nav>