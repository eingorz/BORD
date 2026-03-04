<?php 
$title = 'Global Posts - Admin Sandbox';
require __DIR__ . '/header.php'; 
require __DIR__ . '/admin_nav.php';
?>

<div class="row g-4">
    <!-- Posts List -->
    <div class="col-12">
        <div class="card shadow-sm border-secondary bg-dark-subtle h-100">
            <div class="card-header bg-warning bg-opacity-25 text-light border-secondary fw-bold">
                Global Post Tracker
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover table-striped mb-0 text-light border-secondary">
                        <thead class="table-active border-secondary">
                            <tr>
                                <th>ID</th>
                                <th>Board</th>
                                <th>Poster</th>
                                <th>Attachment</th>
                                <th>Content</th>
                                <th>Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $p): ?>
                                <tr>
                                    <td class="align-middle text-muted"><?php echo $p['id']; ?></td>
                                    <td class="align-middle fw-bold text-danger">/<?php echo htmlspecialchars($p['shortname'] ?? '?'); ?>/</td>
                                    
                                    <td class="align-middle fw-bold">
                                        <?php if ($p['username']): ?>
                                            <a href="<?= BASE_URL ?>/user/<?php echo urlencode($p['username']); ?>" class="text-decoration-none profile-link text-primary" data-username="<?php echo htmlspecialchars($p['username']); ?>">
                                                <?php echo htmlspecialchars($p['username']); ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Anonymous</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="align-middle text-muted text-center" style="width: 100px;">
                                        <?php if ($p['attachment']): ?>
                                            <a href="<?= BASE_URL ?>/public/uploads/<?php echo htmlspecialchars($p['attachment']); ?>" target="_blank" class="text-muted text-decoration-none">[View]</a>
                                        <?php else: ?>
                                            <span class="opacity-50">None</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="align-middle text-break text-truncate" style="max-width: 250px;">
                                        <?php echo htmlspecialchars($p['content']); ?>
                                    </td>
                                    
                                    <td class="align-middle text-muted small"><?php echo $p['timestamp']; ?></td>
                                    
                                    <td class="align-middle text-end">
                                        <div class="d-flex justify-content-end">
                                            <a href="<?= BASE_URL ?>/<?php echo htmlspecialchars($p['shortname']); ?>/thread/<?php echo $p['parentid'] ?? $p['id']; ?>#post-<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-secondary me-2" target="_blank">View Post</a>
                                            <form method="POST" action="<?= BASE_URL ?>/admin/post/<?php echo $p['id']; ?>/delete" class="m-0 p-0" onsubmit="return confirm('Permanently scrub post #<?php echo $p['id']; ?> from the database? This cannot be undone.');">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Scrub</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>
