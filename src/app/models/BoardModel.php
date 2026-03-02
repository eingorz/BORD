<?php
require_once __DIR__ . '/../core/Model.php';

class BoardModel extends Model {
    public function getAll() : array {
        return $this->fetchAll('
            SELECT b.*, c.categoryname AS category_name 
            FROM boards b
            LEFT JOIN boardcategories c ON b.categoryid = c.id
            ORDER BY c.id ASC, b.shortname ASC
        ');
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