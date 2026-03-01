<?php

abstract class Controller {
    protected PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }
    protected function render(string $view, array $data = []): void {
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }
    protected function redirect(string $path): void {
        header('Location: ' . $path);
        exit;
    }
    protected function back(): void {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

?>