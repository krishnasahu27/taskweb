<?php
include('includes/header.php');
require_once('../php/config.php');

// Check if user is admin or manager
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager')) {
    header('Location: ../index.html');
    exit();
}

// Fetch all staff members
$staff_query = "SELECT id, email FROM users WHERE role = 'staff'";
$staff_result = $conn->query($staff_query);

// Fetch existing tasks
$tasks_query = "SELECT * FROM tasks WHERE assigned_to IS NULL";
$tasks_result = $conn->query($tasks_query);
?>

<div class="container">
    <h2 class="page-title">Allocate Task</h2>
    <div class="task-allocation-container">
        <form id="taskForm" method="POST">
            <div class="task-section">
                <div class="form-group">
                    <label for="title">Task Title</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="description">Task Description</label>
                    <textarea id="description" name="description" required></textarea>
                </div>

                <div class="form-group">
                    <label for="schedule">Schedule/Timeline</label>
                    <input type="text" id="schedule" name="schedule" required placeholder="e.g., 2 weeks">
                </div>
            </div>

            <div class="employee-section">
                <label for="assigned_to">Assign To</label>
                <select id="assigned_to" name="assigned_to" required>
                    <option value="">Select employee...</option>
                    <?php while ($staff = $staff_result->fetch_assoc()): ?>
                        <option value="<?php echo $staff['id']; ?>">
                            <?php echo htmlspecialchars($staff['email']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="allocate-btn">Allocate Task</button>
        </form>
        <div id="message" style="display: none;"></div>
    </div>
</div>

<style>
.container {
    padding: 20px;
    max-width: 800px;
    margin: 0 auto;
}

.page-title {
    font-size: 24px;
    color: #333;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 500;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    font-size: 14px;
}

.form-group textarea {
    height: 100px;
    resize: vertical;
}

.employee-section {
    margin-top: 30px;
}

select {
    width: 100%;
    padding: 10px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    font-size: 14px;
    margin-top: 8px;
}

.allocate-btn {
    display: block;
    width: 100%;
    padding: 12px;
    background: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    margin-top: 30px;
    transition: background-color 0.3s;
}

.allocate-btn:hover {
    background: #218838;
}

.alert {
    padding: 12px;
    border-radius: 4px;
    margin-top: 20px;
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

<script>
document.getElementById('taskForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('../php/save_task.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const messageDiv = document.getElementById('message');
        messageDiv.textContent = data.message;
        messageDiv.style.display = 'block';
        messageDiv.className = data.success ? 'alert success' : 'alert error';
        
        if (data.success) {
            this.reset();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const messageDiv = document.getElementById('message');
        messageDiv.textContent = 'An error occurred';
        messageDiv.style.display = 'block';
        messageDiv.className = 'alert error';
    });
});
</script>