function filterTasks() {
    const filterValue = document.getElementById('emailFilter').value.toLowerCase();
    const tableRows = document.querySelectorAll('.allocation-table tbody tr');
    
    tableRows.forEach(row => {
        const staffEmail = row.querySelector('td:first-child').textContent.toLowerCase();
        row.style.display = staffEmail.includes(filterValue) ? '' : 'none';
    });
}

function clearFilter() {
    document.getElementById('emailFilter').value = '';
    document.querySelectorAll('.allocation-table tbody tr').forEach(row => {
        row.style.display = '';
    });
}

document.getElementById('emailFilter').addEventListener('input', function() {
    if (this.value.length >= 3) {
        filterTasks();
    } else if (this.value.length === 0) {
        clearFilter();
    }
});