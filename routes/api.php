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
use Illuminate\Support\Facades\Mail;

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
Route::post('/perencanaan-data/store-identifikasi-kebutuhan', [PerencanaanDataController::class, 'storeIdentifikasiKebutuhan']);

Route::get('/perencanaan-data/get-data-vendor', [PerencanaanDataController::class, 'getAllDataVendor']);
Route::post('/perencanaan-data/store-shortlist-vendor', [PerencanaanDataController::class, 'selectDataVendor']);
Route::get('/perencanaan-data/perencanaan-data-result', [PerencanaanDataController::class, 'perencanaanDataResult']);

Route::get('/test-email', function () {
    Mail::raw('This is a test email', function ($message) {
        $message->to('bayuaditya0111@gmail.com')
                ->subject('Test Email');
    });

    return 'Email sent!';
});

Route::get('/password/reset/{token}', function ($token) {
    return view('auth.password.reset', ['token' => $token]);
})->name('password.reset');

