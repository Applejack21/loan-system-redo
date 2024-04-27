<?php

namespace App\Actions\Loan;

use App\Models\Loan;

class DeleteLoan
{
    public function execute(Loan $loan): Loan
    {
        $loan->delete();

        return tap($loan)->refresh();
    }
}
