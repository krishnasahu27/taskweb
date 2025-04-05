function showAddUserForm() {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <h3>Add New User</h3>
            <form id="addUserForm">
                <input type="text" name="username" placeholder="Username" required>
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <select name="role" required>
                    <option value="employee">Employee</option>
                    <option value="supervisor">Supervisor</option>
                    <option value="manager">Manager</option>
                </select>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Add User</button>
                <button type="button" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    `;
    document.body.appendChild(modal);

    document.getElementById('addUserForm').addEventListener('submit', handleAddUser);
}

function handleAddUser(e) {
    e.preventDefault();
    const formData = new FormData(e.target);

    fetch('../php/add_user.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function editUser(userId) {
    fetch(`../php/get_user.php?id=${userId}`)
        .then(response => response.json())
        .then(user => {
            showEditUserForm(user);
        })
        .catch(error => console.error('Error:', error));
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        fetch('../php/delete_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function closeModal() {
    document.querySelector('.modal').remove();
}