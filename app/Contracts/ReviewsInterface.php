<?php

namespace App\Contracts;

use App\Models\Product;
use App\Models\Reviews;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReviewsInterface
{
    public function create(array $data): Reviews;

    public function getWithPaginated(Product $product, int $per_page = 10): LengthAwarePaginator;
}
