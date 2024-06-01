<?php 
namespace Razan\belajar\php\mvc\Controller;

use Razan\belajar\php\mvc\App\View;
class HomeController
{
    function index()
    {
        View::render("Home/index", [
            "title" => "PHP Login Management"
        ]);
    }
}

?>