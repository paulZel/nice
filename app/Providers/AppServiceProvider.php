<?php

namespace App\Providers;
use Illuminate\Pagination\Paginator;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Comment;

use Illuminate\Support\ServiceProvider;

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
        Paginator::useBootstrap();

        view()->composer('pages._sidebar', function ($view) {
            $view->with('popularPosts', Post::getPopularPosts());

            $view->with('featuredPosts', Post::getFeaturedPosts());

            $view->with('recentPosts', Post::getRecentPosts());

            $view->with('categories', Category::all());
        });

        view()->composer('admin._sidebar', function ($view) {
            $view->with('newCommentsCount', Comment::where('status', 0)->count());
        });
    }
}
