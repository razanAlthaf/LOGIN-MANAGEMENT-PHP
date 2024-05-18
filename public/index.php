<?php
//gunakan php -S localhost:8888
// if (isset($_SERVER["PATH_INFO"])) {
//     echo $_SERVER["PATH_INFO"];
// }else{
//     echo "tidak ada path info";
// }

$path = '/index';

//cek apakah server punya path info
if (isset($_SERVER["PATH_INFO"])) {
    //jika ada maka isi variable path dengan path info
    $path = $_SERVER["PATH_INFO"];
}

require __DIR__ . '/../app/View' . $path . '.php';