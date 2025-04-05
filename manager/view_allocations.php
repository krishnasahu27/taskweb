<?php
include('includes/header.php');
require_once('../php/config.php');

// For managers, show only tasks they assigned
$query = "SELECT t.*, u.email as staff_email 
          FROM tasks t 
          JOIN users u ON t.assigned_to = u.id 
          WHERE t.created_by = ?
          ORDER BY t.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container">
    <h2>Task Allocations</h2>
    
    <div class="filter-section">
        <input type="text" id="emailFilter" placeholder="Filter by email..." class="filter-input">
        <button onclick="filterTable()" class="btn-primary">Filter</button>
        <button onclick="clearFilter()" class="btn-secondary">Clear</button>
    </div>

    <table class="task-table">
        <thead>
            <tr>
                <th>Staff</th>
                <th>Task Title</th>
                <th>Description</th>
                <th>Schedule</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['staff_email']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars($row['schedule']); ?></td>
                    <td>
                        <span class="status-badge <?php echo $row['status']; ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </td>
                    <td><?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?></td>
                    <td>
                        <a href="view_task.php?id=<?php echo $row['id']; ?>" class="btn-view">View</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="no-data">No tasks allocated yet</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.filter-section {
    margin-bottom: 20px;
    display: flex;
    gap: 10px;
    align-items: center;
}

.filter-input {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    flex-grow: 1;
    max-width: 300px;
}

.task-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: white;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.task-table th,
.task-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.task-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #2c3e50;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.85em;
    font-weight: 500;
}

.status-badge.pending {
    background: #fff3e0;
    color: #ef6c00;
}

.status-badge.completed {
    background: #e8f5e9;
    color: #2e7d32;
}

.status-badge.flagged {
    background: #ffebee;
    color: #c62828;
}

.btn-view {
    display: inline-block;
    padding: 6px 12px;
    background-color: #4a90e2;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
    transition: background-color 0.2s;
}

.btn-view:hover {
    background-color: #357abd;
}

.no-data {
    text-align: center;
    color: #666;
    padding: 20px;
}
</style>

<script>
function filterTable() {
    const filter = document.getElementById('emailFilter').value.toLowerCase();
    const rows = document.querySelectorAll('.task-table tbody tr');
    
    rows.forEach(row => {
        const email = row.cells[0].textContent.toLowerCase();
        row.style.display = email.includes(filter) ? '' : 'none';
    });
}

function clearFilter() {
    document.getElementById('emailFilter').value = '';
    filterTable();
}
</script>
</body>
</html>