<?php
session_start();

// Auto-detect base path for views and routing
define('BASE_URL', '/bord');


$config = require __DIR__ .'/config/database.php';
require_once __DIR__ .'/app/core/Database.php';
require_once __DIR__ .'/app/core/Router.php';
require_once __DIR__ .'/app/controllers/BoardController.php';
require_once __DIR__ .'/app/controllers/AuthController.php';
require_once __DIR__ .'/app/controllers/ProfileController.php';
require_once __DIR__ .'/app/controllers/AdminController.php';

$db = new Database($config['dsn'], $config['user'], $config['password']);
try {
    $conn = $db->connect();
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$router = new Router($conn, BASE_URL);
$routes = require __DIR__ .'/config/routes.php';
$routes($router);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
?>