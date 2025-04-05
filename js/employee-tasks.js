function flagTask(taskId) {
    if (confirm('Are you sure you want to flag this task?')) {
        fetch('../php/flag_task.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `task_id=${taskId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Task has been flagged');
                location.reload();
            } else {
                alert(data.message || 'Failed to flag task');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while flagging the task');
        });
    }
}