<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
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
        //
        $usersCount = User::count();
        $postsCount = Post::count();
        $categoriesCount = Category::count();

        view()->share('postsCount', $postsCount);
        view()->share('usersCount', $usersCount);
        view()->share('categoriesCount', $categoriesCount);
    }
}
