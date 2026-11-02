<?php 

namespace App\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductInterface {
    public function get(Product $product): Product;
    
    public function create(array $data): Product;

    public function getAll(): Collection;

    public function update(Product $product, array $data): void;

    public function delete(Product $product): void;
}