<?php

use App\Http\Controllers\AdminPostsController;
use App\Http\Controllers\AdminUsersController;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/**frontend**/
Route::get("/", function () {
    return view("home");
});
Route::get("contactformulier", [
    App\Http\Controllers\ContactController::class,
    "create",
])->name("contact.create");
Route::post("contactformulier", [
    App\Http\Controllers\ContactController::class,
    "store",
]);
Route::get("post/{slug}", [AdminPostsController::class, "post"])->name(
    "frontend.post"
);

/**backend**/

Route::group(
    ["prefix" => "admin", "middleware" => ["auth", "verified"]],
    function () {
        Route::get("/", [
            App\Http\Controllers\HomeController::class,
            "index",
        ])->name("home");
        /*posts*/
        Route::resource("posts", AdminPostsController::class, [
            "except" => ["show"],
        ]);
        Route::get("posts/{post:slug}", [
            AdminPostsController::class,
            "show",
        ])->name("posts.show");
        Route::post("posts/restore/{post}", [
            AdminPostsController::class,
            "postRestore",
        ])->name("admin.postrestore");
        Route::get("authors/{author:name}", [
            AdminPostsController::class,
            "indexByAuthor",
        ])->name("authors");

        Route::resource(
            "comments",
            \App\Http\Controllers\CommentsController::class
        );

        Route::resource(
            "categories",
            \App\Http\Controllers\AdminCategoriesController::class
        );
        Route::post("categories/restore/{category}", [
            \App\Http\Controllers\AdminCategoriesController::class,
            "categoryRestore",
        ])->name("admin.categoryrestore");

        Route::group(["middleware" => "admin"], function () {
            Route::resource("users", AdminUsersController::class);
            Route::post("users/restore/{user}", [
                AdminUsersController::class,
                "userRestore",
            ])->name("admin.userrestore");
            Route::get("usersblade", [
                AdminUsersController::class,
                "index2",
            ])->name("users.index2");
        });
    }
);

Auth::routes(["verify" => true]); //variabele met de naam verify
