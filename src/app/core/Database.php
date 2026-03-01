<?php
class Database {
    private string $dsn;
    private string $user;
    private string $password;

    public function __construct(string $dsn, string $user, string $password) {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $password;
    }

    public function connect(): PDO {
        try {
            $conn = new PDO($this->dsn, $this->user, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }
}

?>