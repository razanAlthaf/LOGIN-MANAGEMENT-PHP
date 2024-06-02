<?php

namespace Razan\belajar\php\mvc\Middleware;

use Razan\belajar\php\mvc\App\View;
use Razan\belajar\php\mvc\Config\Database;
use Razan\belajar\php\mvc\Repository\SessionRepository;
use Razan\belajar\php\mvc\Repository\UserRepository;
use Razan\belajar\php\mvc\Service\SessionService;


class MustNotLoginMiddleware implements Middleware
{
    private SessionService $sessionService;

    public function __construct()
    {
        $sessionRepository = new SessionRepository(Database::getConnection());
        $userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    function before(): void
    {
        $user = $this->sessionService->current();
        if ($user != null) {
            View::redirect("/");
        }
    }
}