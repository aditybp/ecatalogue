<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VendorController;

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


Route::post('/store-user', [UsersController::class, 'store']);
Route::get('/test', [UsersController::class, 'test']);
Route::get('/get-user/{id}', [UsersController::class, 'getUserById']);

Route::get('/show-allvendor', [VendorController::class, 'allVendor']);
Route::post('/input-vendor', [VendorController::class, 'inputVendor']);
