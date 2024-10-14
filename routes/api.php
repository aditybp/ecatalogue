<?php

use App\Http\Controllers\AccountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PerencanaanDataController;
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

Route::get('/send-username/{id}', [AccountController::class, 'sendUsernameAndEmail']);

Route::post('/store-user', [UsersController::class, 'store']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::post('/refresh', [LoginController::class, 'refresh']);
Route::get('/get-user/{id}', [UsersController::class, 'getUserById']);

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

//password reset routes
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

Route::get('/show-allvendor', [VendorController::class, 'allVendor']);
Route::post('/input-vendor', [VendorController::class, 'inputVendor']);

Route::post('/perencanaan-data/store-informasi-umum/', [PerencanaanDataController::class, 'storeInformasiUmumData']);
Route::get('/perencanaan-data/informasi-umum/{id}', [PerencanaanDataController::class, 'getInformasiUmumByPerencanaanId']);
Route::post('/perencanaan-data/identifikasi-kebutuhan', [PerencanaanDataController::class, 'storeIdentifikasiKebutuhan']);
