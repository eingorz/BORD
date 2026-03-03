<?php
require_once __DIR__ . '/../core/Model.php';

class UserModel extends Model {

    public function __construct(PDO $db) {
        parent::__construct($db);
        // Ensure the is_banned column exists for the moderation sandbox feature
        try {
            $this->db->exec("ALTER TABLE users ADD COLUMN is_banned TINYINT(1) DEFAULT 0");
        } catch (PDOException $e) {
            // Column already exists, safe to ignore
        }
    }
    
    public function createUser(string $username, string $password, string $email) : int|false {
        $passwordHash = md5($password);
        
        // role 1 = user
        return $this->insertAndGetId('INSERT INTO users (username, passwordhash, `e-mail`, role) VALUES (:username, :passwordhash, :email, 1)', [
            'username' => $username,
            'passwordhash' => $passwordHash,
            'email' => $email
        ]);
    }

    public function getUserByUsername(string $username) : array|false {
        return $this->fetch('SELECT * FROM users WHERE username = :username', [
            'username' => $username
        ]);
    }
    
    public function getUserById(int $id) : array|false {
        return $this->fetch('SELECT * FROM users WHERE id = :id', [
            'id' => $id
        ]);
    }

    public function getAllUsers() : array {
        return $this->fetchAll('SELECT id, username, `e-mail`, role, is_banned FROM users ORDER BY id DESC');
    }
    
    public function updateProfile(int $id, ?string $pfpfilename, int $postasanon, ?string $bio = null) : bool {
        return $this->update('UPDATE users SET pfpfilename = :pfpfilename, postasanon = :postasanon, bio = :bio WHERE id = :id', [
            'pfpfilename' => $pfpfilename,
            'postasanon' => $postasanon,
            'bio' => $bio,
            'id' => $id
        ]) !== false;
    }

    public function setBannedStatus(int $id, int $status) : bool {
        return $this->update('UPDATE users SET is_banned = :status WHERE id = :id', [
            'status' => $status,
            'id' => $id
        ]) !== false;
    }
}
?>
