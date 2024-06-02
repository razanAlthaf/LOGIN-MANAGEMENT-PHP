<?php

namespace Razan\belajar\php\mvc\Repository;

use PHPUnit\Framework\TestCase;
use Razan\belajar\php\mvc\Config\Database;
use Razan\belajar\php\mvc\Domain\Session;
use Razan\belajar\php\mvc\Domain\User;


class SessionRepositoryTest extends TestCase
{
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    public function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection());

        $this->sessionRepository = new SessionRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->id = 'razan';
        $user->name = 'Razan';
        $user->password = 'razan';
        $this->userRepository->save($user);
    }

    public function testSaveSuccess(): void
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = 'razan';

        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findById($session->id);
        self::assertEquals($session->id, $result->id);
        self::assertEquals($session->userId, $result->userId);
    }

    public function testDeleteByIdSuccess(): void
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = 'razan';

        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findById($session->id);
        self::assertEquals($session->id, $result->id);
        self::assertEquals($session->userId, $result->userId);

        $this->sessionRepository->deleteById($session->id);

        $result = $this->sessionRepository->findById($session->id);
        self::assertNull($result);
    }

    public function testFindByIdNotFound(): void
    {
        $result = $this->sessionRepository->findById('notfound');
        self::assertNull($result);
    }
}