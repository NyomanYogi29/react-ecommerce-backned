<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
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

    public function get(Product $product): JsonResponse {
        return $this->ok(new ProductResource($product->loadCount('reviews')));
    }

    public function create(CreateProductRequest $request, string $id): JsonResponse {
        $validated = $request->validated();

        $categoryId = Category::where('name', $validated['category'])->firstOrFail();

        $product = Product::create([
            'name' => $validated['name'],
            'user_id' => $id,
            'category_id' => $categoryId->id,
            'price' => $validated['price'],
            'image' => $validated['image'],
            'stock' => $validated['stock'],
            'description' => $validated['description']
        ]);

        return $this->ok(
            $product, 
            'Product created',
            201
        );
    }
}
