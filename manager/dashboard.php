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
                <i class="fas fa-tasks"></i>
            </div>
            <div class="card-content">
                <h3>Total Tasks</h3>
                <?php
                    $task_query = "SELECT COUNT(*) as total FROM tasks";
                    $result = $conn->query($task_query);
                    $total = $result->fetch_assoc()['total'];
                ?>
                <p class="stat-number"><?php echo $total; ?></p>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="card-content">
                <h3>Completed Tasks</h3>
                <?php
                    $completed_query = "SELECT COUNT(*) as completed FROM tasks WHERE status = 'completed'";
                    $result = $conn->query($completed_query);
                    $completed = $result->fetch_assoc()['completed'];
                ?>
                <p class="stat-number"><?php echo $completed; ?></p>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="card-content">
                <h3>Pending Tasks</h3>
                <?php
                    $pending_query = "SELECT COUNT(*) as pending FROM tasks WHERE status = 'pending'";
                    $result = $conn->query($pending_query);
                    $pending = $result->fetch_assoc()['pending'];
                ?>
                <p class="stat-number"><?php echo $pending; ?></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>