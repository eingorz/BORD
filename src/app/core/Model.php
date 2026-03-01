<?php
abstract class Model {
    protected PDO $db;
    public function __construct(PDO $db) {
        $this->db = $db;
    }
    protected function query(string $sql, array $params = []): PDOStatement {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    protected function fetchAll(string $sql, array $params = []): array {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function fetch(string $sql, array $params = []): array | false {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    protected function fetchColumn(string $sql, array $params = []): mixed {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchColumn();
    }
    protected function insert(string $sql, array $params = []): int {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
    protected function insertAndGetId(string $sql, array $params = []): int {
        $stmt = $this->query($sql, $params);
        return $this->db->lastInsertId();
    }
    protected function update(string $sql, array $params = []): int {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
    protected function delete(string $sql, array $params = []): int {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
}