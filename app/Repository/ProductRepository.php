<?php

namespace App\Repository;

use App\Contracts\ProductInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Override;

class ProductRepository implements ProductInterface{
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

    public function update(Product $product, array $data): void {
        $product->update($data);
    }

    public function delete(Product $product): void {
        $product->delete();
    }
}