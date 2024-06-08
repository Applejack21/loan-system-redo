<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\EquipmentResource;
use App\Http\Resources\LoanResource;
use App\Models\Equipment;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get the 5 most oldest overdue loans.
        $overdueLoans = Loan::overdue()
            ->with('loanee')
            ->withCount('equipments')
            ->oldest('end_date')
            ->limit(5)
            ->get();

        // Get 5 equipments that are out of stock.
        $outOfStockEquipments = Equipment::latest()
            ->get()
            ->filter(fn ($equipment) => $equipment->outOfStock())
            ->take(5)
            ->values();

        /**
         * Get data for dashboard charts.
         */
        $thiryDaysAgo = today()->subDays(30)->startOfDay();

        // A list of loans created within the last 30 days, grouped by date.
        $loans = Loan::with('equipments')
            ->where('created_at', '>=', $thiryDaysAgo)
            ->get();

        // Store count of each equipment here.
        $topEquipments = [];

        // Count each equipment from each loan into above array.
        $loans->each(function (Loan $loan) use (&$topEquipments) {
            $loan->equipments->each(function ($equipment) use (&$topEquipments) {
                $quantity = $equipment->pivot->quantity;
                if (isset($topEquipments[$equipment->id])) {
                    $topEquipments[$equipment->id] += $quantity;
                } else {
                    $topEquipments[$equipment->id] = $quantity;
                }
            });
        });

        // Sort by most used equipment.
        arsort($topEquipments);

        // Pluck top 5 IDs.
        $top5EquipmentIds = array_slice(array_keys($topEquipments), 0, 5, true);

        // Get equipment for said IDs.
        $equipments = Equipment::whereIn('id', $top5EquipmentIds)->get();

        // Map equipment data into chart data.
        $equipmentChartData = $equipments->map(function ($equipment) use ($topEquipments) {
            return [
                'y' => $topEquipments[$equipment->id],
                'label' => $equipment->name,
            ];
        })
            ->sortByDesc('y')
            ->values()
            ->all();

        $loans = $loans->groupBy(function ($loan) {
            return $loan->created_at->format('M d'); // Format as 'M d' so we can count later.
        });

        // A list of users created within the last 30 days, grouped by date.
        $users = User::where('created_at', '>=', $thiryDaysAgo)
            ->get()
            ->groupBy(function ($user) {
                return $user->created_at->format('M d'); // Format as 'M d' so we can count later.
            });

        // Create a date range for the last 30 days.
        $dates = collect(range(0, 30))->map(function ($i) {
            return Carbon::today()->subDays($i)->format('M d');
        })->reverse()->values();

        // Process the data to count loans and users per day.
        $loanData = $dates->map(function ($date) use ($loans) {
            // Find the amount of loans for this date. If no loans, then count will count an empty collection (so return 0).
            return $loans->get($date, collect())->count();
        });

        $userData = $dates->map(function ($date) use ($users) {
            // Find the amount of loans for this date. If no loans, then count will count an empty collection (so return 0).
            return $users->get($date, collect())->count();
        });

        // Get a list of recent requested loans.
        $recentLoans = Loan::where('status', StatusHelper::STATUS_REQUESTED)
            ->with('loanee')
            ->withCount('equipments')
            ->latest()
            ->limit(5)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'title' => 'Dashboard',
            'breadcrumbs' => [
                'Dashboard' => null,
            ],
            'overdueLoans' => LoanResource::collection($overdueLoans),
            'outOfStockEquipments' => EquipmentResource::collection($outOfStockEquipments),
            'recentLoans' => LoanResource::collection($recentLoans),
            'loanChartData' => $loanData,
            'userChartData' => $userData,
            'chartDates' => $dates,
            'equipmentChartData' => $equipmentChartData,
        ]);
    }
}
