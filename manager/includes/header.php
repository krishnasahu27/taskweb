<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header('Location: /taskweb/index.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard - Task Management System</title>
    <link rel="stylesheet" href="/taskweb/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="top-nav">
        <div class="nav-links">
            <a href="/taskweb/manager/dashboard.php" <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'class="active"' : ''; ?>>Dashboard</a>
            <a href="/taskweb/manager/allocate_task.php" <?php echo basename($_SERVER['PHP_SELF']) == 'allocate_task.php' ? 'class="active"' : ''; ?>>Allocate Task</a>
            <a href="/taskweb/manager/view_allocations.php" <?php echo basename($_SERVER['PHP_SELF']) == 'view_allocations.php' ? 'class="active"' : ''; ?>>View Allocations</a>
        </div>
        <div class="right-nav">
            <a href="/taskweb/manager/change_password.php">Change Password</a>
            <a href="/taskweb/php/logout.php">Logout</a>
        </div>
    </nav>