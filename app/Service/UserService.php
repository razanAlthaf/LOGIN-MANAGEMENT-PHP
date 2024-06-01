<?php

namespace Razan\belajar\php\mvc\Service;

use Razan\belajar\php\mvc\Model\UserRegisterRequest;
use Razan\belajar\php\mvc\Model\UserRegisterResponse;
use Razan\belajar\php\mvc\Repository\UserRepository;
use Razan\belajar\php\mvc\Exception\ValidationException;
use Razan\belajar\php\mvc\Domain\User;
use Razan\belajar\php\mvc\Config\Database;


class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(UserRegisterRequest $request) : UserRegisterResponse
    {
        $this->validateUserRegistrationRequest($request);

        try {
            Database::beginTransaction();
            $user = $this->userRepository->findById($request->id);
            if($user != null){
                throw new ValidationException("User Id Already Exist");
            }
    
            $user = new User();
            $user->id = $request->id;
            $user->name = $request->name;
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);
    
            $this->userRepository->save($user);
    
            $response = new UserRegisterResponse();
            $response->user = $user;
            Database::commitTransaction();
            return $response;
            } catch (\Exception $exception) {
                Database::rollbackTransaction();
                throw $exception;
        }
    }

    private function validateUserRegistrationRequest(UserRegisterRequest $request){
        if($request->id == null || $request->name == null || $request->password == null ||
        trim($request->id) == "" || trim($request->name) == "" || trim($request->password) == ""){
            throw new ValidationException("Id, Name, Password Can't Blank");
        }
    }
}