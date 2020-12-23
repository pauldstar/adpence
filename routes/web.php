<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', PlayController::class);

Route::prefix('login')->group(function () {
    Route::get('/{driver}', [LoginController::class, 'redirectToProvider'])->name('login');
    Route::get('/{driver}/callback', [LoginController::class, 'handleProviderCallback']);
});

Route::get('logout', [LoginController::class, 'logout'])->name('logout');
