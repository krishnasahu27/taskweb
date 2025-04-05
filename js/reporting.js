function generateReport() {
    const reportType = document.getElementById('reportType').value;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    fetch('../php/generate_report.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ reportType, startDate, endDate })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayReport(data.data, reportType);
        } else {
            alert('Error generating report');
        }
    })
    .catch(error => console.error('Error:', error));
}

function displayReport(data, reportType) {
    const reportContainer = document.getElementById('reportContainer');
    let html = '';

    switch(reportType) {
        case 'task_summary':
            html = generateTaskSummaryHTML(data);
            break;
        case 'user_performance':
            html = generateUserPerformanceHTML(data);
            break;
    }

    reportContainer.innerHTML = html;
}

function generateTaskSummaryHTML(data) {
    return `
        <div class="report-section">
            <h3>Task Summary Report</h3>
            <div class="report-stats">
                <div class="stat-item">
                    <span>Total Tasks: ${data.total_tasks}</span>
                </div>
                <div class="stat-item">
                    <span>Completed: ${data.completed_tasks}</span>
                </div>
                <div class="stat-item">
                    <span>In Progress: ${data.ongoing_tasks}</span>
                </div>
                <div class="stat-item">
                    <span>Pending: ${data.pending_tasks}</span>
                </div>
            </div>
        </div>
    `;
}

function exportReport(format) {
    const reportData = document.getElementById('reportContainer').innerHTML;
    
    fetch('../php/export_report.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ data: reportData, format })
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `report.${format}`;
        a.click();
    })
    .catch(error => console.error('Error:', error));
}