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

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
    <?php foreach ($categorizedBoards as $categoryName => $boards): ?>
        <div class="col">
            <div class="card shadow-sm border-secondary bg-dark h-100">
                <div class="card-header bg-secondary bg-opacity-25 text-white fw-bold border-secondary fs-5 text-center">
                    <?php echo htmlspecialchars($categoryName); ?>
                </div>
                <div class="card-body bg-dark-subtle p-3">
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($boards as $board): ?>
                            <div class="card shadow-sm border-secondary bg-dark">
                                <div class="card-body p-3">
                                    <h3 class="card-title text-danger fs-5 mb-1">/<?php echo htmlspecialchars($board['shortname']); ?>/</h3>
                                    <p class="card-text text-light small mb-2"><?php echo htmlspecialchars($board['longname']); ?></p>
                                    <a href="/<?php echo htmlspecialchars($board['shortname']); ?>/" class="btn btn-outline-primary btn-sm w-100 stretched-link">Enter</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require __DIR__ . '/footer.php'; ?>