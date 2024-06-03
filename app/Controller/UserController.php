<?php

namespace Razan\belajar\php\mvc\Controller;

use Razan\belajar\php\mvc\App\View;
use Razan\belajar\php\mvc\Config\Database;
use Razan\belajar\php\mvc\Service\UserService;
use Razan\belajar\php\mvc\Repository\UserRepository;
use Razan\belajar\php\mvc\Model\UserRegisterRequest;
use Razan\belajar\php\mvc\Exception\ValidationException;
use Razan\belajar\php\mvc\Model\UserLoginRequest;
use Razan\belajar\php\mvc\Service\SessionService;
use Razan\belajar\php\mvc\Repository\SessionRepository;
use Razan\belajar\php\mvc\Model\UserUpdateProfileRequest;

class UserController
{
    private UserService $userService;
    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);

        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }


    public function register()
    {
        View::render("User/register", [
            "title" => "Register New User"
        ]);
    }

    public function postRegister()
    {
        $request = new UserRegisterRequest();
        $request->id = $_POST['id'];
        $request->name = $_POST['name'];
        $request->password = $_POST['password'];

        try {
            $this->userService->register($request);
            View::redirect("/users/login");
        } catch (ValidationException $exception) {
            View::render("User/register", [
                "title" => "Register New User",
                "error" => $exception->getMessage()
            ]);
        }
    }

    public function login()
    {
        View::render("User/login", [
            "title" => "Login user"
        ]);
    }

    public function postLogin()
    {
        $request = new UserLoginRequest();
        $request->id = $_POST['id'];
        $request->password = $_POST['password'];

        try {
            $response = $this->userService->login($request);
            $this->sessionService->create($response->user->id);
            View::redirect("/");
        } catch (ValidationException $exception) {
            View::render("User/login", [
                "title" => "Login user",
                "error" => $exception->getMessage()
            ]);
        }
    }

    public function logout()
    {
        $this->sessionService->destroy();
        View::redirect("/");
    }

    public function updateProfile()
    {
        $user = $this->sessionService->current();
        View::render("User/profile", [
            "title" => "Update user profile",
            "user" => [
                "id" => $user->id,
                "name" => $user->name
            ]
        ]);
    }

    public function postUpdateProfile()
    {
        $user = $this->sessionService->current();

        $request = new UserUpdateProfileRequest();
        $request->id = $user->id;
        $request->name = $_POST['name'];

        try {
            $response = $this->userService->updateProfile($request);
            View::redirect("/");
        } catch (ValidationException $exception) {
            View::render("User/profile", [
                "title" => "Update user profile",
                "user" => [
                    "id" => $user->id,
                    "name" => $_POST['name']
                ],
                "error" => $exception->getMessage()
            ]);
        }
    }
}