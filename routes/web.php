<?php

use App\Livewire\Auth\Login;
use App\Livewire\Documentation;
use App\Livewire\Endpoint;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Route::get('/login', Login::class)->name('login');
// Route::get('/documentation', Documentation::class)->name('documentation');
// Route::get('/endpoint', Endpoint::class)->name('endpoint');
Route::get('/preview-create-password-mail', function () {
    return view('mail.create-password-mail', ['employeeId' => 12345]);
});
