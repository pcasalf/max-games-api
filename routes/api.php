<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth.apikey')->group(function(){
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::prefix('carts')->group(function(){
        Route::get('/', [CartController::class, 'getCart'])->name('carts.get');
        Route::post('/add', [CartController::class, 'addItem'])->name('carts.add');
        Route::post('/remove', [CartController::class, 'removeItem'])->name('carts.remove');
        Route::get('/flush', [CartController::class, 'flushCart'])->name('carts.flush');
    });

    Route::prefix('orders')->group(function(){
        Route::get('/', [OrderController::class, 'getOrders']);
        Route::get('/{order}', [OrderController::class, 'getOrder']);
        Route::post('/', [OrderController::class, 'placeOrder']);
    });
});

Route::prefix('products')->group(function(){
    Route::get('/', [ProductController::class, 'all'])->name('products.all');
    Route::get('/{product}', [ProductController::class, 'getProduct'])->name('products.get');
    Route::post('/', [ProductController::class, 'createProduct'])->name('products.create')->middleware('auth.apikey');
    Route::put('/{product}', [ProductController::class, 'editProduct'])->name('products.edit')->middleware('auth.apikey');
});

Route::prefix('categories')->group(function(){
    Route::get('/', [CategoriesController::class, 'getCategories'])->name('categories.all');
    Route::get('/{category}', [CategoriesController::class, 'getCategory'])->name('categories.get')->middleware('auth.apikey');
    Route::post('/', [CategoriesController::class, 'createCategory'])->name('categories.create')->middleware('auth.apikey');
    Route::put('/{category}', [CategoriesController::class, 'editCategory'])->name('categories.edit')->middleware('auth.apikey');
});

Route::prefix('platforms')->group(function () {
    Route::get('/', [PlatformController::class, 'getPlatforms'])->name('platforms.all');
    Route::get('/{platform}', [PlatformController::class, 'getPlatform'])->name('platforms.get')->middleware('auth.apikey');
    Route::post('/', [PlatformController::class, 'createPlatform'])->name('platforms.create')->middleware('auth.apikey');
    Route::put('/{platform}', [PlatformController::class, 'editPlatform'])->name('platforms.edit')->middleware('auth.apikey');
});

Route::get('configurations', [ConfigurationController::class, 'getConfigurations'])->name('configurations');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::post('register', [AuthController::class, 'register'])->name('auth.register');
Route::get('verify', [AuthController::class, 'verify'])->name('auth.verify');

