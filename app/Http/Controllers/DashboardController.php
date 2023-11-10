<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Dashboard', [
			'title' => 'Dashboard',
			'breadcrumbs' => [
				'Dashboard' => null,
			]
		]);
    }

	/**
	 * Display a listing of the resource.
	 */
	public function indexAdmin()
    {
        return Inertia::render('DashboardAdmin', [
			'title' => 'Admin Dashboard',
			'breadcrumbs' => [
				'Admin Dashboard' => null,
			],
		]);
    }
}
