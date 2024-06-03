<?php 
namespace Razan\Controller;

use Razan\App\View;
use Razan\Config\Database;
use Razan\Service\SessionService;
use Razan\Repository\SessionRepository;
use Razan\Repository\UserRepository;

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