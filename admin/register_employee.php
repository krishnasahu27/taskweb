<?php
include('includes/header.php');
?>

<div class="container">
    <h2>Register New Employee</h2>
    <form action="../php/register_employee.php" method="POST">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required class="form-control">
        </div>
        
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required class="form-control">
        </div>
        
        <div class="form-group">
            <label>Role</label>
            <select name="role" required class="form-control">
                <option value="staff">Staff</option>
                <option value="manager">Manager</option>
            </select>
        </div>
        
        <button type="submit" class="btn-primary">Register Employee</button>
    </form>
</div>
</body>
</html>