<?php

use App\Http\Controllers\api\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\api\ProductTypeController;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

Route::group(['middleware' => 'auth:api'], function ($router) {

    //productsType operations
    Route::get('productTypes', [ProductTypeController::class, 'index']);
    Route::get('productType/{id}', [ProductTypeController::class, 'show']);
    Route::post('productType', [ProductTypeController::class, 'store']);
    Route::put('productType/{id}', [ProductTypeController::class, 'update']);
    Route::delete('productType/{id}', [ProductTypeController::class, 'destroy']);

    //items opertaions
    Route::get('items/{id}', [ItemController::class, 'index']);
    Route::post('item', [ItemController::class, 'store']);
    Route::post('item/{id}', [ItemController::class, 'sold']);
    Route::post('items', [ItemController::class, 'bulkStore']);
    Route::put('item/{id}', [ItemController::class, 'update']);
    Route::delete('item/{id}', [ItemController::class, 'destroy']);
});