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
use Razan\belajar\php\mvc\Controller\HomeController;

Router::add('GET', '/', HomeController::class, 'index', []);

Router::run();