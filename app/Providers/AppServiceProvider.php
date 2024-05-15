<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Document;
use App\Observers\CommentObserver;
use App\Observers\DocumentObserver;
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
        Document::observe(DocumentObserver::class);
        Comment::observe(CommentObserver::class);

    }
}
