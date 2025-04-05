<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header('Location: /taskweb/index.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - Task Management System</title>
    <link rel="stylesheet" href="/taskweb/css/style.css">
</head>
<body>
    <nav class="top-nav">
        <div class="nav-links">
            <a href="/taskweb/employee/dashboard.php" <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'class="active"' : ''; ?>>Dashboard</a>
            <a href="/taskweb/employee/my_tasks.php" <?php echo basename($_SERVER['PHP_SELF']) == 'my_tasks.php' ? 'class="active"' : ''; ?>>My Tasks</a>
        </div>
        <div class="right-nav">
            <a href="/taskweb/employee/change_password.php">Change Password</a>
            <a href="/taskweb/php/logout.php">Logout</a>
        </div>
    </nav>