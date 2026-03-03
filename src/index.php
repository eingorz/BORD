<?php
session_start();
$config = require __DIR__ .'/config/database.php';
require_once __DIR__ .'/app/core/Database.php';
require_once __DIR__ .'/app/core/Router.php';
require_once __DIR__ .'/app/controllers/BoardController.php';
require_once __DIR__ .'/app/controllers/AuthController.php';
require_once __DIR__ .'/app/controllers/ProfileController.php';


$db = new Database($config['dsn'], $config['user'], $config['password']);
try {
    $conn = $db->connect();
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$router = new Router($conn);
$router->addRoute('GET', '/', 'BoardController', 'index');

// auth routes must be registered first or regex will steal
$router->addRoute('GET', '/register', 'AuthController', 'showRegister');
$router->addRoute('POST', '/register', 'AuthController', 'processRegister');
$router->addRoute('GET', '/login', 'AuthController', 'showLogin');
$router->addRoute('POST', '/login', 'AuthController', 'processLogin');
$router->addRoute('GET', '/logout', 'AuthController', 'logout');

$router->addRoute('GET', '/profile', 'ProfileController', 'showProfile');
$router->addRoute('POST', '/profile/update', 'ProfileController', 'updateProfile');
$router->addRoute('GET', '/user/{username}', 'ProfileController', 'showUser');

$router->addRoute('GET', '/{shortname}', 'BoardController', 'showBoard');
$router->addRoute('GET', '/{shortname}/page/{page}', 'BoardController', 'showBoard');
$router->addRoute('GET', '/{shortname}/catalog', 'BoardController', 'showCatalog');
$router->addRoute('POST', '/{shortname}/submit', 'BoardController', 'submitThread');
$router->addRoute('GET', '/{shortname}/thread/{id}', 'BoardController', 'showThread');
$router->addRoute('POST', '/{shortname}/thread/{id}/reply', 'BoardController', 'submitReply');
$router->addRoute('POST', '/{shortname}/post/{id}/delete', 'BoardController', 'deletePost');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
?>