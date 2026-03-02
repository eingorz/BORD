<?php
require_once __DIR__ . '/../core/Model.php';

class UserModel extends Model {
    
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
}
?>
