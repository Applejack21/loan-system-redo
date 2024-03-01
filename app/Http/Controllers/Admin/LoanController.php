<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Loan\CreateLoan;
use App\Actions\Loan\DeleteLoan;
use App\Actions\Loan\GetLoans;
use App\Actions\Loan\UpdateLoan;
use App\Http\Controllers\Controller;
use App\Http\Resources\LoanResource;
use App\Models\Loan;
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
			'loans' => fn () => (new GetLoans())->execute($request, ['loanee'], ['equipments']),
			'filters' => $request->only('search'),
			'breadcrumbs' => [
				'Dashboard' => route('admin.dashboard.index'),
				'Loans' => null,
			],
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
		return Inertia::render('Admin/Loan/Show', [
			'title' => 'Loan for: ' . $loan->loanee->name,
			'loan' => new LoanResource($loan->loadMissing('createdBy', 'updatedBy', 'approvedBy', 'deniedBy', 'loanee', 'equipments')),
			'breadcrumbs' => [
				'Dashboard' => route('admin.dashboard.index'),
				'Loans' => route('admin.loan.index'),
				'Loan for: ' . $loan->loanee->name => null,
			],
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
