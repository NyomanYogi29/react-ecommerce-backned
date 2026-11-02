<?php
namespace App\Repository;

use App\Contracts\ReviewsInterface;
use App\Models\Product;
use App\Models\Reviews;
use Illuminate\Pagination\LengthAwarePaginator;
use Override;

class ReviewsRepository implements ReviewsInterface {
    #[Override]
    public function create(array $data): Reviews
    {
        return Reviews::create($data);
    }

    #[Override]
    public function getWithPaginated(Product $product, int $per_page = 10): LengthAwarePaginator {
        return Reviews::with('user:id,name')
            ->where('product_id', $product->id)
            ->latest()
            ->paginate($per_page);
    }
}