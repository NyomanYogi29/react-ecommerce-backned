<?php

namespace App\Http\Controllers;

use App\Contracts\CategoryInterface;
use App\Contracts\ProductInterface;
use App\Contracts\ReviewsInterface;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ReviewsResource;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(
        private CategoryInterface $categoryInterface,
        private ProductInterface $productInterface,
        private ReviewsInterface $reviewsInterface
    ) {}
    public function getAll(): JsonResponse {
        $products = $this->productInterface->getAll();
        return $this->ok(ProductResource::collection($products), "Successfully retrieved all the product");
    }

    public function get(Request $request, Product $product): JsonResponse {
        $product = $this->productInterface->get($product);

        $perPage = $request->integer('per_page', 10);

        $paginatedReviews = $this->reviewsInterface->getWithPaginated($product, $perPage);

        return $this->ok(
            [
                'product' => new ProductResource($product),
                'reviews' => ReviewsResource::collection($paginatedReviews)->response()->getData(true)
            ], 'Product detail retrieve successfully'
        );
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
