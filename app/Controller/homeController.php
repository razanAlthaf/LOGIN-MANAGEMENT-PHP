<?php 
namespace Razan\belajar\php\mvc\Controller;

use Razan\belajar\php\mvc\App\View;
class HomeController
{
    function index(): void
    {
        $model = [
            "title" => "Belajar php seru",
            "content" => "demi ukom kita ngebut belajar php"
        ];
        
        View::render("Home/index", $model);
    }
    function hello(): void
    {
        echo "HomeController.hello()";
    }
    function world(): void
    {
        echo "HomeController.world()";
    }
    function about(): void
    {
        echo "Hello, Razan althaf subrata";
    }
}

?>