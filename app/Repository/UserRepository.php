<?php

namespace App\Repository;

use App\Contracts\UserInterface;
use App\Models\User;

class UserRepository implements UserInterface
{
    public function getByEmail(string $email): User
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }
}
