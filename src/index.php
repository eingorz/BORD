<?php
require_once __DIR__ .'/app/core/Database.php';
$config = require __DIR__ .'/config/database.php';

$db = new Database($config['dsn'], $config['user'], $config['password']);
try {
    $conn = $db->connect();
    echo "Connected to database";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>