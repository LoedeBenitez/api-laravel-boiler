<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('v1/login', [App\Http\Controllers\v1\Auth\CredentialController::class, 'onLogin']);

Route::get('v1/user/access/get/{id}', [App\Http\Controllers\v1\Access\AccessManagementController::class, 'onGetAccessList']);
// Route::get('v1/check/token/{token}', [App\Http\Controllers\v1\Auth\CredentialController::class, 'onCheckToken']);
Route::get('signed-url/check/{token}', [App\Http\Controllers\Auth\SignedUrlController::class, 'onCheckSignedURL']);
// Route::post('signed-url/create', [App\Http\Controllers\Auth\SignedUrlController::class, 'onSendLoginURL']);
Route::post('password/reset', [App\Http\Controllers\Auth\CredentialController::class, 'onResetPassword']);
Route::post('password/forgot', [App\Http\Controllers\Auth\CredentialController::class, 'onForgotPassword']);

Route::post('otp/send', [App\Http\Controllers\Auth\SignedUrlController::class, 'onSendOtp']);
Route::post('otp/validate', [App\Http\Controllers\Auth\SignedUrlController::class, 'onValidateOtp']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('v1/logout', [App\Http\Controllers\v1\Auth\CredentialController::class, 'onLogout']); // Logout

    Route::get('token/check', [App\Http\Controllers\Auth\SignedUrlController::class, 'onCheckToken']);

    Route::post('user/bulk', [App\Http\Controllers\Bulk\BulkController::class, 'onBulkUploadEmployee']);
    Route::post('user/update/employment/bulk', [App\Http\Controllers\Bulk\BulkController::class, 'onBulkUpdateEmployeeInformation']);

    Route::post('user/email/send', [App\Http\Controllers\Bulk\BulkController::class, 'onRequestEmailBlast']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('v1/run-migrations', function () {
        // Artisan::call('migrate', ["--force" => true]);
        Artisan::call('migrate', ["--force" => true]);
        return 'Migrations completed successfully!';
    });
    Route::get('v1/run-migrations-and-seed', function () {
        // Artisan::call('migrate', ["--force" => true]);
        Artisan::call('migrate:fresh', ["--force" => true]);
        Artisan::call('db:seed', ["--force" => true]);
        return 'Migrations and Seed completed successfully!';
    });
});
