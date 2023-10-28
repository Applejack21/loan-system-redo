<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
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

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

	Route::controller(DashboardController::class)->name('dashboard.')->group(function () {
		Route::get('/', 'index')->name('index');
	});

	Route::prefix('admin')->group(function () {
		Route::controller(DashboardController::class)->name('dashboard-admin.')->group(function () {
			Route::get('/', 'indexAdmin')->name('index');
		});

		Route::controller(UserController::class)->name('user.')->group(function () {
			Route::get('users', 'index')->name('index');
			Route::get('user/{user}', 'show')->name('show');
			Route::post('/', 'store')->name('store');
			Route::patch('{user}', 'update')->name('update');
			Route::delete('{user}', 'destroy')->name('destroy');
		});
	});
});
