<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// automatically append any query strings to the pagination we get from models
		// so we don't have to repeat ourselves each time we do a paginate
		$this->app->resolving(Paginator::class, function ($paginator) {
			return $paginator->appends(Arr::except(Request::query(), $paginator->getPageName()));
		});

		$this->app->resolving(LengthAwarePaginator::class, function ($paginator) {
			return $paginator->appends(Arr::except(Request::query(), $paginator->getPageName()));
		});
	}
}
