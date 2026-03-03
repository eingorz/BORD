<?php 
$title = "/{$board['shortname']}/ - {$board['longname']}";
require __DIR__ . '/header.php'; 
?>

<div class="row align-items-center mb-4">
    <div class="col text-center">
        <h1 class="display-5 text-danger fw-bold">/<?php echo htmlspecialchars($board['shortname']); ?>/ - <?php echo htmlspecialchars($board['longname']); ?></h1>
        <div class="mt-3">
            <a href="/<?php echo htmlspecialchars($board['shortname']); ?>/catalog" class="btn btn-outline-secondary btn-sm">Catalog View</a>
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
                    <?php if (isset($thread['username']) && $thread['username'] !== null): ?>
                        <a href="/user/<?php echo urlencode($thread['username']); ?>" class="text-decoration-none profile-link" data-username="<?php echo htmlspecialchars($thread['username']); ?>">
                            <span class="text-primary fw-bold"><?php echo htmlspecialchars($thread['username']); ?></span>
                        </a>
                    <?php else: ?>
                        <span class="text-primary fw-bold">Anonymous</span> 
                    <?php endif; ?>
                    <?php echo $thread['bumptimestamp']; ?> 
                    <span class="ms-2">No. <strong><a href="/<?php echo $board['shortname']; ?>/thread/<?php echo $thread['id']; ?>"><?php echo $thread['id']; ?></a></strong></span>
                    <a href="/<?php echo $board['shortname']; ?>/thread/<?php echo $thread['id']; ?>" class="float-end text-decoration-none ms-2">[Reply]</a>
                    
                    <?php
                    $isAuthor = isset($_SESSION['userid']) && $thread['userid'] !== null && $_SESSION['userid'] == $thread['userid'];
                    $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] == 2;
                    if ($isAuthor || $isAdmin):
                    ?>
                        <form id="delete-thread-<?php echo $thread['id']; ?>" method="POST" action="/<?php echo $board['shortname']; ?>/post/<?php echo $thread['id']; ?>/delete" class="d-none"></form>
                        <a href="#" class="float-end text-danger text-decoration-none ms-2" onclick="if(confirm('Delete this thread? This cannot be undone.')) document.getElementById('delete-thread-<?php echo $thread['id']; ?>').submit(); return false;">[Delete]</a>
                    <?php endif; ?>
                </div>
                
                <!-- Thread Content (Flex Layout for Image + Text) -->
                <div class="d-flex flex-column flex-md-row gap-3">
                    <?php if ($thread['attachment']): ?>
                        <div class="flex-shrink-0" style="max-width: 100%;">
                            <div class="small text-muted mb-1 text-truncate" style="max-width: 100%;">
                                File: <a href="/public/uploads/<?php echo htmlspecialchars($thread['attachment']); ?>" target="_blank" class="text-decoration-none text-info"><?php echo htmlspecialchars($thread['attachment']); ?></a>
                            </div>
                            <a href="/<?php echo $board['shortname']; ?>/thread/<?php echo $thread['id']; ?>">
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

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
    <nav aria-label="Board pagination" class="mt-5 mb-4">
        <ul class="pagination justify-content-center" data-bs-theme="dark">
            <!-- Previous Button -->
            <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link bg-dark text-light border-secondary" href="/<?php echo $board['shortname']; ?>/page/<?php echo $currentPage - 1; ?>">Previous</a>
            </li>
            
            <!-- Page Numbers -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($i === $currentPage) ? 'active' : ''; ?>">
                    <a class="page-link <?php echo ($i === $currentPage) ? 'bg-secondary border-secondary text-white' : 'bg-dark text-light border-secondary'; ?>" href="/<?php echo $board['shortname']; ?>/page/<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            
            <!-- Next Button -->
            <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                <a class="page-link bg-dark text-light border-secondary" href="/<?php echo $board['shortname']; ?>/page/<?php echo $currentPage + 1; ?>">Next</a>
            </li>
        </ul>
    </nav>
<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>
