<?php

use App\Http\Controllers\authController;
use App\Http\Controllers\filtersController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\indexController;
use Illuminate\Http\Request;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


###################################### authentication APIs #####################################################
    // Route::middleware('web')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('updateUser', [authController::class, 'updateUser']);
        Route::post('updateUserPassword', [authController::class, 'updateUserPassword']);
        Route::get('user', [authController::class, 'profile']);
        Route::post('addToUserCart', [indexController::class, 'addToUserCart']);
        Route::get('userCartProducts', [indexController::class, 'userCartProducts']);
        Route::delete('deleteCartProduct', [indexController::class, 'deleteCartProduct']);
        Route::post('addToUserWishList', [indexController::class, 'addToUserWishList']);
        Route::get('userWishlistProducts', [indexController::class, 'userWishlistProducts']);
        Route::delete('deleteWishlistProduct', [indexController::class, 'deleteWishlistProduct']);
        Route::post('addRating', [indexController::class, 'addRating']);

    });
    // });
    
    Route::post('signUp', [authController::class, 'register']);
    Route::post('login', [authController::class, 'login']);
    Route::post('logout', [authController::class, 'signOut']);


    
    ###################################### resources APIs ##########################################################
    ############ HOME APIs ############
    Route::get('hotOffers', [homeController::class, 'hotOffers']);
    Route::get('bestSeller', [homeController::class, 'bestSeller']);
    Route::get('newArrival', [homeController::class, 'newArrival']);
    Route::get('search', [homeController::class, 'search']);
    Route::get('productCategories', [homeController::class, 'productCategories']);
    

    ############ filters API ############
    Route::get('allProducts', [filtersController::class, 'allProducts']);
    Route::get('product', [filtersController::class, 'product']);
    Route::get('productBrand', [filtersController::class, 'productBrand']);
    Route::get('productColor', [filtersController::class, 'productColor']);
    Route::get('productOperatingSystem', [filtersController::class, 'productOperatingSystem']);
    Route::get('productByBrand', [filtersController::class, 'productByBrand']);
    Route::get('productByColor', [filtersController::class, 'productByColor']);
    Route::get('productByOperatingSystem', [filtersController::class, 'productByOperatingSystem']);


     ############ Index APIs ############
     Route::get('productSpecs', [indexController::class, 'productSpecs']);
     Route::get('ratingUsers', [indexController::class, 'ratingUsers']);
    
 
 
    

    


