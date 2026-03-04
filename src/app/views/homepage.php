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

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 row-cols-xl-5 g-4 mb-5">
    <?php foreach ($categorizedBoards as $categoryName => $boards): ?>
        <div class="col">
            <div class="card shadow-sm border-secondary bg-dark">
                <div class="card-header bg-secondary bg-opacity-25 text-white fw-bold border-secondary fs-5 text-center">
                    <?php echo htmlspecialchars($categoryName); ?>
                </div>
                <div class="card-body bg-dark-subtle p-3">
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($boards as $board): ?>
                            <li class="mb-2 text-truncate">
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

<?php require __DIR__ . '/footer.php'; ?>