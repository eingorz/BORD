<?php
require_once __DIR__ . '../core/Model.php';
class PostModel extends Model {
    public function getAll() : array {
        return $this->fetchAll('SELECT * FROM posts');
    }
    public function getPostById(int $id) : array | false {
        return $this->fetch('SELECT * FROM posts WHERE id = :id', [
            'id' => $id
        ]);
    }
    public function createPost(int $boardid, int $userid, string $content) : int {
        return $this->insert('INSERT INTO posts (boardid, userid, content) VALUES (:boardid, :userid, :content)', [
            'boardid' => $boardid,
            'userid' => $userid,
            'content' => $content
        ]);
    }
}


?>