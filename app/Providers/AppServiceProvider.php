<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Topic;
use Illuminate\Http\Resources\Json\JsonResource;
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
        // disables wrapping resource collection responses in a 'data' key
        JsonResource::withoutWrapping();

        // this lets change morphable_type in polymorphic relations from default values
        Relation::enforceMorphMap([
            'Topic' => Topic::class,
            'Post' => Post::class,
        ]);
    }
}
