<?php

namespace App\Contracts\UserInterface;

use App\Models\User;

interface UserFinderInterface {
    public function getByEmail(string $email): ?User;
}