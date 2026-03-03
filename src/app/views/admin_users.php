<?php 
$title = 'Manage Users - Admin Sandbox';
require __DIR__ . '/header.php'; 
require __DIR__ . '/admin_nav.php';
?>

<div class="row g-4">
    <!-- RIGHT COLUMN: User Management -->
    <div class="col-12">
        <div class="card shadow-sm border-secondary bg-dark-subtle h-100">
            <div class="card-header bg-danger bg-opacity-25 text-light border-secondary fw-bold">
                User Management
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover table-striped mb-0 text-light border-secondary">
                        <thead class="table-active border-secondary">
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td class="align-middle"><?php echo $u['id']; ?></td>
                                    <td class="align-middle fw-bold text-primary">
                                        <a href="/user/<?php echo urlencode($u['username']); ?>" class="text-decoration-none profile-link text-primary" data-username="<?php echo htmlspecialchars($u['username']); ?>">
                                            <?php echo htmlspecialchars($u['username']); ?>
                                        </a>
                                    </td>
                                    <td class="align-middle text-muted"><?php echo htmlspecialchars($u['e-mail']); ?></td>
                                    <td class="align-middle">
                                        <?php if ((int)$u['role'] === 2): ?>
                                            <span class="badge bg-danger">Admin</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary">User</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle">
                                        <?php if (isset($u['is_banned']) && $u['is_banned'] == 1): ?>
                                            <span class="badge bg-danger">Banned</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle text-end">
                                        <?php if ((int)$u['id'] !== (int)$_SESSION['userid']): ?>
                                            <form method="POST" action="/admin/user/<?php echo $u['id']; ?>/ban" class="m-0 p-0" onsubmit="return confirm('Toggle suspension for <?php echo htmlspecialchars($u['username']); ?>?');">
                                                <button type="submit" class="btn btn-sm <?php echo (isset($u['is_banned']) && $u['is_banned'] == 1) ? 'btn-outline-success' : 'btn-outline-danger'; ?>">
                                                    <?php echo (isset($u['is_banned']) && $u['is_banned'] == 1) ? 'Unban' : 'Ban'; ?>
                                                </button>
                                            </form>
                                        <?php endif; ?>
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
