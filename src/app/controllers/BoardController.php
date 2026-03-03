<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/BoardModel.php';
require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class BoardController extends Controller {
    private BoardModel $BoardModel;
    private PostModel $PostModel;
    private UserModel $UserModel;

    public function __construct(PDO $db) {
        parent::__construct($db);
        $this->BoardModel = new BoardModel($db);
        $this->PostModel = new PostModel($db);
        $this->UserModel = new UserModel($db);
    }

    public function index() : void {
        $rawBoards = $this->BoardModel->getAll();
        
        $categorizedBoards = [];
        foreach ($rawBoards as $board) {
            $catName = $board['category_name'] ?? 'Uncategorized';
            $categorizedBoards[$catName][] = $board;
        }

        $this->render('homepage', [
            'categorizedBoards' => $categorizedBoards
        ]);
    }
    private function parseContent(string $content) : string {
        $escaped = htmlspecialchars($content);
        
        // Link to Posts (>>1234)
        $escaped = preg_replace('/&gt;&gt;([0-9]+)/', '<a href="#post-$1" class="text-info text-decoration-none fw-bold" onclick="addReply(event, \'$1\')">&gt;&gt;$1</a>', $escaped);
        
        // Match lines starting with >, capturing up to the end of the line (excluding \r)
        // This prevents the \r from being trapped inside the span, which caused nl2br to emit <br>\n<br>
        $escaped = preg_replace('/^(\s*&gt;(?!&gt;).*?)\r?$/m', '<span class="greentext">$1</span>', $escaped);
        
        return nl2br($escaped);
    }

    public function showBoard(string $shortname, string $page = '1') : void {
        $board = $this->BoardModel->getBoardByShort($shortname);
        if (!$board) {
            $this->redirect('/');
            return;
        }

        // Pagination settings
        $threadsPerPage = 10;
        $currentPage = max(1, (int)$page);
        $offset = ($currentPage - 1) * $threadsPerPage;

        // Fetch limited posts and total count
        $posts = $this->PostModel->getPostsByBoardId($board['id'], $threadsPerPage, $offset);
        $totalThreads = $this->PostModel->countThreadsByBoardId($board['id']);
        $totalPages = ceil($totalThreads / $threadsPerPage);
        
        foreach ($posts as &$post) {
            $post['parsed_content'] = $this->parseContent($post['content']);
        }
        unset($post); // Break the reference

        $this->render('board', [
            'board' => $board,
            'posts' => $posts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
    }

    public function showCatalog(string $shortname) : void {
        $board = $this->BoardModel->getBoardByShort($shortname);
        if (!$board) {
            $this->redirect('/');
            return;
        }

        // Fetch up to 100 threads for the catalog view without pagination
        $posts = $this->PostModel->getPostsByBoardId($board['id'], 100, 0); 
        
        foreach ($posts as &$post) {
            $preview = $post['content'];
            if (mb_strlen($preview) > 100) {
                $preview = mb_substr($preview, 0, 97) . '...';
            }
            $post['parsed_content'] = $this->parseContent($preview);
        }
        unset($post); 

        $this->render('catalog', [
            'board' => $board,
            'posts' => $posts
        ]);
    }

    public function showThread(string $shortname, string $id) : void {
        $post = $this->PostModel->getPostById((int)$id);
        if ($post) {
            $post['parsed_content'] = $this->parseContent($post['content']);
        }

        $replies = $this->PostModel->getReplies((int)$id);
        foreach ($replies as &$reply) {
            $reply['parsed_content'] = $this->parseContent($reply['content']);
        }
        unset($reply);

        // Pass both the shortname and the thread data to the view
        $this->render('thread', [
            'shortname' => $shortname,
            'post' => $post,
            'replies' => $replies
        ]);
    }

    private function handleUpload(array $file) : ?string {
        if ($file['error'] !== UPLOAD_ERR_OK) return null;
        
        $imageInfo = @getimagesize($file['tmp_name']);
        if ($imageInfo === false) return null;

        $mime = $imageInfo['mime'];
        $image = null;
        $extension = '';

        if ($mime === 'image/jpeg') {
            $image = @imagecreatefromjpeg($file['tmp_name']);
            $extension = '.jpg';
        } elseif ($mime === 'image/png') {
            $image = @imagecreatefrompng($file['tmp_name']);
            $extension = '.png';
        } elseif ($mime === 'image/gif') {
            $image = @imagecreatefromgif($file['tmp_name']);
            $extension = '.gif';
        }

        if (!$image) return null;

        // Exactly 16 characters for the DB schema limitation
        $filename = substr(bin2hex(random_bytes(6)), 0, 12) . $extension;
        $destination = __DIR__ . '/../../public/uploads/' . $filename;

        // GD Re-encoding intrinsically sanitizes Polyglot payloads (strips EXIF and appended data)
        if ($mime === 'image/jpeg') {
            @imagejpeg($image, $destination, 90);
        } elseif ($mime === 'image/png') {
            @imagepng($image, $destination);
        } elseif ($mime === 'image/gif') {
            @imagegif($image, $destination);
        }
        
        @imagedestroy($image);
        return file_exists($destination) ? $filename : null;
    }

    public function submitThread(string $shortname) : void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['content'])) {
                $board = $this->BoardModel->getBoardByShort($shortname);
                
                if ($board) {
                    $userid = $_SESSION['userid'] ?? null;
                    $attachment = null;
                    
                    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                        $attachment = $this->handleUpload($_FILES['attachment']);
                    }
                    
                    $this->PostModel->createPost($board['id'], $userid, $_POST['content'], null, $attachment);
                }
            }
        }
        
        $this->redirect('/' . $shortname . '/');
    }

    public function submitReply(string $shortname, string $id) : void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['content'])) {
                $board = $this->BoardModel->getBoardByShort($shortname);
                
                if ($board) {
                    $userid = $_SESSION['userid'] ?? null;
                    $attachment = null;
                    
                    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                        $attachment = $this->handleUpload($_FILES['attachment']);
                    }
                    
                    $this->PostModel->createPost($board['id'], $userid, $_POST['content'], (int)$id, $attachment);
                }
            }
        }
        
        $this->redirect('/' . $shortname . '/thread/' . $id);
    }
}


?>