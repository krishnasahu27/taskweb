document.getElementById('allocateTaskForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const selectedTasks = Array.from(document.querySelectorAll('input[name="tasks[]"]:checked'))
        .map(checkbox => checkbox.value);
        
    if (selectedTasks.length === 0) {
        alert('Please select at least one task');
        return;
    }
    
    const formData = new FormData(this);
    
    fetch('../php/manager_allocate_task.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Tasks allocated successfully');
            this.reset();
        } else {
            alert(data.message || 'Failed to allocate tasks');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while allocating tasks');
    });
});