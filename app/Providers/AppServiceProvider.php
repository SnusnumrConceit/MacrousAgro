<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Order;
use App\Models\Photo;
use App\Models\Product;
use App\Models\Video;
use App\Observers\ArticleObserver;
use App\Observers\OrderObserver;
use App\Observers\PhotoObserver;
use App\Observers\ProductObserver;
use App\Observers\UserObserver;
use App\Observers\VideoObserver;
use App\User;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Illuminate\Database\Schema\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Cashier::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Article::observe(ArticleObserver::class);
        Order::observe(OrderObserver::class);
        Photo::observe(PhotoObserver::class);
        Product::observe(ProductObserver::class);
        Video::observe(VideoObserver::class);
        User::observe(UserObserver::class);
        Builder::defaultStringLength(191); // Update defaultStringLength
    }
}
