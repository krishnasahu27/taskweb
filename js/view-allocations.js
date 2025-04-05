function filterTasks() {
    const filter = document.getElementById('emailFilter').value.toLowerCase();
    const rows = document.querySelectorAll('.allocation-table tbody tr');
    
    rows.forEach(row => {
        const email = row.querySelector('td:first-child').textContent.toLowerCase();
        row.style.display = email.includes(filter) ? '' : 'none';
    });
}

function clearFilter() {
    document.getElementById('emailFilter').value = '';
    const rows = document.querySelectorAll('.allocation-table tbody tr');
    rows.forEach(row => row.style.display = '');
}

// Add event listener for real-time filtering
document.getElementById('emailFilter').addEventListener('input', filterTasks);