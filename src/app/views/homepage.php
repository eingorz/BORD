<?php 
$title = "BORD - Homepage";
require __DIR__ . '/header.php'; 
?>

<div class="row mb-4">
    <div class="col-12 text-center">
        <h1 class="display-4 fw-bold text-primary">Welcome to BÖRD</h1>
        <p class="lead">Select a board from the catalog below to start posting.</p>
    </div>
</div>

<div class="card shadow-sm border-secondary bg-dark mb-5">
    <div class="card-header bg-secondary bg-opacity-25 text-white fw-bold border-secondary fs-5">
        Board Catalog
    </div>
    <div class="card-body p-4 text-center">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 row-cols-xl-5 g-4">
            <?php foreach ($categorizedBoards as $categoryName => $boards): ?>
                <div class="col">
                    <div class="card shadow-sm border-secondary bg-dark h-100">
                        <div class="card-header bg-secondary bg-opacity-25 text-white fw-bold border-secondary fs-5 text-center">
                            <?php echo htmlspecialchars($categoryName); ?>
                        </div>
                        <div class="card-body bg-dark-subtle p-3">
                            <ul class="list-unstyled mb-0">
                                <?php foreach ($boards as $board): ?>
                                    <li class="mb-2 text-truncate text-start">
                                        <a href="<?= BASE_URL ?>/<?php echo htmlspecialchars($board['shortname']); ?>/" class="text-decoration-none fw-bold" title="<?php echo htmlspecialchars($board['longname']); ?>">
                                            <span class="text-danger">/<?php echo htmlspecialchars($board['shortname']); ?>/</span> 
                                            <span class="text-light ms-1 small"><?php echo htmlspecialchars($board['longname']); ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php if (!empty($recentThreads)): ?>
<div class="card shadow-sm border-secondary bg-dark mb-5">
    <div class="card-header bg-secondary bg-opacity-25 text-white fw-bold border-secondary fs-5">
        Recently Active Threads
    </div>
    <div class="card-body p-4 text-center">
        <div class="row row-cols-2 row-cols-md-4 row-cols-xl-4 g-4">
            <?php foreach ($recentThreads as $thread): ?>
                <div class="col d-flex flex-column align-items-center">
                    <div class="fw-bold text-danger mb-2" style="font-size: 0.9em;">
                        <?php echo htmlspecialchars($thread['longname'] ?? $thread['shortname']); ?>
                    </div>
                    
                    <a href="<?= BASE_URL ?>/<?= htmlspecialchars($thread['shortname']) ?>/thread/<?= $thread['id'] ?>" class="d-block mb-2 text-decoration-none">
                        <?php if ($thread['attachment']): ?>
                            <img src="<?= BASE_URL ?>/public/uploads/<?= htmlspecialchars($thread['attachment']) ?>" class="img-fluid border border-secondary img-thumbnail" style="max-height: 180px; max-width: 100%; object-fit: contain; padding: 0; background-color: transparent;" alt="Attachment">
                        <?php else: ?>
                            <div class="border border-secondary d-flex align-items-center justify-content-center" style="width: 150px; height: 150px; background-color: #222;">
                                <span class="text-muted small">No Image</span>
                            </div>
                        <?php endif; ?>
                    </a>
                    
                    <div style="font-size: 0.85em; line-height: 1.3; max-width: 250px;">
                        <div class="fw-bold text-danger">/<?= htmlspecialchars($thread['shortname']) ?>/ - <?php echo isset($thread['username']) && $thread['username'] !== null ? htmlspecialchars($thread['username']) : 'Anonymous'; ?></div>
                        <div class="text-light overflow-hidden text-break" style="max-height: 3.9em;">
                            <?= $thread['parsed_content'] ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>