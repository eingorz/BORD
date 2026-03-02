<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/UserModel.php';

class AuthController extends Controller {
    private UserModel $UserModel;

    public function __construct(PDO $db) {
        parent::__construct($db);
        $this->UserModel = new UserModel($db);
    }

    public function showRegister() : void {
        $this->render('register');
    }

    public function processRegister() : void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $email = $_POST['email'] ?? '';

            if ($username && $password && $email) {
                // Check if user already exists
                $existingUser = $this->UserModel->getUserByUsername($username);
                if (!$existingUser) {
                    $this->UserModel->createUser($username, $password, $email);
                    // Redirect to login upon successful registration
                    $this->redirect('/login');
                    return;
                }
            }
        }
        // If validation fails or user exists, redirect back to the form
        $this->redirect('/register');
    }

    public function showLogin() : void {
        $this->render('login');
    }

    public function processLogin() : void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($username && $password) {
                $user = $this->UserModel->getUserByUsername($username);
                
                // Compare the plain text password hashed with MD5 against the stored hash
                if ($user && $user['passwordhash'] === md5($password)) {
                    // Populate session variables
                    $_SESSION['userid'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    
                    $this->redirect('/');
                    return;
                }
            }
        }
        
        // Invalid credentials
        $this->redirect('/login');
    }

    public function logout() : void {
        // Clear all session variables
        $_SESSION = [];
        
        // Destroy the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        
        // Redirect home
        $this->redirect('/');
    }
}
?>
