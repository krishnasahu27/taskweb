document.addEventListener('DOMContentLoaded', function() {
    // Load dashboard statistics
    fetchDashboardStats();
    // Load recent activities
    fetchRecentActivities();
});

function fetchDashboardStats() {
    fetch('../php/get_dashboard_stats.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('totalUsers').textContent = data.totalUsers;
            document.getElementById('activeTasks').textContent = data.activeTasks;
            document.getElementById('completedTasks').textContent = data.completedTasks;
        })
        .catch(error => console.error('Error:', error));
}

function fetchRecentActivities() {
    fetch('../php/get_recent_activities.php')
        .then(response => response.json())
        .then(data => {
            const activitiesList = document.getElementById('activitiesList');
            activitiesList.innerHTML = data.activities.map(activity => `
                <div class="activity-item">
                    <span class="activity-time">${activity.created_at}</span>
                    <span class="activity-text">${activity.description}</span>
                </div>
            `).join('');
        })
        .catch(error => console.error('Error:', error));
}