document.addEventListener('DOMContentLoaded', function() {
    // Task form submission
    const taskForm = document.getElementById('taskForm');
    if (taskForm) {
        taskForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('../php/create_task.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Task created successfully');
                    this.reset();
                } else {
                    alert(data.message || 'Failed to create task');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while creating the task');
            });
        });
    }
});

function allocateTask() {
    const form = document.getElementById('allocateTaskForm');
    const formData = new FormData(form);
    
    fetch('../php/allocate_task.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Task allocated successfully');
            form.reset();
        } else {
            alert(data.message || 'Failed to allocate task');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while allocating the task');
    });
}

function updateTaskStatus(taskId, status) {
    fetch('../php/update_task_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `task_id=${taskId}&status=${status}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Failed to update task status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the task');
    });
}

function loadTaskDetails(taskId) {
    fetch(`../php/get_task_details.php?id=${taskId}`)
        .then(response => response.json())
        .then(task => {
            showTaskDetailsModal(task);
        })
        .catch(error => console.error('Error:', error));
}

function showTaskDetailsModal(task) {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <h3>${task.title}</h3>
            <div class="task-details">
                <p><strong>Description:</strong> ${task.description}</p>
                <p><strong>Category:</strong> ${task.category}</p>
                <p><strong>Status:</strong> ${task.status}</p>
                <p><strong>Priority:</strong> ${task.priority}</p>
                <p><strong>Due Date:</strong> ${task.due_date}</p>
            </div>
            <button onclick="closeModal()">Close</button>
        </div>
    `;
    document.body.appendChild(modal);
}