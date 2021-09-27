<?php

use App\Http\Controllers\authController;
use App\Http\Controllers\filtersController;
use App\Http\Controllers\indexController;
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


############################################### Auth APIs ##########################################################


Route::post('/signUp', [authController::class, 'register']);
Route::post('/login', [authController::class, 'login']);


########################################## Sanctum Middleware APIs ################################################


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [authController::class, 'profile']);
    Route::put('/user/{id}', [authController::class, 'updateUser']);
    Route::put('/user-password/{id}', [authController::class, 'updateUserPassword']);

    Route::get('/user/cart/products', [indexController::class, 'userCartProducts']);
    Route::post('/user/cart/product/{id}', [indexController::class, 'addToUserCart']);
    Route::delete('/user/cart/product/{id}', [indexController::class, 'deleteCartProduct']);

    Route::get('/user/wishlist/products', [indexController::class, 'userWishlistProducts']);
    Route::post('/user/wishlist/product/{id}', [indexController::class, 'addToUserWishList']);
    Route::delete('/user/wishlist/product/{id}', [indexController::class, 'deleteWishlistProduct']);

    Route::post('/product/{id}/review', [indexController::class, 'addRating']);
    Route::post('/logout', [authController::class, 'signOut']);
});


######################################### resources APIs ########################################################

    Route::get('/products', [filtersController::class, 'products']);
    Route::get('/product/{id}', [filtersController::class, 'product']);
    Route::get('/product/{id}/specs', [indexController::class, 'productSpecs']);
    Route::get('/product/{id}/reviewers', [indexController::class, 'ratingUsers']);
    Route::get('/products/prices', [filtersController::class, 'prices']);
    Route::get('/products/search', [filtersController::class, 'search']);
    Route::get('/products/categories', [filtersController::class, 'productCategories']);
    Route::get('/products/brands', [filtersController::class, 'productBrand']);
    Route::get('/products/colors', [filtersController::class, 'productColor']);
    Route::get('/products/operating-systems', [filtersController::class, 'productOperatingSystem']);
 



 
    

    


