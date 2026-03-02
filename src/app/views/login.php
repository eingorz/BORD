<?php 
$title = "Login - BORD";
require __DIR__ . '/header.php'; 
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm border-secondary bg-dark text-light">
            <div class="card-header bg-secondary text-white fw-bold text-center">
                <h2>Login</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="/login">
                    <div class="mb-3">
                        <label class="form-label">Username:</label>
                        <input type="text" class="form-control bg-dark-subtle text-light border-secondary" name="username" required maxlength="32">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Password:</label>
                        <input type="password" class="form-control bg-dark-subtle text-light border-secondary" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Login</button>
                    
                    <div class="mt-3 text-center">
                        <small class="text-muted">Don't have an account? <a href="/register" class="text-info">Register</a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>
