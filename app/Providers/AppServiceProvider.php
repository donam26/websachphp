<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Paginator::useBootstrap();

        View::composer(['layouts.app', 'layouts.admin'], function ($view) {
            $globalCategories = cache()->remember('global_categories', 600, function () {
                return Category::orderBy('name')->get();
            });

            $cartCount = 0;
            if (auth()->check()) {
                $cartCount = auth()->user()->cart()->sum('quantity');
            }

            $view->with('globalCategories', $globalCategories)
                 ->with('cartCount', $cartCount);
        });
    }
}
