function deleteEmployee(employeeId) {
    if (confirm('Are you sure you want to delete this employee?')) {
        fetch('../php/delete_employee.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `employee_id=${employeeId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Employee deleted successfully');
                location.reload();
            } else {
                alert(data.message || 'Failed to delete employee');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the employee');
        });
    }
}