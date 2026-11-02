<?php

namespace App\Contracts;

use App\Models\Reviews;

interface ReviewsInterface {
    public function create(array $data): Reviews;

    public function getAll(): void;
}