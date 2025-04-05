<?php
include('includes/header.php');
require_once('../php/config.php');

// Fetch all employees except admin
$query = "SELECT id, email, role FROM users WHERE role != 'admin' ORDER BY role, email";
$result = $conn->query($query);
?>

<div class="container">
    <h2>Delete Employee</h2>
    
    <?php if(isset($_SESSION['success_message'])): ?>
        <div class="alert success">
            <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
            ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error_message'])): ?>
        <div class="alert error">
            <?php 
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
            ?>
        </div>
    <?php endif; ?>

    <table class="allocation-table">
        <thead>
            <tr>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                    <td>
                        <button class="btn-warning" 
                                onclick="deleteEmployee(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['email']); ?>')">
                            Delete
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="no-data">No employees found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function deleteEmployee(userId, email) {
    if (confirm(`Are you sure you want to delete employee: ${email}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '../php/delete_employee.php';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'user_id';
        input.value = userId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<style>
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
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

.no-data {
    text-align: center;
    padding: 20px;
    color: #666;
}
</style>