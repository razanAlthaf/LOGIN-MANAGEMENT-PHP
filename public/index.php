<?php
//gunakan php -S localhost:8888
// if (isset($_SERVER["PATH_INFO"])) {
//     echo $_SERVER["PATH_INFO"];
// }else{
//     echo "tidak ada path info";
// }

// $path = '/index';

// //cek apakah server punya path info
// if (isset($_SERVER["PATH_INFO"])) {
//     //jika ada maka isi variable path dengan path info
//     $path = $_SERVER["PATH_INFO"];
// }

// require __DIR__ . '/../app/View' . $path . '.php';

require_once __DIR__ . "/../vendor/autoload.php";

use Razan\belajar\php\mvc\App\Router;
use Razan\belajar\php\mvc\Config\Database;
use Razan\belajar\php\mvc\Controller\HomeController;
use Razan\belajar\php\mvc\Controller\UserController;
use Razan\belajar\php\mvc\Middleware\MustNotLoginMiddleware;
use Razan\belajar\php\mvc\Middleware\MustLoginMiddleware;


Database::getConnection('prod');

// Home Controller
Router::add('GET', '/', HomeController::class, 'index', []);

// User Controller
Router::add('GET', '/users/register', UserController::class, 'register', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/register', UserController::class, 'postRegister', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/login', UserController::class, 'login', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/login', UserController::class, 'postLogin', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/logout', UserController::class, 'logout', [MustLoginMiddleware::class]);
Router::add('GET', '/users/profile', UserController::class, 'updateProfile', [MustLoginMiddleware::class]);
Router::add('POST', '/users/profile', UserController::class, 'postUpdateProfile', [MustLoginMiddleware::class]);

Router::run();