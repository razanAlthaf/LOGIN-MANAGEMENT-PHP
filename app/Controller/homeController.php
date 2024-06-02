<?php 
namespace Razan\belajar\php\mvc\Controller;

use Razan\belajar\php\mvc\App\View;
use Razan\belajar\php\mvc\Config\Database;
use Razan\belajar\php\mvc\Service\SessionService;
use Razan\belajar\php\mvc\Repository\SessionRepository;
use Razan\belajar\php\mvc\Repository\UserRepository;

class HomeController
{
    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $sessionRepository = new SessionRepository($connection);
        $userRepository = new UserRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    function index()
    {
        $user = $this->sessionService->current();
        if ($user == null) {
            View::render("Home/index", [
                "title" => "PHP Login Management"
            ]);
        }else{
            View::render("Home/dashboard", [
                "title" => "Dashboard",
                "user" => [
                    "name" => $user->name
                ]
            ]);
        }
        
    }
}

?>