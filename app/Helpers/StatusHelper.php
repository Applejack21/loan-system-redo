<?php

namespace App\Helpers;

use App\Models\Loan;

class StatusHelper
{
    const STATUS_REQUESTED = 'requested'; // default status

    const STATUS_DENIED = 'denied'; // if loan is denied by an admin

    const STATUS_APPROVED = 'approved'; // if loan is approved by an admin

    const STATUS_OUT_ON_LOAN = 'out on loan'; // if out on loan (i.e loanee has collected it)

    const STATUS_OVERDUE = 'overdue'; // if loan is overdue

    const STATUS_PARTIALLY_RETURNED = 'partially returned'; // if some of the loan items have been returned (but not all)

    const STATUS_COMPLETED = 'complete'; // if fully complete

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
        // TODO: this functionality

        // return the request data
        return $request;
    }
}
