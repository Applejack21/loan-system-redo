<?php

namespace App\Http\Controllers;

use App\Helpers\StatusHelper;
use App\Http\Resources\LoanResource;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get a list of loans this user has.
        $loans = auth()->user()
            ->loans
            ->sortByDesc('created_at')
            ->values();

        // Get a list of loans that are overdue. Order by most overdue loan.
        $overDueLoans = $loans->where('status', StatusHelper::STATUS_OVERDUE)
            ->sortBy('created_at')
            ->values();

        // Get the first 5 loans from each one.
        $loans = $loans->take(5);
        $overDueLoans = $overDueLoans->take(5);

        return Inertia::render('Dashboard', [
            'title' => 'Dashboard',
            'breadcrumbs' => [
                'Dashboard' => null,
            ],
            'loans' => LoanResource::collection($loans->loadCount('equipments')),
            'overdueLoans' => LoanResource::collection($overDueLoans),
        ]);
    }
}
