<?php
require_once __DIR__ . '/../core/Model.php';
class PostModel extends Model {
    public function getAll() : array {
        return $this->fetchAll('SELECT * FROM posts');
    }

    public function getPostsByBoardId(int $boardid, int $limit = 10, int $offset = 0) : array {
        // Securely cast to int to prevent SQL injection since we are manually interpolating the LIMIT/OFFSET
        $limit = (int)$limit;
        $offset = (int)$offset;
        
        return $this->fetchAll("
            SELECT posts.*, users.username 
            FROM posts 
            LEFT JOIN users ON posts.userid = users.id 
            WHERE posts.boardid = :boardid AND posts.parentid IS NULL 
            ORDER BY posts.bumptimestamp DESC
            LIMIT $limit OFFSET $offset
        ", [
            'boardid' => $boardid
        ]);
    }

    public function countThreadsByBoardId(int $boardid) : int {
        $result = $this->fetch('SELECT COUNT(*) as total FROM posts WHERE boardid = :boardid AND parentid IS NULL', [
            'boardid' => $boardid
        ]);
        return (int)$result['total'];
    }
    public function getPostById(int $id) : array | false {
        return $this->fetch('
            SELECT posts.*, users.username 
            FROM posts 
            LEFT JOIN users ON posts.userid = users.id 
            WHERE posts.id = :id
        ', [
            'id' => $id
        ]);
    }
    // There's no reason to create a createReply function as its just a matter of the parentid being or not being a thing anyway :)
    // Thus we should just use createPost whenever we need to add a reply

    public function createPost(int $boardid, int | null $userid, string $content, int | null $parentid = null, string | null $attachment = null) : int|false {
        $now = date('Y-m-d H:i:s');
        $postId = $this->insertAndGetId('INSERT INTO posts (boardid, userid, content, attachment, timestamp, parentid, bumptimestamp) VALUES (:boardid, :userid, :content, :attachment, :timestamp, :parentid, :bumptimestamp)', [
            'boardid' => $boardid,
            'userid' => $userid,
            'content' => $content,
            'attachment' => $attachment,
            'timestamp' => $now,
            'parentid' => $parentid,
            'bumptimestamp' => $now
        ]);

        if ($postId && $parentid !== null) {
            $this->update('UPDATE posts SET bumptimestamp = :bumptimestamp WHERE id = :parentid', [
                'bumptimestamp' => $now,
                'parentid' => $parentid
            ]);
        }

        return $postId;
    }

    public function getReplies(int $parentid) : array {
        return $this->fetchAll('
            SELECT posts.*, users.username 
            FROM posts 
            LEFT JOIN users ON posts.userid = users.id 
            WHERE posts.parentid = :parentid
        ', [
            'parentid' => $parentid
        ]);
    }



}


?>