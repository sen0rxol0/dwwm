<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;

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

Auth::routes();
Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function() {
    return redirect('/admin/home');
});
Route::get('/users/{id}/profile', [UserController::class, 'show'])->name('users.show');
Route::prefix('admin')->group(function()
{
    Route::name('admin.')->group(function()
    {
        Route::get('/home', [AdminController::class, 'showHome'])->name('home');
        /**
         * START CRUD
         */
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/ajouter', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('/categories/{id}/modifier', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::post('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.delete');
        /**
         * START CRUD
         */
        Route::get('/articles', [PostController::class, 'index'])->name('posts.index');
        Route::get('/articles/ajouter', [PostController::class, 'create'])->name('posts.create');
        Route::post('/articles', [PostController::class, 'store'])->name('posts.store');
        Route::get('/articles/{id}', [PostController::class, 'show'])->name('posts.show');
        Route::get('/articles/{id}/modifier', [PostController::class, 'edit'])->name('posts.edit');
        Route::post('/articles/{id}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/articles/{id}', [PostController::class, 'destroy'])->name('posts.delete');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });
});