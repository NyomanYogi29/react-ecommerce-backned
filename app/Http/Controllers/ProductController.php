<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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

    public function create(CreateProductRequest $request) {
        $validated = $request->validated();

        $sellerId = $request->user()->id;

        $categoryId = Category::where('name', $validated['category'])->firstOrFail();

        $newProduct = Product::create([
            'name' => $validated['name'],
            'user_id' => $sellerId,
            'category_id' => $categoryId->id,
            'price' => $validated['price'],
            'image' => $validated['image'],
            'stock' => $validated['stock'],
            'description' => $validated['description']
        ]);

        return $this->ok(
            new ProductResource($newProduct), 
            'Product created',
            201
        );
    }

    public function update(UpdateProductRequest $updateProduct, Product $product) {
        if($product->user_id !== Auth::id()) {
            return $this->forbidden('You are not authorized to modify this product.');
        } 
        
        $data = $updateProduct->validated();

        if(!empty($data['category'])) {
            $category = Category::where('name', $data['category'])->first();
            if ($category) {
                $data['category_id'] = $category->id;
            }
            unset($data['category']);
        }

        $product->update($data);

        return $this->ok(
            new ProductResource($product),
            'Product updated successfully',
        );
    }

    public function delete(Product $product) {
        if ($product->user_id !== Auth::id()) {
            return $this->forbidden('You are not authorized to modify this product');
        }
        $product->delete();
        return $this->deleted($product->id);
    }
}
