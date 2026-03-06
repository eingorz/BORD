<?php 
$title = "Register - BORD";
require __DIR__ . '/header.php'; 
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm border-secondary bg-dark text-light">
            <div class="card-header bg-secondary text-white fw-bold text-center">
                <h2>Register</h2>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['register_error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($_SESSION['register_error']); unset($_SESSION['register_error']); ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="<?= BASE_URL ?>/register">
                    <div class="mb-3">
                        <label class="form-label">Username:</label>
                        <input type="text" class="form-control bg-dark-subtle text-light border-secondary" name="username" required maxlength="32">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email:</label>
                        <input type="email" class="form-control bg-dark-subtle text-light border-secondary" name="email" required maxlength="64">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Password:</label>
                        <input type="password" class="form-control bg-dark-subtle text-light border-secondary" name="password" required minlength="8" pattern="(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}">
                        <div class="form-text text-muted">Must be at least 8 characters, include 1 number, and 1 special character.</div>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100 fw-bold">Create Account</button>
                    
                    <div class="mt-3 text-center">
                        <small class="text-muted">Already have an account? <a href="<?= BASE_URL ?>/login" class="text-info">Login</a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>
