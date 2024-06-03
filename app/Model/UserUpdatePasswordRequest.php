<?php

namespace Razan\belajar\php\mvc\Model;

class UserUpdatePasswordRequest
{
    public ?string $id = null;
    public ?string $oldPassword = null;
    public ?string $newPassword = null;
}