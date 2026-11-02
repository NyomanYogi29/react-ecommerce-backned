<?php

namespace App\Repository;

use App\Contract\UserInterface\UserCreatorInterface;
use App\Contracts\UserInterface\UserFinderInterface;
use App\Models\User;
use Override;

class UserRepository implements UserCreatorInterface, UserFinderInterface {
    public function getByEmail(string $email): User
    {
        return User::where('email', $email)->first();
    }

    #[Override]
    public function create(array $data): User
    {
        return User::create($data);
    }
}