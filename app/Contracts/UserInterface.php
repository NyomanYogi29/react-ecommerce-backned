<?php

namespace App\Contracts;

use App\Models\User;

interface UserInterface {
    public function getByEmail(string $email): ?User;
    
    public function create(array $data): User;
}