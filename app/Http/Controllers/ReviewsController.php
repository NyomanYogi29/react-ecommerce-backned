<?php

namespace App\Http\Controllers;

use App\Contracts\ReviewsInterface;
use App\Http\Requests\Reviews\CreateReviewRequest;
use App\Http\Resources\ReviewsResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ReviewsController extends Controller
{
    public function __construct(
        private ReviewsInterface $reviewsInterface
    ) {}

    public function create(CreateReviewRequest $request, Product $product): JsonResponse
    {
        $userId = $request->user()->id;

        $data = $request->validated();

        $reviews = $this->reviewsInterface->create([
            'user_id' => $userId,
            'product_id' => $product->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return $this->ok(
            new ReviewsResource($reviews),
            'Reviews created',
            201
        );
    }
}
