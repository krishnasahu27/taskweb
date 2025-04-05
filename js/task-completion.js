function completeTask(taskId) {
    const notes = document.getElementById(`completion-notes-${taskId}`).value;
    
    fetch('../php/complete_task.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `task_id=${taskId}&notes=${encodeURIComponent(notes)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const taskRow = document.getElementById(`task-${taskId}`);
            taskRow.classList.add('completed');
            updateTaskStatus(taskId, 'completed');
        } else {
            alert(data.message || 'Failed to complete task');
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateTaskStatus(taskId, status) {
    const statusCell = document.querySelector(`#task-${taskId} .task-status`);
    statusCell.textContent = status.charAt(0).toUpperCase() + status.slice(1);
    statusCell.className = `task-status status-${status}`;
}