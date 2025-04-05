document.addEventListener('DOMContentLoaded', function() {
    const progressForm = document.getElementById('progress-form');
    const progressList = document.querySelector('.progress-updates-list');

    if (progressForm) {
        progressForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const taskId = new URLSearchParams(window.location.search).get('id');
            const progressText = document.getElementById('progress_text').value;
            
            if (!progressText.trim()) {
                alert('Please enter a progress update');
                return;
            }

            const formData = new FormData();
            formData.append('task_id', taskId);
            formData.append('progress_text', progressText);

            fetch('../php/add_progress.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Create new progress update element
                    const updateElement = document.createElement('div');
                    updateElement.className = 'progress-update-item';
                    updateElement.innerHTML = `
                        <p class="progress-text">${data.update.progress_text.replace(/\n/g, '<br>')}</p>
                        <div class="progress-meta">
                            <small>Updated by: ${data.update.updated_by}</small>
                            <small>on ${data.update.created_at}</small>
                        </div>
                    `;

                    // Add the new update at the top of the list
                    progressList.insertBefore(updateElement, progressList.firstChild);
                    
                    // Clear the form
                    progressForm.reset();
                } else {
                    throw new Error(data.error || 'Failed to add progress update');
                }
            })
            .catch(error => {
                alert(error.message);
            });
        });
    }
}); 