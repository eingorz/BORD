<?php 
$title = "Thread #{$post['id']}";
require __DIR__ . '/header.php'; 
?>

<div class="mb-4">
    <a href="/<?php echo htmlspecialchars($shortname); ?>/" class="btn btn-outline-secondary btn-sm">&larr; Return to /<?php echo htmlspecialchars($shortname); ?>/</a>
</div>

<!-- Reply Form -->
<div class="row justify-content-center mb-5">
    <div class="col-md-8 col-lg-6">
        <div class="text-center mb-3">
            <button class="btn btn-secondary fw-bold border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#newReplyForm" aria-expanded="false" aria-controls="newReplyForm">
                Post a Reply
            </button>
        </div>
        <div class="collapse" id="newReplyForm">
            <div class="card shadow-sm border-secondary bg-dark-subtle">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="/<?php echo htmlspecialchars($shortname); ?>/thread/<?php echo $post['id']; ?>/reply">
                        <div class="mb-3">
                            <label class="form-label text-light fw-bold">Attach Image:</label>
                            <input type="file" class="form-control bg-dark text-light border-secondary" name="attachment" accept="image/png, image/jpeg, image/gif">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control bg-dark text-light border-secondary" id="replyTextarea" name="content" rows="4" placeholder="Type your reply here..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Post Reply</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<hr class="border-secondary opacity-50 mb-5">

<!-- Original Post -->
<div class="card border-primary mb-4 bg-dark shadow" id="post-<?php echo htmlspecialchars($post['id']); ?>">
    <div class="card-header bg-primary bg-opacity-25 text-light border-primary">
        <?php if (isset($post['username']) && $post['username'] !== null): ?>
            <a href="/user/<?php echo urlencode($post['username']); ?>" class="text-decoration-none">
                <span class="text-primary fw-bold"><?php echo htmlspecialchars($post['username']); ?></span>
            </a>
        <?php else: ?>
            <span class="text-primary fw-bold">Anonymous</span> 
        <?php endif; ?>
        <span class="text-muted ms-2"><?php echo htmlspecialchars($post['timestamp']); ?></span>
        <span class="float-end">No. <strong style="cursor: pointer;" onclick="addReply(event, '<?php echo htmlspecialchars($post['id']); ?>')"><?php echo htmlspecialchars($post['id']); ?></strong></span>
    </div>
    <div class="card-body">
        <div class="d-flex flex-column flex-md-row gap-3">
            <?php if ($post['attachment']): ?>
                <div class="flex-shrink-0">
                    <a href="/public/uploads/<?php echo htmlspecialchars($post['attachment']); ?>" target="_blank">
                        <img src="/public/uploads/<?php echo htmlspecialchars($post['attachment']); ?>" class="img-thumbnail bg-dark border-secondary" style="max-width: 300px; height: auto;" alt="Attachment">
                    </a>
                </div>
            <?php endif; ?>
            <div class="text-break text-light fs-5">
                <?php echo $post['parsed_content']; ?>
            </div>
        </div>
    </div>
</div>

<!-- Replies -->
<div class="ms-md-5 ps-md-4 border-start border-secondary border-3">
    <?php if (empty($replies)): ?>
        <div class="alert alert-secondary text-center ms-3" role="alert">
            No replies yet. Be the first to reply!
        </div>
    <?php else: ?>
        <?php foreach ($replies as $reply): ?>
            <div class="card mb-3 shadow-sm border-secondary bg-dark-subtle ms-3" id="post-<?php echo htmlspecialchars($reply['id']); ?>">
                <div class="card-body py-2">
                    <div class="mb-2 text-muted small border-bottom border-secondary pb-2">
                        <?php if (isset($reply['username']) && $reply['username'] !== null): ?>
                            <a href="/user/<?php echo urlencode($reply['username']); ?>" class="text-decoration-none">
                                <span class="text-info fw-bold"><?php echo htmlspecialchars($reply['username']); ?></span>
                            </a>
                        <?php else: ?>
                            <span class="text-info fw-bold">Anonymous</span> 
                        <?php endif; ?>
                        <?php echo htmlspecialchars($reply['timestamp']); ?> 
                        <span class="float-end">No. <strong style="cursor: pointer;" onclick="addReply(event, '<?php echo htmlspecialchars($reply['id']); ?>')"><?php echo htmlspecialchars($reply['id']); ?></strong></span>
                    </div>
                    
                    <div class="d-flex flex-column flex-md-row gap-3 mt-2">
                        <?php if ($reply['attachment']): ?>
                            <div class="flex-shrink-0">
                                <a href="/public/uploads/<?php echo htmlspecialchars($reply['attachment']); ?>" target="_blank">
                                    <img src="/public/uploads/<?php echo htmlspecialchars($reply['attachment']); ?>" class="img-thumbnail bg-dark border-secondary" style="max-width: 200px; height: auto;" alt="Attachment">
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="text-break text-light">
                            <?php echo $reply['parsed_content']; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function addReply(event, id) {
    // Prevent the default link jump if we clicked the >> anchor
    if (event.target.tagName === 'A') {
        event.preventDefault();
        window.location.hash = 'post-' + id;
    }
    
    const formCollapse = document.getElementById('newReplyForm');
    if (formCollapse && !formCollapse.classList.contains('show')) {
        // Expand the collapsible form before focusing using Bootstrap JS API
        bootstrap.Collapse.getOrCreateInstance(formCollapse).show();
    }
    
    const textarea = document.getElementById('replyTextarea');
    if (textarea) {
        textarea.value += '>>' + id + '\n';
        // Wait a tiny bit for the collapse animation to start before focusing
        setTimeout(() => textarea.focus(), 150);
    }
}
</script>

<?php require __DIR__ . '/footer.php'; ?>
