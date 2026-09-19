<?php

namespace App\Providers;

use App\Services\ProductService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {

            $categories = Cache::remember(
                'product_categories_tree',
                now()->addHours(24),
                function () {
                    return ProductService::buildCategoriesTree();
                }
            );

            $view->with('categoriesFrontEndHeader', $categories);
        });


        Paginator::useBootstrapFive();
    }
}
