<?php

namespace App\Repository;

use App\Contracts\ProductInterface\ProductCreator;
use App\Contracts\ProductInterface\ProductFinder;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Override;

class ProductRepository implements ProductFinder, ProductCreator {
    #[Override]
    public function getAll(): Collection
    {
        $products = Product::with(['category', 'user'])->get();
        return $products;
    }

    #[Override]
    public function create(array $data): Product
    {
        return Product::create($data);
    }
}