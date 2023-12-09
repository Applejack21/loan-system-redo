<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController as FrontendDashboardController;
use App\Http\Middleware\AdminRoutes;
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

	Route::controller(FrontendDashboardController::class)->name('dashboard.')->group(function () {
		Route::get('/', 'index')->name('index');
	});

	/**
	 * Routes only for admins. Models have their own policies to make sure certain users are allowed access to the controller methods.
	 */
	Route::middleware(AdminRoutes::class)->group(function () {
		Route::prefix('admin')->name('admin.')->group(function () {

			Route::controller(DashboardController::class)->name('dashboard.')->group(function () {
				Route::get('/', 'index')->name('index');
			});

			Route::controller(UserController::class)->name('user.')->group(function () {
				Route::get('users', 'index')->name('index');
				Route::get('user/{user}', 'show')->name('show');
				Route::post('/user', 'store')->name('store');
				Route::patch('user/{user}', 'update')->name('update');
				Route::delete('user/{user}', 'destroy')->name('destroy');
			});

			Route::controller(CategoryController::class)->name('category.')->group(function () {
				Route::get('categories', 'index')->name('index');
				Route::get('category/{category}', 'show')->name('show');
				Route::post('/category', 'store')->name('store');
				Route::patch('category/{category}', 'update')->name('update');
				Route::delete('category/{category}', 'destroy')->name('destroy');
			});

			Route::controller(LocationController::class)->name('location.')->group(function () {
				Route::get('locations', 'index')->name('index');
				Route::get('location/{location}', 'show')->name('show');
				Route::post('/location', 'store')->name('store');
				Route::patch('location/{location}', 'update')->name('update');
				Route::delete('location/{location}', 'destroy')->name('destroy');
			});
		});
	});
});
