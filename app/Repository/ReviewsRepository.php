<?php

namespace App\Repository;

use App\Contracts\ReviewsInterface;
use App\Models\Reviews;
use Override;

class ReviewsRepository implements ReviewsInterface {
    #[Override]
    public function create(array $data): Reviews
    {
        return Reviews::create($data);
    }

    #[Override]
    public function getAll(): void
    {
        throw new \Exception('Not implemented');
    }
}