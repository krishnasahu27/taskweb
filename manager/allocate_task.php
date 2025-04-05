<?php
include('includes/header.php');
require_once('../php/config.php');

// Fetch all staff members
$staff_query = "SELECT id, email FROM users WHERE role = 'staff'";
$staff_result = $conn->query($staff_query);
?>

<div class="container">
    <h2>Allocate Task</h2>
    
    <?php if(isset($_SESSION['success_message'])): ?>
        <div class="alert success">
            <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
            ?>
        </div>
    <?php endif; ?>

    <form id="taskForm" method="POST" action="../php/save_task.php">
        <div class="form-group">
            <label>Task Title</label>
            <input type="text" name="title" required class="form-control">
        </div>

        <div class="form-group">
            <label>Task Description</label>
            <textarea name="description" required class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label>Schedule/Timeline</label>
            <input type="text" name="schedule" required class="form-control" placeholder="e.g., 2 weeks">
        </div>

        <div class="form-group">
            <label>Assign To</label>
            <select name="assigned_to" required class="form-control">
                <option value="">Select employee...</option>
                <?php while($staff = $staff_result->fetch_assoc()): ?>
                    <option value="<?php echo $staff['id']; ?>">
                        <?php echo htmlspecialchars($staff['email']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn-primary">Allocate Task</button>
    </form>
</div>

<style>
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

textarea.form-control {
    min-height: 100px;
    resize: vertical;
}

.btn-primary {
    display: block;
    width: 100%;
    padding: 10px;
    background: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.btn-primary:hover {
    background: #218838;
}

.alert {
    padding: 12px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>