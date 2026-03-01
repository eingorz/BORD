<?php
$config = require __DIR__ .'/config/database.php';
require_once __DIR__ .'/app/core/Database.php';
require_once __DIR__ .'/app/core/Router.php';
require_once __DIR__ .'/app/controllers/BoardController.php';


$db = new Database($config['dsn'], $config['user'], $config['password']);
try {
    $conn = $db->connect();
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$router = new Router($conn);
$router->addRoute('GET', '/', 'BoardController', 'index');
$router->addRoute('GET', '/{shortname}', 'BoardController', 'showBoard');
$router->addRoute('POST', '/{shortname}/submit', 'BoardController', 'submitThread');
$router->addRoute('GET', '/{shortname}/thread/{id}', 'BoardController', 'showThread');
$router->addRoute('POST', '/{shortname}/thread/{id}/reply', 'BoardController', 'submitReply');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
?>