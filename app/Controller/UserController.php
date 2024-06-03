<?php

namespace Razan\Controller;

use Razan\App\View;
use Razan\Config\Database;
use Razan\Service\UserService;
use Razan\Repository\UserRepository;
use Razan\Model\UserRegisterRequest;
use Razan\Exception\ValidationException;
use Razan\Model\UserLoginRequest;
use Razan\Service\SessionService;
use Razan\Repository\SessionRepository;
use Razan\Model\UserUpdateProfileRequest;
use Razan\Model\UserUpdatePasswordRequest;


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

    public function updatePassword()
    {
        $user = $this->sessionService->current();
        View::render("User/password", [
            "title" => "Update user password",
            "user" => [
                "id" => $user->id,
                "name" => $user->name
            ]
        ]);
    }

    public function postUpdatePassword()
    {
        $user = $this->sessionService->current();

        $request = new UserUpdatePasswordRequest();
        $request->id = $user->id;
        $request->oldPassword = $_POST['oldPassword'];
        $request->newPassword = $_POST['newPassword'];

        try {
            $this->userService->updatePassword($request);
            View::redirect("/");
        } catch (ValidationException $exception) {
            View::render("User/password", [
                "title" => "Update user password",
                "user" => [
                    "id" => $user->id,
                    "name" => $user->name
                ],
                "error" => $exception->getMessage()
            ]);
        }
    }
}