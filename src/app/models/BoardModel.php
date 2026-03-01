<?php
require_once __DIR__ . '../core/Model.php';
class BoardModel extends Model {
    public function getAll() : array {
        return $this->fetchAll('SELECT * FROM boards');
    }

    public function getBoardById(int $id) : array | false {
        return $this->fetch('SELECT * FROM boards WHERE id = :id', [
            'id' => $id
        ]);
    }

    public function getBoardByShort(string $shortname) : array | false {
        return $this->fetch('SELECT * FROM boards WHERE shortname = :shortname', [
            'shortname' => $shortname
        ]);
    }

    public function createBoard(string $shortname, string $longname, int $categoryid) : int {
        return $this->insert('INSERT INTO boards (shortname, longname, categoryid) VALUES (:shortname, :longname, :categoryid)', [
            'shortname' => $shortname,
            'longname' => $longname,
            'categoryid' => $categoryid
        ]);
    }
}