function viewTaskHistory(taskId) {
    fetch(`../php/get_task_history.php?task_id=${taskId}`)
        .then(response => response.json())
        .then(data => {
            const historyContainer = document.getElementById('task-history');
            historyContainer.innerHTML = data.history.map(entry => `
                <div class="history-entry">
                    <div class="history-date">${entry.action_date}</div>
                    <div class="history-action">${entry.action}</div>
                    ${entry.old_status ? `
                        <div class="status-change">
                            ${entry.old_status} → ${entry.new_status}
                        </div>
                    ` : ''}
                    <div class="history-user">by ${entry.user_email}</div>
                </div>
            `).join('');
            
            showHistoryModal();
        })
        .catch(error => console.error('Error:', error));
}

function showHistoryModal() {
    const modal = document.getElementById('history-modal');
    modal.style.display = 'block';
}

function closeHistoryModal() {
    const modal = document.getElementById('history-modal');
    modal.style.display = 'none';
}