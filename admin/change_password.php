<?php
include('includes/header.php'); // header.php already has session_start()
?>

<div class="container">
    <h2>Change Password</h2>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert error">
            <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['success_message'])): ?>
        <div class="alert success">
            <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
            ?>
        </div>
    <?php endif; ?>

    <form action="../php/update_password.php" method="POST">
        <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password" required>
        </div>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" required>
        </div>
        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn-primary">Change Password</button>
    </form>
</div>
</body>
</html>