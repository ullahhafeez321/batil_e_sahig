<?php

namespace App\Providers;

use Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Post;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define("isAdmin", function (User $user) {
            return $user->email === "ullahhafeez321@gmail.com";
        });

        
    }
    
}
