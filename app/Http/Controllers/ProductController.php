<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse;
    public function getAll(): JsonResponse {
        $products = Product::with(['category', 'user'])->get();
        return $this->ok(ProductResource::collection($products), "Successfully retrieved all the product");
    }

    public function get(string $id): JsonResponse {
        $product = Product::firstWhere($id)->with(['category', 'user']);
        return $this->ok(new ProductResource($product));
    } 
}
