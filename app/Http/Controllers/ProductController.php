<?php

namespace App\Http\Controllers;

use App\Contracts\CategoryInterface;
use App\Contracts\FileUploadInterface;
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
        private ReviewsInterface $reviewsInterface,
        private FileUploadInterface $fileUploadInterface
    ) {}

    public function getAll(): JsonResponse
    {
        $products = $this->productInterface->getAll();

        return $this->ok(ProductResource::collection($products), 'Successfully retrieved all the product');
    }

    public function get(Request $request, Product $product): JsonResponse
    {
        $product = $this->productInterface->get($product);

        $perPage = $request->integer('per_page', 10);

        $paginatedReviews = $this->reviewsInterface->getWithPaginated($product, $perPage);

        return $this->ok(
            [
                'product' => new ProductResource($product),
                'reviews' => ReviewsResource::collection($paginatedReviews)->response()->getData(true),
            ],
            'Product detail retrieve successfully'
        );
    }

    public function create(CreateProductRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $sellerId = $request->user()->id;

        $categoryId = $this->categoryInterface->getIdByName($validated['category']);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = $this->fileUploadInterface->upload($request->file('image'), 'products');
        }

        $newProduct = $this->productInterface->create([
            'name' => $validated['name'],
            'user_id' => $sellerId,
            'category_id' => $categoryId,
            'price' => $validated['price'],
            'image' => $imageUrl,
            'stock' => $validated['stock'] ?? 0,
            'description' => $validated['description'] ?? null,
        ]);

        return $this->ok(
            new ProductResource($newProduct),
            'Product created',
            201
        );
    }

    public function update(UpdateProductRequest $updateProduct, Product $product): JsonResponse
    {
        if ($product->user_id !== Auth::id()) {
            return $this->forbidden('You are not authorized to modify this product.');
        }

        $data = $updateProduct->validated();

        if (! empty($data['category'])) {
            $category = $this->categoryInterface->getIdByName($data['category']);
            if ($category) {
                $data['category_id'] = $category;
            }
            unset($data['category']);
        }

        if ($updateProduct->hasFile('image')) {
            if ($product->image) {
                $this->fileUploadInterface->delete($product->image);
            }
            $data['image'] = $this->fileUploadInterface->upload($updateProduct->file('image'), 'products');
        } else {
            unset($data['image']);
        }

        $this->productInterface->update($product, $data);

        return $this->ok(
            new ProductResource($product->fresh(['category', 'user'])),
            'Product updated successfully',
        );
    }

    public function delete(Product $product): JsonResponse
    {
        if ($product->user_id !== Auth::id()) {
            return $this->forbidden('You are not authorized to modify this product');
        }

        if ($product->image) {
            $this->fileUploadInterface->delete($product->image);
        }

        $this->productInterface->delete($product);

        return $this->deleted($product->id);
    }
}
