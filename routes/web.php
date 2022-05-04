<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\CommentsController;
use App\Http\Controllers\Admin\SubscribersController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubsController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Middleware;

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


Route::get('/', [HomeController::class, 'index']);
Route::get('/post/{slug}', [HomeController::class, 'show'])->name('post.show');
Route::get('/tag/{slug}', [HomeController::class, 'tag'])->name('tag.show');
Route::get('/category/{slug}', [HomeController::class, 'category'])->name('category.show');
Route::post('/subscribe', [SubsController::class, 'subscribe']);
Route::get('/verify/{token}', [SubsController::class, 'verify']);


Route::group(['middleware' => 'admin'], function() {
    Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard.show');
    Route::resource('/admin/categories', CategoriesController::class);
    Route::resource('/admin/tags', TagsController::class);
    Route::resource('/admin/users', UsersController::class);
    Route::resource('/admin/posts', PostsController::class);
    Route::resource('/admin/posts/likes', [PostsController::class], 'likes');
    Route::get('/admin/comments', [CommentsController::class, 'index']);
    Route::get('admin/comments/toggle/{id}', [CommentsController::class, 'toggle']);
    Route::delete('/comments/{id}/destroy', [CommentsController::class, 'destroy'])->name('comments.destroy');
    Route::resource('admin/subscribers', SubscribersController::class);
});

Route::group(['middleware' => 'auth'], function(){
    Route::get('/profile', [AuthController::class, 'profileForm']);
    Route::post('/profile', [AuthController::class, 'profile']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/comment', [CommentController::class, 'store']);
});

Route::group(['middleware' => 'guest'], function(){
    Route::get('/register', [AuthController::class, 'registerForm']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});






