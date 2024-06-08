<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Loan\CreateLoan;
use App\Actions\Loan\DeleteLoan;
use App\Actions\Loan\GetLoans;
use App\Actions\Loan\UpdateLoan;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\LoanResource;
use App\Models\Equipment;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoanController extends Controller
{
    /**
     * Create the controller instance.
     * This automatically applies the loan policies to the default functions this controller has.
     */
    public function __construct()
    {
        $this->authorizeResource(Loan::class, 'loan');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Admin/Loan/Index', [
            'title' => 'Loans',
            'filters' => $request->only('search', 'status'),
            'breadcrumbs' => [
                'Dashboard' => route('admin.dashboard.index'),
                'Loans' => null,
            ],
            'loans' => fn () => (new GetLoans())->execute($request, load: ['loanee'], count: ['equipments']),
            'equipments' => fn () => Equipment::orderBy('name')->get()->map(function ($equipment) {
                $id = $equipment->id;
                $name = $equipment->name;
                $notInStock = $equipment->outOfStock();

                if ($notInStock) {
                    $name = $name . ' - Not in stock';
                }

                return [
                    'id' => $id,
                    'name' => $name,
                    'disabled' => $notInStock,
                ];
            }),
            'statuses' => fn () => collect(StatusHelper::allStatuses())->map(function ($status) {
                return [
                    'name' => ucwords($status),
                    'value' => $status,
                ];
            })
                ->sortBy('name')
                ->prepend([
                    'name' => 'All',
                    'value' => 'all',
                ])
                ->values(),
            'users' => fn () => User::orderBy('name')->get()->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'profile_photo_url' => $user->profile_photo_url,
                ];
            }),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $loan = (new CreateLoan())->execute($request->all());

        return redirect()->back()->with('message', [
            'type' => 'success',
            'message' => 'Loan created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        $loan = new LoanResource($loan->loadMissing('createdBy', 'updatedBy', 'approvedBy', 'deniedBy', 'loanee'));
        $loanEquipment = $loan->equipments->map(function ($equipment) {
            return [
                ...$equipment->toArray(),
                'pivot' => $equipment->pivot,
            ];
        });

        return Inertia::render('Admin/Loan/Show', [
            'title' => 'Loan for: ' . $loan->loanee->name,
            'loan' => $loan,
            'loanEquipment' => $loanEquipment,
            'breadcrumbs' => [
                'Dashboard' => route('admin.dashboard.index'),
                'Loans' => route('admin.loan.index'),
                'Loan for: ' . $loan->loanee->name => null,
            ],
            'equipments' => fn () => Equipment::orderBy('name')->get()->map(function ($equipment) {
                $id = $equipment->id;
                $name = $equipment->name;
                $notInStock = $equipment->calculateAmountInStock() === 0;

                if ($notInStock) {
                    $name = $name . ' - Not in stock';
                }

                return [
                    'id' => $id,
                    'name' => $name,
                    'disabled' => $notInStock,
                ];
            }),
            'statuses' => fn () => collect(StatusHelper::allStatuses())->map(function ($status) {
                return [
                    'name' => ucwords($status),
                    'value' => $status,
                ];
            })
                ->sortBy('name')
                ->prepend([
                    'name' => 'All',
                    'value' => 'all',
                ])
                ->values(),
            'users' => fn () => User::orderBy('name')->get()->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'profile_photo_url' => $user->profile_photo_url,
                ];
            }),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan)
    {
        $loan = (new UpdateLoan())->execute($loan, $request->all());

        return redirect()->back()->with('message', [
            'type' => 'success',
            'message' => 'Loan updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        $loan = (new DeleteLoan())->execute($loan);

        return redirect()->route('admin.loan.index')->with('message', [
            'type' => 'success',
            'message' => 'Loan deleted successfully.',
        ]);
    }
}
