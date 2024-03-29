<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'title' => 'Admin Dashboard',
            'breadcrumbs' => [
                'Dashboard' => null,
            ],
        ]);
    }
}
