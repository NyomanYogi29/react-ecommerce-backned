<?php

namespace App\Providers;

use App\Contract\UserInterface\UserCreatorInterface;
use App\Contracts\CategoryInterface\CategoryFinder;
use App\Contracts\ProductInterface\ProductCreator;
use App\Contracts\ProductInterface\ProductFinder;
use App\Contracts\UserInterface\UserFinderInterface;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
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
        $this->app->bind(UserCreatorInterface::class, UserRepository::class);
        $this->app->bind(UserFinderInterface::class, UserRepository::class);
        
        // Category binding
        $this->app->bind(CategoryFinder::class, CategoryRepository::class);

        // Product binding
        $this->app->bind(ProductFinder::class, ProductRepository::class);
        $this->app->bind(ProductCreator::class, ProductRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
