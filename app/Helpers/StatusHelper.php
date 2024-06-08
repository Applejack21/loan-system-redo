<?php

namespace App\Helpers;

use App\Models\Loan;
use Carbon\Carbon;

class StatusHelper
{
    const STATUS_REQUESTED = 'requested'; // Default status.

    const STATUS_DENIED = 'denied'; // If loan is denied by an admin.

    const STATUS_APPROVED = 'approved'; // If loan is approved by an admin.

    const STATUS_OUT_ON_LOAN = 'out on loan'; // If out on loan (i.e loanee has collected it).

    const STATUS_OVERDUE = 'overdue'; // If loan is overdue.

    const STATUS_PARTIALLY_RETURNED = 'partially returned'; // If some of the loan items have been returned (but not all).

    const STATUS_COMPLETED = 'complete'; // If fully complete.

    /**
     * Return all enums.
     */
    public static function allStatuses(): array
    {
        return [
            self::STATUS_REQUESTED,
            self::STATUS_DENIED,
            self::STATUS_APPROVED,
            self::STATUS_OUT_ON_LOAN,
            self::STATUS_OVERDUE,
            self::STATUS_PARTIALLY_RETURNED,
            self::STATUS_COMPLETED,
        ];
    }

    /**
     * Determine what the new status for the loan should be.
     *
     * @param  array  $request  The request data to update the loan.
     * @param  Loan  $loan  The current loan data.
     */
    public static function determineNewStatus(array $request, Loan $loan): array
    {
        // Collect the equipments.
        $equipments = collect($request['equipments']);

        // Running count.
        $returnCount = 0;

        // Check how many equipments has been marked as "Returned".
        $equipments->each(function ($equipment) use (&$returnCount) {
            if ($equipment['returned']) {
                $returnCount++;
            }
        });

        // All has been returned, set as "Completed".
        if ($returnCount == $equipments->count()) {
            $request['status'] = self::STATUS_COMPLETED;
            $request['date_returned'] = isset($request['date_returned']) && ! is_null($request['date_returned']) ? $request['date_returned'] : now()->toDateTimeString();

            return $request;
        }

        // If some of them are returned, set as "Partially Returned".
        if ($returnCount > 0 && $returnCount < $equipments->count()) {
            $request['status'] = self::STATUS_PARTIALLY_RETURNED;

            return $request;
        }

        // If the loan is overdue, set as "Overdue".
        if (Carbon::parse($request['end_date'])->lte(now())) {
            $request['status'] = self::STATUS_OVERDUE;

            return $request;
        }

        // Return the original request data.
        return $request;
    }
}
