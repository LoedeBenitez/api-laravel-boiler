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
#region Authentication routes
Route::post('login', [App\Http\Controllers\Auth\CredentialController::class, 'onLogin']);
Route::post('password/reset', [App\Http\Controllers\Auth\CredentialController::class, 'onResetPassword']);
Route::post('password/forgot', [App\Http\Controllers\Auth\CredentialController::class, 'onForgotPassword']);
Route::post('otp/send', [App\Http\Controllers\Auth\SignedUrlController::class, 'onSendOtp']);
Route::post('otp/validate', [App\Http\Controllers\Auth\SignedUrlController::class, 'onValidateOtp']);
#endregion

Route::get('user/access/get/{id}', [App\Http\Controllers\Access\AccessManagementController::class, 'onGetAccessList']);
Route::get('signed-url/check/{token}', [App\Http\Controllers\Auth\SignedUrlController::class, 'onCheckSignedURL']);
Route::group(['middleware' => ['auth:sanctum', 'check.system.status:SMPL-SYS']], function () {
    Route::get('logout', [App\Http\Controllers\Auth\CredentialController::class, 'onLogout']); // Logout
    Route::post('user/create', [App\Http\Controllers\User\UserController::class, 'onCreate']); // Logout

    Route::post('user/bulk', [App\Http\Controllers\Bulk\BulkController::class, 'onBulkUploadEmployee']);
    Route::post('user/update/employment/bulk', [App\Http\Controllers\Bulk\BulkController::class, 'onBulkUpdateEmployeeInformation']);
    Route::post('user/email/send', [App\Http\Controllers\Bulk\BulkController::class, 'onRequestEmailBlast']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    #region System Status
    Route::post('v1/system/admin/status/change/{system_id}', [App\Http\Controllers\Admin\System\AdminSystemController::class, 'onChangeStatus']);
    Route::get('v1/system/admin/get/{system_id?}', [App\Http\Controllers\Admin\System\AdminSystemController::class, 'onGet']);

    Route::get('run-migrations', function () {
        // Artisan::call('migrate', ["--force" => true]);
        Artisan::call('migrate', ["--force" => true]);
        return 'Migrations completed successfully!';
    });
    Route::get('run-migrations-and-seed', function () {
        // Artisan::call('migrate', ["--force" => true]);
        Artisan::call('migrate:fresh', ["--force" => true]);
        Artisan::call('db:seed', ["--force" => true]);
        return 'Migrations and Seed completed successfully!';
    });
});
