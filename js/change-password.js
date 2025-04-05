document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    if (formData.get('new_password') !== formData.get('confirm_password')) {
        alert('New passwords do not match');
        return;
    }
    
    fetch('../php/update_password.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Password updated successfully');
            this.reset();
        } else {
            alert(data.message || 'Failed to update password');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the password');
    });
});