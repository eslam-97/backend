<?php

use App\Http\Controllers\homeController;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;

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

Route::get('/', [homeController::class, 'acessories']);
// Route::get('hotOffers', [Controller::class, 'hotOffers']);


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
