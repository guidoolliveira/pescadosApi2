<?php

namespace App\Providers;
use App\Models\Product;
use Illuminate\Support\Facades\View;


use Illuminate\Database\Schema\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         Builder::defaultStringLength(125);
         View::composer('*', function ($view) {
            $products = Product::with('estoque')->get();
    
            $outOfStockProducts = $products->filter(function ($product) {
                return $product->estoque()->sum('quantity') == 0;
            });
    
            $view->with('outOfStockProducts', $outOfStockProducts);
        });
    }
}