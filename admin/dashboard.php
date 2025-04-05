<?php
include('includes/header.php');
require_once('../php/config.php');
?>

<div class="container">
    <?php if(isset($_SESSION['success_message'])): ?>
        <div class="alert success fade-out">
            <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
            ?>
        </div>
    <?php endif; ?>

    <h2 class="welcome-title">Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?>!</h2>
    
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-content">
                <h3>Total Employees</h3>
                <?php
                    $emp_query = "SELECT COUNT(*) as total FROM users WHERE role != 'admin'";
                    $result = $conn->query($emp_query);
                    $total = $result->fetch_assoc()['total'];
                ?>
                <p class="stat-number"><?php echo $total; ?></p>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="card-content">
                <h3>Total Managers</h3>
                <?php
                    $manager_query = "SELECT COUNT(*) as managers FROM users WHERE role = 'manager'";
                    $result = $conn->query($manager_query);
                    $managers = $result->fetch_assoc()['managers'];
                ?>
                <p class="stat-number"><?php echo $managers; ?></p>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-user"></i>
            </div>
            <div class="card-content">
                <h3>Total Staff</h3>
                <?php
                    $staff_query = "SELECT COUNT(*) as staff FROM users WHERE role = 'staff'";
                    $result = $conn->query($staff_query);
                    $staff = $result->fetch_assoc()['staff'];
                ?>
                <p class="stat-number"><?php echo $staff; ?></p>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="card-content">
                <h3>Total Tasks</h3>
                <?php
                    $task_query = "SELECT COUNT(*) as tasks FROM tasks";
                    $result = $conn->query($task_query);
                    $tasks = $result->fetch_assoc()['tasks'];
                ?>
                <p class="stat-number"><?php echo $tasks; ?></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>