<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/BoardModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class AdminController extends Controller {
    private BoardModel $BoardModel;
    private UserModel $UserModel;
    private PostModel $PostModel;

    public function __construct(PDO $db) {
        parent::__construct($db);
        $this->BoardModel = new BoardModel($db);
        $this->UserModel = new UserModel($db);
        $this->PostModel = new PostModel($db);
        
        // Strict Authorization: Must be an Admin (Role = 2)
        if (!isset($_SESSION['role']) || (int)$_SESSION['role'] !== 2) {
            $this->redirect('/');
            exit;
        }
    }

    public function showDashboard() : void {
        $weeklyData = $this->PostModel->getPostCountLastWeek();
        $this->render('admin_dashboard', ['weeklyData' => $weeklyData]);
    }

    public function showBoards() : void {
        $boards = $this->BoardModel->getAll();
        $categories = $this->BoardModel->getAllCategories();
        $this->render('admin_boards', [
            'boards' => $boards,
            'categories' => $categories
        ]);
    }

    public function showUsers() : void {
        $users = $this->UserModel->getAllUsers();
        $this->render('admin_users', ['users' => $users]);
    }

    public function showPosts() : void {
        $posts = $this->PostModel->getAllWithDetails();
        $this->render('admin_posts', ['posts' => $posts]);
    }

    public function createBoard() : void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $shortname = trim($_POST['shortname'] ?? '');
            $longname = trim($_POST['longname'] ?? '');
            $categoryid = (int)($_POST['categoryid'] ?? 1);

            if (!empty($shortname) && !empty($longname)) {
                $this->BoardModel->createBoard($shortname, $longname, $categoryid);
            }
        }
        $this->redirect('/admin/boards');
    }

    public function createCategory() : void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryname = trim($_POST['categoryname'] ?? '');
            if (!empty($categoryname)) {
                $this->BoardModel->createCategory($categoryname);
            }
        }
        $this->redirect('/admin/boards');
    }

    public function toggleBan(string $id) : void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->UserModel->getUserById((int)$id);
            if ($user && (int)$user['id'] !== (int)$_SESSION['userid']) {
                $newStatus = (isset($user['is_banned']) && $user['is_banned'] == 1) ? 0 : 1;
                $this->UserModel->setBannedStatus((int)$id, $newStatus);
            }
        }
        $this->redirect('/admin/users');
    }

    public function deletePost(string $id) : void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->PostModel->deletePost((int)$id);
        }
        $this->redirect('/admin/posts');
    }
}
?>
