<?php

namespace App\Actions\Loan;

use App\Helpers\StatusHelper;
use App\Models\Loan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateLoan
{
	public function execute(Loan $loan, array $request): Loan
	{
		// if the status passed is the same as the current status, determine if it should be changed
		// this is here in case the user forgot to update it (but should have done)
		if (Str::lower($request['status']) == Str::lower($loan->status)) {
			$request = StatusHelper::determineNewStatus($request, $loan);
		}

		$this->validate($request, $loan);

		$loan->update([
			...$request,
			'last_updated_by_user_id' => auth()->user()->id,
		]);

		return tap($loan)->refresh();
	}

	private function validate(array $request, Loan $loan): array
	{
		return Validator::validate($request, [
			'approved_by_user_id' => ['nullable', 'exists:users,id'],
			'denied_by_user_id' => ['nullable', 'exists:users,id'],
			'loanee_id' => ['required', 'exists:users,id'],
			'status' => ['required', 'string', 'max:255', Rule::in(StatusHelper::allStatuses())],
			'denied_reason' => ['nullable', 'string', 'max:255'],
			'approval_date' => ['nullable'],
			'start_date' => ['required', 'date'],
			'end_date' => ['required', 'date', 'after_or_equal:start_date'],
			'date_returned' => ['nullable', 'after_or_equal:start_date'],
		], [
			'status.in' => 'Status should be one of the following: ' . implode(', ', StatusHelper::allStatuses()),
		]);
	}
}
