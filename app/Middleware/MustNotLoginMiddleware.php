<?php

namespace Razan\Middleware;

use Razan\App\View;
use Razan\Config\Database;
use Razan\Repository\SessionRepository;
use Razan\Repository\UserRepository;
use Razan\Service\SessionService;


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