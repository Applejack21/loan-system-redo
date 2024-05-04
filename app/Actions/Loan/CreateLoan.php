<?php

namespace App\Actions\Loan;

use App\Actions\General\SyncToPivot;
use App\Helpers\StatusHelper;
use App\Models\Loan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\UuidInterface;

class CreateLoan
{
    public function execute(array $request): Loan
    {
        $this->validate($request);

        $equipments = $request['equipments'] ?? [];
        unset($request['equipments']);

        $loan = Loan::create([
            ...$request,
            'reference' => $this->generateUuid(),
            'created_by_user_id' => auth()->user()->id,
            'last_updated_by_user_id' => auth()->user()->id,
            'approved_by_user_id' => Str::lower(trim($request['status'])) === 'approved' ? auth()->user()->id : null,
            'denied_by_user_id' => Str::lower(trim($request['status'])) === 'denied' ? auth()->user()->id : null,
        ]);

        if (is_array($equipments) && ! empty($equipments)) {
            (new SyncToPivot())->addData($equipments, $loan, 'equipments');
        }

        return tap($loan)->refresh();
    }

    private function validate(array $request): array
    {
        return Validator::validate($request, [
            'approved_by_user_id' => ['nullable', 'exists:users,id'],
            'denied_by_user_id' => ['nullable', 'exists:users,id'],
            'loanee_id' => ['required', 'exists:users,id'],
            'status' => ['required', 'string', 'max:255', Rule::in(StatusHelper::allStatuses())],
            'denied_reason' => ['nullable', 'string', 'max:255', 'required_if:status,denied'],
            'start_date' => ['required', 'date'],
            'approval_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'date_returned' => ['nullable', 'date', 'after_or_equal:start_date'],
            'equipments' => ['required', 'array'],
            'equipments.*.equipment_id' => ['required', 'exists:equipments,id'],
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

    private function generateUuid(): UuidInterface
    {
        $uuid = Str::uuid();

        $exists = Loan::where('reference', $uuid)->first();

        if (! $exists) {
            return $uuid;
        }

        return $this->generateUuid();
    }
}
