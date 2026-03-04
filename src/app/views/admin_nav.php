<div class="row mb-4">
    <div class="col">
        <h1 class="display-5 text-danger fw-bold">Admin Sandbox</h1>
        <p class="text-muted">Manage the Imageboard Backend</p>
    </div>
</div>

<ul class="nav nav-pills mb-4 border-bottom border-secondary pb-3">
    <li class="nav-item">
        <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] === BASE_URL . '/admin') ? 'active text-light fw-bold' : 'text-primary'; ?>" href="<?= BASE_URL ?>/admin">Dashboard Overview</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] === BASE_URL . '/admin/boards') ? 'active text-light fw-bold' : 'text-primary'; ?>" href="<?= BASE_URL ?>/admin/boards">Boards Manager</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] === BASE_URL . '/admin/users') ? 'active text-light fw-bold' : 'text-primary'; ?>" href="<?= BASE_URL ?>/admin/users">Users & Bans</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] === BASE_URL . '/admin/posts') ? 'active text-light fw-bold' : 'text-primary'; ?>" href="<?= BASE_URL ?>/admin/posts">Global Posts Tracker</a>
    </li>
</ul>
