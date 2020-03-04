<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Article'  => 'App\Policies\ArticlePolicy',
        'App\Models\Category' => 'App\Policies\CategoryPolicy',
        'App\Models\Order'    => 'App\Policies\OrderPolicy',
        'App\Models\Product'  => 'App\Policies\ProductPolicy',
        'App\Models\Photo'    => 'App\Policies\PhotoPolicy',
        'App\Models\Video'    => 'App\Policies\VideoPolicy',
        'App\User'            => 'App\Policies\UserPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


    }
}
