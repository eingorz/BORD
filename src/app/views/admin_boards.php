<?php 
$title = 'Manage Boards - Admin Sandbox';
require __DIR__ . '/header.php'; 
require __DIR__ . '/admin_nav.php';
?>

<div class="row g-4">
    <!-- LEFT: Board Creation -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-secondary bg-dark-subtle mb-4">
            <div class="card-header bg-primary bg-opacity-25 text-light border-secondary fw-bold">
                Create New Board
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/admin/board/new">
                    <div class="mb-3">
                        <label class="form-label text-light fw-bold">Short Name (/v/, /b/)</label>
                        <input type="text" class="form-control bg-dark text-light border-secondary" name="shortname" maxlength="6" required placeholder="e.g. wg">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-light fw-bold">Long Name</label>
                        <input type="text" class="form-control bg-dark text-light border-secondary" name="longname" maxlength="64" required placeholder="e.g. Wallpapers">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-light fw-bold">Category</label>
                        <select class="form-select bg-dark text-light border-secondary" name="categoryid" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['categoryname']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Create Board</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-secondary bg-dark-subtle mb-4">
            <div class="card-header bg-info bg-opacity-25 text-light border-secondary fw-bold">
                Create New Category
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/admin/category/new">
                    <div class="mb-3">
                        <label class="form-label text-light fw-bold">Category Name</label>
                        <input type="text" class="form-control bg-dark text-light border-secondary" name="categoryname" maxlength="32" required placeholder="e.g. Video Games">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Create Category</button>
                </form>
            </div>
        </div>
    </div>

    <!-- RIGHT: Board List -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-secondary bg-dark-subtle mb-4">
            <div class="card-header bg-secondary bg-opacity-25 text-light border-secondary fw-bold">
                Active Boards
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover table-striped mb-0 text-light border-secondary">
                        <thead class="table-active border-secondary">
                            <tr>
                                <th>ID</th>
                                <th>Short Name</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($boards as $b): ?>
                                <tr>
                                    <td class="align-middle text-muted"><?php echo $b['id']; ?></td>
                                    <td class="align-middle fw-bold text-danger">/<?php echo htmlspecialchars($b['shortname']); ?>/</td>
                                    <td class="align-middle"><?php echo htmlspecialchars($b['longname']); ?></td>
                                    <td class="align-middle text-info"><?php echo htmlspecialchars($b['category_name'] ?? 'Unknown'); ?></td>
                                    <td class="align-middle text-end">
                                        <a href="<?= BASE_URL ?>/<?php echo htmlspecialchars($b['shortname']); ?>/" class="btn btn-sm btn-outline-secondary" target="_blank">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
        </div>

        <div class="card shadow-sm border-secondary bg-dark-subtle mb-4">
            <div class="card-header bg-success bg-opacity-25 text-light border-secondary fw-bold">
                Stored Categories
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover table-sm mb-0 text-light border-secondary">
                        <thead class="table-active border-secondary">
                            <tr>
                                <th>ID</th>
                                <th>Category Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td class="align-middle text-muted ps-3"><?php echo $cat['id']; ?></td>
                                    <td class="align-middle text-info"><?php echo htmlspecialchars($cat['categoryname']); ?></td>
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
