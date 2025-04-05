function addComment(taskId) {
    const comment = document.getElementById(`comment-${taskId}`).value;
    if (!comment.trim()) {
        alert('Please enter a comment');
        return;
    }

    fetch('../php/add_comment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `task_id=${taskId}&comment=${encodeURIComponent(comment)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadComments(taskId);
            document.getElementById(`comment-${taskId}`).value = '';
        } else {
            alert(data.message || 'Failed to add comment');
        }
    })
    .catch(error => console.error('Error:', error));
}

function loadComments(taskId) {
    fetch(`../php/get_comments.php?task_id=${taskId}`)
        .then(response => response.json())
        .then(data => {
            const commentsContainer = document.getElementById(`comments-${taskId}`);
            commentsContainer.innerHTML = data.comments.map(comment => `
                <div class="comment">
                    <div class="comment-header">
                        <span class="comment-author">${comment.email}</span>
                        <span class="comment-date">${comment.created_at}</span>
                    </div>
                    <div class="comment-body">${comment.comment}</div>
                </div>
            `).join('');
        })
        .catch(error => console.error('Error:', error));
}