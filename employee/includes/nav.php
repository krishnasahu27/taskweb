<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<nav class="top-nav">
    <div class="nav-wrapper">
        <div class="nav-left">
            <a href="dashboard.php">Dashboard</a>
            <a href="my_tasks.php" class="active">My Tasks</a>
        </div>
        <div class="nav-right">
            <a href="change_password.php">Change Password</a>
            <a href="../php/logout.php">Logout</a>
        </div>
    </div>
</nav>

<style>
.top-nav {
    background-color: #2c3e50;
    padding: 1rem;
    margin-bottom: 2rem;
}

.nav-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
}

.nav-left, .nav-right {
    display: flex;
    gap: 1.5rem;
}

.top-nav a {
    color: #ecf0f1;
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.top-nav a:hover {
    background-color: #34495e;
}

.top-nav a.active {
    background-color: #3498db;
}
</style> 