<?php 
$title = "/{$board['shortname']}/ - {$board['longname']}";
require __DIR__ . '/header.php'; 
?>

<div class="row align-items-center mb-4">
    <div class="col text-center">
        <h1 class="display-5 text-danger fw-bold">/<?php echo htmlspecialchars($board['shortname']); ?>/ - <?php echo htmlspecialchars($board['longname']); ?></h1>
    </div>
</div>

<!-- Submission Form -->
<div class="row justify-content-center mb-5">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-secondary bg-dark-subtle">
            <div class="card-header bg-secondary text-white text-center fw-bold">
                Start a New Thread
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" action="/<?php echo htmlspecialchars($board['shortname']); ?>/submit">
                    <div class="mb-3">
                        <label class="form-label text-light">Attach Image:</label>
                        <input type="file" class="form-control bg-dark text-light border-secondary" name="attachment" accept="image/png, image/jpeg, image/gif">
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control bg-dark text-light border-secondary" name="content" rows="4" placeholder="What's on your mind?" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Post Thread</button>
                </form>
            </div>
        </div>
    </div>
</div>

<hr class="border-secondary opacity-50 mb-5">

<!-- Thread Listing -->
<?php if (empty($posts)): ?>
    <div class="alert alert-secondary text-center" role="alert">
        There are no threads on this board yet. Be the first!
    </div>
<?php else: ?>
    <?php foreach ($posts as $thread): ?>
        <div class="card mb-4 shadow-sm border-secondary bg-dark-subtle">
            <div class="card-body">
                <!-- Thread Header -->
                <div class="mb-2 text-muted small border-bottom border-secondary pb-2">
                    <span class="text-primary fw-bold">Anonymous</span> 
                    <?php echo $thread['bumptimestamp']; ?> 
                    <span class="ms-2">No. <strong><?php echo $thread['id']; ?></strong></span>
                    <a href="/<?php echo $board['shortname']; ?>/thread/<?php echo $thread['id']; ?>" class="float-end text-decoration-none">[Reply]</a>
                </div>
                
                <!-- Thread Content (Flex Layout for Image + Text) -->
                <div class="d-flex flex-column flex-md-row gap-3">
                    <?php if ($thread['attachment']): ?>
                        <div class="flex-shrink-0">
                            <a href="/public/uploads/<?php echo htmlspecialchars($thread['attachment']); ?>" target="_blank">
                                <img src="/public/uploads/<?php echo htmlspecialchars($thread['attachment']); ?>" class="img-thumbnail bg-dark border-secondary" style="max-width: 250px; height: auto;" alt="Attachment">
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="text-break text-light fs-5">
                        <?php echo $thread['parsed_content']; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>
