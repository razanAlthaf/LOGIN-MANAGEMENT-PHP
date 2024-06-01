<?php

namespace Razan\belajar\php\mvc\Controller;

use Razan\belajar\php\mvc\App\View;
use Razan\belajar\php\mvc\Config\Database;
use Razan\belajar\php\mvc\Service\UserService;
use Razan\belajar\php\mvc\Repository\UserRepository;
use Razan\belajar\php\mvc\Model\UserRegisterRequest;
use Razan\belajar\php\mvc\Exception\ValidationException;

class UserController
{
    private UserService $userService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);
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
}