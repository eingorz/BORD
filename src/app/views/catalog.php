<?php 
$title = "Catalog - /{$board['shortname']}/ - {$board['longname']}";
require __DIR__ . '/header.php'; 
?>

<div class="row align-items-center mb-4">
    <div class="col text-center">
        <h1 class="display-5 text-danger fw-bold">/<?php echo htmlspecialchars($board['shortname']); ?>/ - Catalog</h1>
        <p class="text-muted"><?php echo htmlspecialchars($board['longname']); ?></p>
        <div class="mt-3">
            <a href="<?= BASE_URL ?>/<?php echo htmlspecialchars($board['shortname']); ?>/" class="btn btn-outline-secondary btn-sm">Index View</a>
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
                    <?php if (isset($_SESSION['upload_error'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($_SESSION['upload_error']); unset($_SESSION['upload_error']); ?>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                bootstrap.Collapse.getOrCreateInstance(document.getElementById('newThreadForm')).show();
                            });
                        </script>
                    <?php endif; ?>
                    <form method="POST" enctype="multipart/form-data" action="<?= BASE_URL ?>/<?php echo htmlspecialchars($board['shortname']); ?>/submit">
                        <div class="mb-3">
                            <label class="form-label text-light fw-bold">Attach File:</label>
                            <input type="file" class="form-control bg-dark text-light border-secondary" name="attachment" accept="image/png, image/jpeg, image/gif, video/webm, video/mp4" onchange="if(this.files[0] && this.files[0].size > 10485760){ alert('File is too large! Maximum size is 10MB.'); this.value = ''; }">
                            <small class="text-muted d-block mt-1">Maximum upload limit: 10MB</small>
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
                    <div class="card-body p-2 d-flex flex-column align-items-center justify-content-start text-light">
                        <a href="<?= BASE_URL ?>/<?php echo $board['shortname']; ?>/thread/<?php echo $thread['id']; ?>" class="d-block w-100 mb-2">
                            <?php if ($thread['attachment']): ?>
                                <?php $attachExt = strtolower(pathinfo($thread['attachment'], PATHINFO_EXTENSION)); ?>
                                <?php if (in_array($attachExt, ['webm', 'mp4'])): ?>
                                    <video controls loop muted style="max-height: 250px; width: 100%; object-fit: contain;">
                                        <source src="<?= BASE_URL ?>/public/uploads/<?php echo htmlspecialchars($thread['attachment']); ?>" type="video/<?= $attachExt ?>">
                                    </video>
                                <?php else: ?>
                                    <img src="<?= BASE_URL ?>/public/uploads/<?php echo htmlspecialchars($thread['attachment']); ?>" class="img-fluid" style="max-height: 250px; object-fit: contain;" alt="Attachment">
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 100%; height: 200px;">
                                    <span class="text-muted small">No Image</span>
                                </div>
                            <?php endif; ?>
                        </a>
                        
                        <div class="small w-100 overflow-hidden text-start" style="max-height: 4.5em;">
                            <?php echo $thread['parsed_content']; ?>
                        </div>
                    </div>
                    <div class="card-footer bg-dark p-1 text-muted small">
                        <?php if (isset($thread['username']) && $thread['username'] !== null): ?>
                            <a href="<?= BASE_URL ?>/user/<?php echo urlencode($thread['username']); ?>" class="text-decoration-none profile-link text-primary fw-bold" data-username="<?php echo htmlspecialchars($thread['username']); ?>">
                                <?php echo htmlspecialchars($thread['username']); ?>
                            </a>
                        <?php else: ?>
                            <span class="fw-bold">Anonymous</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>
