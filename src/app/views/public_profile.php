<?php 
$title = htmlspecialchars($profileUser['username']) . " - BORD Profile";
require __DIR__ . '/header.php'; 
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6 col-lg-4">
        
        <div class="mb-4 text-center">
            <a href="<?= BASE_URL ?>/" class="btn btn-outline-secondary btn-sm">&larr; Return Home</a>
        </div>

        <div class="card shadow border-secondary bg-dark-subtle text-center">
            <div class="card-header bg-secondary text-white fw-bold d-flex justify-content-between align-items-center">
                <span>User Profile</span>
                <span class="badge <?php echo $profileUser['role'] == 2 ? 'bg-danger' : 'bg-primary'; ?>">
                    <?php echo $profileUser['role'] == 2 ? 'Admin' : 'User'; ?>
                </span>
            </div>
            <div class="card-body py-5">
                
                <?php if ($profileUser['pfpfilename']): ?>
                    <img src="<?= BASE_URL ?>/public/uploads/<?php echo htmlspecialchars($profileUser['pfpfilename']); ?>" class="rounded-circle border border-secondary shadow-sm mb-4" style="width: 180px; height: 180px; object-fit: cover;" alt="Profile Picture">
                <?php else: ?>
                    <div class="mx-auto rounded-circle bg-secondary bg-opacity-25 border border-secondary shadow-sm d-flex align-items-center justify-content-center mb-4" style="width: 180px; height: 180px;">
                        <span class="text-muted" style="font-size: 5rem;">?</span>
                    </div>
                <?php endif; ?>
                
                <h2 class="display-6 fw-bold text-light mb-1"><?php echo htmlspecialchars($profileUser['username']); ?></h2>
                
                <hr class="border-secondary opacity-50 my-4">
                
                <div class="text-start px-2">
                    <h5 class="text-muted fw-bold mb-3">Bio</h5>
                    <?php if (!empty($profileUser['bio'])): ?>
                        <p class="text-light" style="white-space: pre-wrap;"><?php echo htmlspecialchars($profileUser['bio']); ?></p>
                    <?php else: ?>
                        <p class="text-muted fst-italic">This user hasn't written a bio yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>
