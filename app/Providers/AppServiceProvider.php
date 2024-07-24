<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

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
        // this let's change morphable_type in polymorphic relations from default values
        Relation::enforceMorphMap([
            'Topic' => 'App\Models\Topic',
            'Post' => 'App\Models\Post',
        ]);
    }
}
