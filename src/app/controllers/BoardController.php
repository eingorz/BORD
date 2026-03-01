<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/BoardModel.php';
require_once __DIR__ . '/../models/PostModel.php';

class BoardController extends Controller {
    private BoardModel $BoardModel;
    private PostModel $PostModel;

    public function __construct(PDO $db) {
        parent::__construct($db);
        $this->BoardModel = new BoardModel($db);
        $this->PostModel = new PostModel($db);
    }

    public function index() : void {
        $boards = $this->BoardModel->getAll();
        $this->render('homepage', [
            'boards' => $boards
        ]);
    }
    public function showBoard(string $shortname) : void {
        $board = $this->BoardModel->getBoardByShort($shortname);
        $posts = $this->PostModel->getPostsByBoardId($board['id']);
        $this->render('board', [
            'board' => $board,
            'posts' => $posts
        ]);
    }
    public function showThread(string $shortname, string $id) : void {
        $post = $this->PostModel->getPostById((int)$id);
        $replies = $this->PostModel->getReplies((int)$id);
        
        // Pass both the shortname and the thread data to the view
        $this->render('thread', [
            'shortname' => $shortname,
            'post' => $post,
            'replies' => $replies
        ]);
    }

    public function submitThread(string $shortname) : void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['content'])) {
                $board = $this->BoardModel->getBoardByShort($shortname);
                
                if ($board) {
                    // Create anonymous (null userid) root thread (null parentid)
                    $this->PostModel->createPost($board['id'], null, $_POST['content']);
                }
            }
        }
        
        // PRG Pattern: Redirect to GET catalog to prevent duplicate POST submissions
        $this->redirect('/' . $shortname . '/');
    }

    public function submitReply(string $shortname, string $id) : void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['content'])) {
                $board = $this->BoardModel->getBoardByShort($shortname);
                
                if ($board) {
                    // createPost automatically updates the parent thread's bumptimestamp
                    $this->PostModel->createPost($board['id'], null, $_POST['content'], (int)$id);
                }
            }
        }
        
        $this->redirect('/' . $shortname . '/thread/' . $id);
    }
}


?>