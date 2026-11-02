<?php
namespace App\Repository;

use App\Contracts\ReviewsInterface;
use App\Models\Product;
use App\Models\Reviews;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Override;

class ReviewsRepository implements ReviewsInterface {
    #[Override]
    public function create(array $data): Reviews
    {
        return DB::transaction(function () use ($data) {
            $review = Reviews::create($data);

            // Hitung agregat rating dan total review untuk produk ini
            $stats = Reviews::where('product_id', $data['product_id'])
                ->selectRaw('COUNT(*) as total_count, AVG(rating) as avg_rating')
                ->first();

            // Update rating dan review_count pada produk
            Product::where('id', $data['product_id'])->update([
                'rating' => round($stats->avg_rating ?? 0, 1),
                'review_count' => $stats->total_count ?? 0,
            ]);

            return $review;
        });
    }

    #[Override]
    public function getWithPaginated(Product $product, int $per_page = 10): LengthAwarePaginator {
        return Reviews::with('user:id,name')
            ->where('product_id', $product->id)
            ->latest()
            ->paginate($per_page);
    }
}