<div class="comments-section">
    <h3>Comments</h3>
    <div id="comments-<?php echo $taskId; ?>" class="comments-container">
        <?php
        $comments_sql = "SELECT c.*, u.email 
                        FROM task_comments c 
                        JOIN users u ON c.user_id = u.id 
                        WHERE c.task_id = ? 
                        ORDER BY c.created_at DESC";
        $comments_stmt = $conn->prepare($comments_sql);
        $comments_stmt->bind_param("i", $taskId);
        $comments_stmt->execute();
        $comments = $comments_stmt->get_result();
        
        while ($comment = $comments->fetch_assoc()):
        ?>
        <div class="comment">
            <div class="comment-header">
                <span class="comment-author"><?php echo htmlspecialchars($comment['email']); ?></span>
                <span class="comment-date">
                    <?php echo date('M j, Y g:i A', strtotime($comment['created_at'])); ?>
                </span>
            </div>
            <div class="comment-body">
                <?php echo nl2br(htmlspecialchars($comment['comment'])); ?>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    
    <div class="comment-form">
        <textarea id="comment-<?php echo $taskId; ?>" 
                  placeholder="Add a comment..." class="comment-input"></textarea>
        <button onclick="addComment(<?php echo $taskId; ?>)" class="btn-primary">
            Add Comment
        </button>
    </div>
</div>