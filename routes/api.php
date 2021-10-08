<?php

use App\Http\Controllers\authController;
use App\Http\Controllers\filtersController;
use App\Http\Controllers\resourceController;
use Illuminate\Support\Facades\Broadcast;
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

Broadcast::routes(['middleware' => ['auth:sanctum']]);

########################################## Sanctum Middleware APIs ################################################


Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::get('/', [authController::class, 'profile']);
    Route::put('/', [authController::class, 'updateUser']);
    Route::put('/password', [authController::class, 'updateUserPassword']);

    Route::get('/cart/products', [resourceController::class, 'showCartProducts']);
    Route::post('/cart/products/{product_id}', [resourceController::class, 'addToCart']);
    Route::delete('/cart/products/{product_id}', [resourceController::class, 'deleteCartProduct']);

    Route::get('/wishlist/products', [resourceController::class, 'showWishlistProducts']);
    Route::post('/wishlist/products/{product_id}', [resourceController::class, 'addToWishList']);
    Route::delete('/wishlist/products/{product_id}', [resourceController::class, 'deleteWishlistProduct']);

    Route::post('/product/{id}/review', [resourceController::class, 'addReview']);
    Route::post('/logout', [authController::class, 'signOut']);
});


######################################### resources APIs ########################################################

    Route::get('/products', [filtersController::class, 'products']);
    Route::get('/product/{id}', [filtersController::class, 'product']);
    Route::get('/product/{id}/specs', [resourceController::class, 'productSpecs']);
    Route::get('/product/{id}/reviewers', [resourceController::class, 'showReviewers']);

    Route::get('/products/prices', [filtersController::class, 'priceRanges']);
    Route::get('/products/search', [filtersController::class, 'search']);
    Route::get('/products/categories', [filtersController::class, 'productCategories']);
    Route::get('/products/brands', [filtersController::class, 'productBrand']);
    Route::get('/products/colors', [filtersController::class, 'productColor']);
    Route::get('/products/operating-systems', [filtersController::class, 'productOperatingSystem']);
 



 
    

    


