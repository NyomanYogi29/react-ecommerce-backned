<?php

namespace App\Contracts\ProductInterface;

use App\Models\Product;

interface ProductCreator {
    public function create(array $data): Product;
}