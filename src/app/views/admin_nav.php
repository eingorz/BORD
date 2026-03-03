<div class="row mb-4">
    <div class="col">
        <h1 class="display-5 text-danger fw-bold">Admin Sandbox</h1>
        <p class="text-muted">Manage the Imageboard Backend</p>
    </div>
</div>

<ul class="nav nav-pills mb-4 border-bottom border-secondary pb-3">
    <li class="nav-item">
        <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] === '/admin') ? 'active text-light fw-bold' : 'text-primary'; ?>" href="/admin">Dashboard Overview</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] === '/admin/boards') ? 'active text-light fw-bold' : 'text-primary'; ?>" href="/admin/boards">Boards Manager</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] === '/admin/users') ? 'active text-light fw-bold' : 'text-primary'; ?>" href="/admin/users">Users & Bans</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] === '/admin/posts') ? 'active text-light fw-bold' : 'text-primary'; ?>" href="/admin/posts">Global Posts Tracker</a>
    </li>
</ul>
