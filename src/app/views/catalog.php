<?php 
$title = "Catalog - /{$board['shortname']}/ - {$board['longname']}";
require __DIR__ . '/header.php'; 
?>

<div class="row align-items-center mb-4">
    <div class="col text-center">
        <h1 class="display-5 text-danger fw-bold">/<?php echo htmlspecialchars($board['shortname']); ?>/ - Catalog</h1>
        <p class="text-muted"><?php echo htmlspecialchars($board['longname']); ?></p>
        <div class="mt-3">
            <a href="/<?php echo htmlspecialchars($board['shortname']); ?>/" class="btn btn-outline-secondary btn-sm">Index View</a>
        </div>
    </div>
</div>

<!-- Submission Form -->
<div class="row justify-content-center mb-5">
    <div class="col-md-8 col-lg-6">
        <div class="text-center mb-3">
            <button class="btn btn-secondary fw-bold border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#newThreadForm" aria-expanded="false" aria-controls="newThreadForm">
                Start a New Thread
            </button>
        </div>
        <div class="collapse" id="newThreadForm">
            <div class="card shadow-sm border-secondary bg-dark-subtle">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="/<?php echo htmlspecialchars($board['shortname']); ?>/submit">
                        <div class="mb-3">
                            <label class="form-label text-light fw-bold">Attach Image:</label>
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
</div>

<hr class="border-secondary opacity-50 mb-5">

<!-- Catalog Grid -->
<?php if (empty($posts)): ?>
    <div class="alert alert-secondary text-center" role="alert">
        There are no threads on this board yet.
    </div>
<?php else: ?>
    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3 mb-5">
        <?php foreach ($posts as $thread): ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-secondary bg-dark-subtle text-center">
                    <a href="/<?php echo $board['shortname']; ?>/thread/<?php echo $thread['id']; ?>" class="text-decoration-none text-light d-flex flex-column h-100">
                        <div class="card-body p-2 d-flex flex-column align-items-center justify-content-start">
                            <?php if ($thread['attachment']): ?>
                                <img src="/public/uploads/<?php echo htmlspecialchars($thread['attachment']); ?>" class="img-fluid mb-2 border border-secondary" style="max-height: 250px; object-fit: contain;" alt="Attachment">
                            <?php else: ?>
                                <div class="bg-secondary bg-opacity-25 border border-secondary d-flex align-items-center justify-content-center mb-2" style="width: 100%; height: 200px;">
                                    <span class="text-muted small">No Image</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="small w-100 overflow-hidden text-start" style="max-height: 4.5em;">
                                <?php echo $thread['parsed_content']; ?>
                            </div>
                        </div>
                        <div class="card-footer bg-secondary p-1 border-top border-secondary text-muted small">
                            <?php echo htmlspecialchars($thread['username'] ?? 'Anonymous'); ?>
                        </div>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>
