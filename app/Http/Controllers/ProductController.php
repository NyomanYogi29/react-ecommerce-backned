<?php

namespace App\Http\Controllers;

use App\Contracts\CategoryInterface;
use App\Contracts\ProductInterface;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(
        private CategoryInterface $categoryInterface,
        private ProductInterface $productInterface
    ) {}
    public function getAll(): JsonResponse {
        $products = $this->productInterface->getAll();
        return $this->ok(ProductResource::collection($products), "Successfully retrieved all the product");
    }

    public function get(Product $product): JsonResponse {
        return $this->ok(new ProductResource($product->loadCount('reviews')));
    }

    public function create(CreateProductRequest $request) {
        $validated = $request->validated();

        $sellerId = $request->user()->id;

        $categoryId = $this->categoryInterface->getIdByName($validated['category']);

        $newProduct = $this->productInterface->create([
            'name' => $validated['name'],
            'user_id' => $sellerId,
            'category_id' => $categoryId,
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

    public function update(UpdateProductRequest $updateProduct, Product $product): JsonResponse {
        if($product->user_id !== Auth::id()) {
            return $this->forbidden('You are not authorized to modify this product.');
        } 
        
        $data = $updateProduct->validated();

        if(!empty($data['category'])) {
            $category = $this->categoryInterface->getIdByName($data['name']);
            if ($category) {
                $data['category_id'] = $category;
            }
            unset($data['category']);
        }

        $this->productInterface->update($product, $data);

        return $this->ok(
            new ProductResource($product),
            'Product updated successfully',
        );
    }

    public function delete(Product $product): JsonResponse {
        if ($product->user_id !== Auth::id()) {
            return $this->forbidden('You are not authorized to modify this product');
        }
        $this->productInterface->delete($product); 
        return $this->deleted($product->id);
    }
}
