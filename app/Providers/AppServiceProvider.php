<?php

namespace App\Providers;

use App\Contracts\CategoryInterface;
use App\Contracts\ProductInterface;
use App\Contracts\ReviewsInterface;
use App\Contracts\UserInterface;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\ReviewsRepository;
use App\Repository\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // User binding
        $this->app->bind(UserInterface::class, UserRepository::class);
        
        // Category binding
        $this->app->bind(CategoryInterface::class, CategoryRepository::class);

        // Product binding
        $this->app->bind(ProductInterface::class, ProductRepository::class);

        // Reviews binding
        $this->app->bind(ReviewsInterface::class, ReviewsRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
