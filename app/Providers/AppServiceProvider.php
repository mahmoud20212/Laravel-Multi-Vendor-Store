<?php

namespace App\Providers;

use App\Rules\Filter;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
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
        // remove [data] wrapping in response
        JsonResource::withoutWrapping();

        // General Rule
        Validator::extend('filter', function($attribute, $value, $params){
            return !in_array(strtolower($value), $params);
        }, 'The value is prohibited!');

        Paginator::useBootstrapFour();
        // Paginator::defaultView('pagination.custom');
    }
}
