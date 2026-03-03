<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/UserModel.php';

class ProfileController extends Controller {
    private UserModel $UserModel;

    public function __construct(PDO $db) {
        parent::__construct($db);
        $this->UserModel = new UserModel($db);
    }

    public function showProfile() : void {
        if (!isset($_SESSION['userid'])) {
            $this->redirect('/login');
            return;
        }

        $user = $this->UserModel->getUserById($_SESSION['userid']);
        
        $this->render('profile', [
            'user' => $user
        ]);
    }
    
    public function showUser(string $username) : void {
        $profileUser = $this->UserModel->getUserByUsername($username);
        
        if (!$profileUser) {
            $this->redirect('/');
            return;
        }
        
        $this->render('public_profile', [
            'profileUser' => $profileUser
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

    private function handleBase64Upload(string $base64) : ?string {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64, $matches)) {
            $type = strtolower($matches[1]);
            $base64Data = substr($base64, strpos($base64, ',') + 1);
            $decodedData = base64_decode($base64Data);

            if ($decodedData === false) return null;

            $img = @imagecreatefromstring($decodedData);
            if (!$img) return null;

            $extension = '';
            if ($type === 'jpeg' || $type === 'jpg') {
                $extension = '.jpg';
            } elseif ($type === 'png') {
                $extension = '.png';
            } elseif ($type === 'gif') {
                $extension = '.gif';
            } else {
                @imagedestroy($img);
                return null;
            }

            $filename = substr(bin2hex(random_bytes(6)), 0, 12) . $extension;
            $destination = __DIR__ . '/../../public/uploads/' . $filename;

            if ($extension === '.jpg') {
                @imagejpeg($img, $destination, 90);
            } elseif ($extension === '.png') {
                @imagepng($img, $destination);
            } elseif ($extension === '.gif') {
                @imagegif($img, $destination);
            }
            
            @imagedestroy($img);
            return file_exists($destination) ? $filename : null;
        }
        return null;
    }

    public function updateProfile() : void {
        if (!isset($_SESSION['userid']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
            return;
        }

        $user = $this->UserModel->getUserById($_SESSION['userid']);
        if (!$user) {
            $this->redirect('/logout');
            return;
        }

        $postasanon = isset($_POST['postasanon']) ? 1 : 0;
        $pfpfilename = $user['pfpfilename']; // Default to keeping the old one

        // Handle PFP Delete
        if (isset($_POST['remove_pfp']) && $_POST['remove_pfp'] === '1') {
            $pfpfilename = null;
        } 
        // Handle Cropped PFP
        elseif (!empty($_POST['cropped_pfp'])) {
            $uploadedFile = $this->handleBase64Upload($_POST['cropped_pfp']);
            if ($uploadedFile) {
                $pfpfilename = $uploadedFile;
            }
        }
        // Handle standard fallback PFP Upload
        elseif (isset($_FILES['pfp']) && $_FILES['pfp']['error'] === UPLOAD_ERR_OK) {
            $uploadedFile = $this->handleUpload($_FILES['pfp']);
            if ($uploadedFile) {
                $pfpfilename = $uploadedFile;
            }
        }

        $bio = $_POST['bio'] ?? null;

        $this->UserModel->updateProfile($user['id'], $pfpfilename, $postasanon, $bio);

        // Redirect back to profile to show changes
        $this->redirect('/profile');
    }
}
?>
