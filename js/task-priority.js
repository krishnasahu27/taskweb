function updatePriority(taskId, priority) {
    fetch('../php/update_priority.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `task_id=${taskId}&priority=${priority}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const priorityBadge = document.getElementById(`priority-${taskId}`);
            priorityBadge.className = `priority-badge ${priority}`;
            priorityBadge.textContent = priority.charAt(0).toUpperCase() + priority.slice(1);
        } else {
            alert(data.message || 'Failed to update priority');
        }
    })
    .catch(error => console.error('Error:', error));
}

function initPriorityDropdowns() {
    document.querySelectorAll('.priority-select').forEach(select => {
        select.addEventListener('change', function() {
            const taskId = this.getAttribute('data-task-id');
            updatePriority(taskId, this.value);
        });
    });
}