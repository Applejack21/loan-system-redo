<?php

namespace App\Actions\Loan;

use App\Actions\General\SyncToPivot;
use App\Helpers\StatusHelper;
use App\Models\Loan;
use App\Rules\EquipmentInStock;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateLoan
{
    public function execute(Loan $loan, array $request): Loan
    {
        $this->validate($request, $loan);

        // If the status passed is the same as the current status, determine if it should be changed.
        // This is here in case the user forgot to update it (but should have done).
        if (Str::lower($request['status']) == Str::lower($loan->status)) {
            $request = StatusHelper::determineNewStatus($request, $loan);
        }

        $equipments = $request['equipments'] ?? [];
        unset($request['equipments']);

        $loan->update([
            ...$request,
            'last_updated_by_user_id' => auth()->user()->id,
            'approved_by_user_id' => Str::lower(trim($request['status'])) === 'approved' ? auth()->user()->id : null,
            'denied_by_user_id' => Str::lower(trim($request['status'])) === 'denied' ? auth()->user()->id : null,
        ]);

        if (is_array($equipments) && ! empty($equipments)) {
            (new SyncToPivot())->addData($equipments, $loan, 'equipments');
        }

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
            'date_returned' => ['nullable', 'date', 'after_or_equal:start_date'],
            'date_collected' => ['nullable', 'date', 'after_or_equal:start_date'],
            'equipments' => ['required', 'array'],
            'equipments.*.equipment_id' => ['required', 'exists:equipment,id', new EquipmentInStock], // Make sure each one is still in stock.
            'equipments.*.quantity' => ['required', 'integer'],
            'equipments.*.returned' => ['required', 'boolean'],
        ], [
            'status.in' => 'Status should be one of the following: ' . implode(', ', StatusHelper::allStatuses()),
            'equipments.*.equipment_id.required' => 'Please select an equipment for equipment #:position.',
            'equipments.*.equipment_id.exists' => 'The equipment selected at #:position doesn\'t exist.',
            'equipments.*.quantity.required' => 'Please enter a quantity for equipment #:position.',
            'equipments.*.quantity.integer' => 'Please enter a valid quantity integer for equipment #:position.',
            'equipments.*.returned.required' => 'Please pass in a returned array key for equipment #:position.',
            'equipments.*.returned.boolean' => 'Please pass in a valid boolean for returned key for equipment #:position.',
        ]);
    }
}
