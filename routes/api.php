<?php

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
// Route::group([ 'prefix' => 'v1', 'middleware' => ['auth:admin-api', 'auth:sanctum'] ], function () {} ); // demo

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([ 'prefix' => 'v1', 'middleware' => ['auth:sanctum'] ], function () {
    Route::post('/register', 'AuthController@register')->withoutMiddleware('auth:sanctum');
    Route::post('/login', 'AuthController@login')->withoutMiddleware('auth:sanctum');
    Route::get('/user', 'AuthController@getAuthUser');
    Route::get('/logout', 'AuthController@logout');

    Route::apiResource('/products', 'Admin\AdminProductController');
    Route::apiResource('/categories', 'Admin\AdminCategoryController');
    Route::apiResource('/orders', 'Admin\AdminOrderController');
});
