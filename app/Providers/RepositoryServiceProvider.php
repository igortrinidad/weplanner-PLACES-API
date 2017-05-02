<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Repositories\UserRepository::class, \App\Repositories\UserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\UserSocialProviderRepository::class, \App\Repositories\UserSocialProviderRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PlaceCategoryRepository::class, \App\Repositories\PlaceCategoryRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PlaceRepository::class, \App\Repositories\PlaceRepositoryEloquent::class);
        //:end-bindings:
    }
}
