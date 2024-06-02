<?php

namespace Razan\belajar\php\mvc\Service;

use PHPUnit\Framework\TestCase;
use Razan\belajar\php\mvc\Config\Database;
use Razan\belajar\php\mvc\Repository\SessionRepository;
use Razan\belajar\php\mvc\Repository\UserRepository;
use Razan\belajar\php\mvc\Service\SessionService;
use Razan\belajar\php\mvc\Domain\User;
use Razan\belajar\php\mvc\Domain\Session;

function setcookie(string $name, string $value)
{
    echo "$name: $value";  
}

class SessionServiceTest extends TestCase
{
    private SessionService $sessionService;
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->id = "razan";
        $user->name = "Razan";
        $user->password = "rahasia";
        $this->userRepository->save($user);
    }

    public function testCreate()
    {
        $session = $this->sessionService->create("razan");

        $this->expectOutputRegex("[APP-SESSION: $session->id]");

        $result = $this->sessionRepository->findById($session->id);

        self::assertEquals("razan", $result->userId);
    }

    public function testDestroy(): void
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = "razan";

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->sessionService->destroy();

        $this->expectOutputRegex("[APP-SESSION: ]");

        $result = $this->sessionRepository->findById($session->id);
        self::assertNull($result);
    }

    public function testCurrent(): void
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = "razan";

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $user = $this->sessionService->current();

        self::assertEquals($session->userId, $user->id);
    }
}