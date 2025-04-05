<?php
include('includes/header.php');
require_once('../php/config.php');

// Get task statistics
$stats_query = "SELECT 
    COUNT(*) as total_tasks,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_tasks,
    SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_tasks
    FROM tasks 
    WHERE assigned_to = ?";

$stmt = $conn->prepare($stats_query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();
?>

<div class="container">
    <h1 class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?>!</h1>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-list"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total Tasks</span>
                <span class="stat-value"><?php echo $stats['total_tasks'] ?? 0; ?></span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Completed Tasks</span>
                <span class="stat-value"><?php echo $stats['completed_tasks'] ?? 0; ?></span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple-light">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Pending Tasks</span>
                <span class="stat-value"><?php echo $stats['pending_tasks'] ?? 0; ?></span>
            </div>
        </div>
    </div>
</div>

<style>
.container {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.welcome-message {
    font-size: 24px;
    color: #333;
    margin-bottom: 40px;
}

.stats-grid {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.stat-card {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    min-width: 250px;
    flex: 1;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon i {
    color: #fff;
    font-size: 1.2rem;
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-label {
    color: #666;
    font-size: 14px;
    margin-bottom: 5px;
}

.stat-value {
    color: #333;
    font-size: 24px;
    font-weight: 600;
}

/* Icon colors */
.blue {
    background-color: #4F46E5;
}

.purple {
    background-color: #7C3AED;
}

.purple-light {
    background-color: #8B5CF6;
}

/* Responsive design */
@media (max-width: 768px) {
    .stats-grid {
        flex-direction: column;
    }
    
    .stat-card {
        width: 100%;
    }
}
</style>
</body>
</html>