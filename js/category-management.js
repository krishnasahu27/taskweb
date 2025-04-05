function manageCategory(action, id = null) {
    const formData = new FormData();
    formData.append('action', action);
    
    if (action === 'add' || action === 'update') {
        formData.append('name', document.getElementById('category-name').value);
        formData.append('description', document.getElementById('category-description').value);
        if (id) formData.append('id', id);
    } else if (action === 'delete') {
        if (!confirm('Are you sure you want to delete this category?')) return;
        formData.append('id', id);
    }

    fetch('../php/manage_categories.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Operation failed');
        }
    })
    .catch(error => console.error('Error:', error));
}

function loadCategories() {
    fetch('../php/get_categories.php')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('task-category');
            select.innerHTML = '<option value="">Select Category</option>' +
                data.categories.map(category => 
                    `<option value="${category.id}">${category.name}</option>`
                ).join('');
        })
        .catch(error => console.error('Error:', error));
}