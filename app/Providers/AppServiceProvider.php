<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
        // Redirect after login
        $this->app->make(\Laravel\Fortify\Fortify::class);
        
        // Custom login response
        \Laravel\Fortify\Fortify::loginView(function () {
            return view('auth.login');
        });
        
        // Redirect after registration to welcome page
        \Laravel\Fortify\Fortify::registerView(function () {
            return view('auth.register');
        });
        
        // Override redirect after user creation
        $this->app->bind(\Laravel\Fortify\Contracts\RegisterResponse::class, 
            \App\Http\Responses\RegisterResponse::class
        );
    }
}