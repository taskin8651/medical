<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        View::composer('custom.*', function ($view) {
            $frontendSetting = null;
            $frontendCategories = collect();

            if (Schema::hasTable('settings')) {
                $frontendSetting = Setting::first();
            }

            if (Schema::hasTable('categories')) {
                $frontendCategories = Category::active()
                    ->with(['subcategories', 'media'])
                    ->withCount(['products' => fn ($query) => $query->where('is_active', 1)])
                    ->orderBy('name')
                    ->take(12)
                    ->get();
            }

            $cartItems = session()->get('cart', []);
            $cartCount = collect($cartItems)->sum(fn ($item) => (int) ($item['quantity'] ?? 1));
            $cartTotal = collect($cartItems)->sum(function ($item) {
                return ((float) ($item['price_with_gst'] ?? $item['price'] ?? 0)) * (int) ($item['quantity'] ?? 1);
            });

            $view->with(compact(
                'frontendSetting',
                'frontendCategories',
                'cartItems',
                'cartCount',
                'cartTotal'
            ));
        });
    }
}
