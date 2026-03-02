<?php 
$title = "Thread #{$post['id']}";
require __DIR__ . '/header.php'; 
?>

<div class="mb-4">
    <a href="/<?php echo htmlspecialchars($shortname); ?>/" class="btn btn-outline-secondary btn-sm">&larr; Return to /<?php echo htmlspecialchars($shortname); ?>/</a>
</div>

<!-- Reply Form -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card shadow-sm border-secondary bg-dark-subtle">
            <div class="card-header bg-secondary text-white fw-bold">
                Post a Reply
            </div>
            <div class="card-body py-2">
                <form method="POST" enctype="multipart/form-data" action="/<?php echo htmlspecialchars($shortname); ?>/thread/<?php echo $post['id']; ?>/reply" class="d-flex flex-column flex-md-row gap-3 align-items-center">
                    <input type="file" class="form-control form-control-sm bg-dark text-light border-secondary w-auto" name="attachment" accept="image/png, image/jpeg, image/gif">
                    <textarea class="form-control form-control-sm bg-dark text-light border-secondary flex-grow-1" name="content" rows="1" placeholder="Type your reply here..." required></textarea>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold px-4">Reply</button>
                </form>
            </div>
        </div>
    </div>
</div>

<hr class="border-secondary opacity-50 mb-5">

<!-- Original Post -->
<div class="card border-primary mb-4 bg-dark shadow">
    <div class="card-header bg-primary bg-opacity-25 text-light border-primary">
        <span class="text-primary fw-bold">Original Poster</span> 
        <span class="text-muted ms-2"><?php echo htmlspecialchars($post['timestamp']); ?></span>
        <span class="float-end">No. <strong><?php echo htmlspecialchars($post['id']); ?></strong></span>
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
            <div class="card mb-3 shadow-sm border-secondary bg-dark-subtle ms-3">
                <div class="card-body py-2">
                    <div class="mb-2 text-muted small border-bottom border-secondary pb-2">
                        <span class="text-info fw-bold">Anonymous</span> 
                        <?php echo htmlspecialchars($reply['timestamp']); ?> 
                        <span class="float-end">No. <strong><?php echo htmlspecialchars($reply['id']); ?></strong></span>
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

<?php require __DIR__ . '/footer.php'; ?>
