<?php
if (!isset($_SESSION)) {
    session_start();
}

// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="top-nav">
    <div class="nav-wrapper">
        <div class="nav-left">
            <a href="dashboard.php" class="<?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
            <a href="allocate_task.php" class="<?php echo $current_page === 'allocate_task.php' ? 'active' : ''; ?>">Allocate Task</a>
            <a href="view_allocations.php" class="<?php echo $current_page === 'view_allocations.php' || $current_page === 'view_task.php' ? 'active' : ''; ?>">View Allocations</a>
        </div>
        <div class="nav-right">
            <a href="change_password.php" class="<?php echo $current_page === 'change_password.php' ? 'active' : ''; ?>">Change Password</a>
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
    align-items: center;
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

@media (max-width: 768px) {
    .nav-wrapper {
        flex-direction: column;
        gap: 1rem;
    }
    
    .nav-left, .nav-right {
        flex-wrap: wrap;
        justify-content: center;
    }
}
</style> 