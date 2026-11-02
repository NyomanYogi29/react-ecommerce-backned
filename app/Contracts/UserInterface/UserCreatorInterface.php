<?php

namespace App\Contract\UserInterface;

use App\Models\User;

interface UserCreatorInterface {
    public function create(array $data): User;
}