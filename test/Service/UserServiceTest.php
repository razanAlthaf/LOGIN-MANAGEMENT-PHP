<?php

namespace Razan\belajar\php\mvc\Service;

use PHPUnit\Framework\TestCase;
use Razan\belajar\php\mvc\Config\Database;
use Razan\belajar\php\mvc\Repository\UserRepository;
use Razan\belajar\php\mvc\Model\UserRegisterRequest;
use Razan\belajar\php\mvc\Service\UserService;
use Razan\belajar\php\mvc\Exception\ValidationException;
use Razan\belajar\php\mvc\Domain\User;
use Razan\belajar\php\mvc\Model\UserLoginRequest;


class UserServiceTest extends TestCase
{
    private UserService $userService;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->userRepository = new UserRepository($connection);
        $this->userService = new UserService($this->userRepository);

        $this->userRepository->deleteAll();
    }

    public function testRegisterSuccess(): void
    {
        $request = new UserRegisterRequest();
        $request->id = 'razan';
        $request->name = 'Razan';
        $request->password = 'rahasia';

        $response = $this->userService->register($request);

        self::assertEquals($request->id, $response->user->id);
        self::assertEquals($request->name, $response->user->name);
        self::assertNotEquals($request->password, $response->user->password);

        self::assertTrue(password_verify($request->password, $response->user->password));
    }

    public function testRegisterFailed(): void
    {
        $this->expectException(ValidationException::class);
        $request = new UserRegisterRequest();
        $request->id = '';
        $request->name = '';
        $request->password = '';

        $this->userService->register($request);
    }

    public function testRegisterDuplicate(): void
    {
        $user = new User();
        $user->id = 'razan';
        $user->name = 'Razan';
        $user->password = 'rahasia';

        $this->userRepository->save($user);

        $this->expectException(ValidationException::class);

        $request = new UserRegisterRequest();
        $request->id = 'razan';
        $request->name = 'Razan';
        $request->password = 'rahasia';

        $this->userService->register($request);
    }

    public function testLoginNotfound(): void
    {
        $this->expectException(ValidationException::class);
        $request = new UserLoginRequest();
        $request->id = 'razan';
        $request->password = 'rahasia';

        $this->userService->login($request);
    }

    public function testLoginWrongPassword(): void
    {
        $user = new User();
        $user->id = 'razan';
        $user->name = 'Razan';
        $user->password = password_hash('rahasia', PASSWORD_BCRYPT);

        $this->expectException(ValidationException::class);

        $request = new UserLoginRequest();
        $request->id = 'razan';
        $request->password = 'salah';

        $this->userService->login($request);
    }

    public function testLoginSuccess(): void
    {
        $user = new User();
        $user->id = 'razan';
        $user->name = 'Razan';
        $user->password = password_hash('rahasia', PASSWORD_BCRYPT);

        $this->expectException(ValidationException::class);

        $request = new UserLoginRequest();
        $request->id = 'razan';
        $request->password = 'rahasia';

        $response = $this->userService->login($request);

        self::assertEquals($request->id, $response->user->id);
        self::assertTrue(password_verify($request->password, $response->user->password));

    }
    
}