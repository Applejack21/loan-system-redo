<?php

namespace App\Actions\Loan;

use App\Http\Resources\LoanResource;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Laravel\Scout\Builder;

class GetLoans
{
    /**
     * Get data for this model.
     *
     * @param  Request  $request  The request data, mainly for search or filtering.
     * @param  array  $loan  Load relationship data of the passed relationships in the array.
     * @param  array  $count  Count relationship data of the passed relationships in the array.
     * @param  array  $map  Only get the data that is passed in the array. Pass an array containing fields for this model. E.g ['id', 'name', 'email'].
     */
    public function execute(Request $request, array $load = [], array $count = [], array $map = []): AnonymousResourceCollection
    {
        $loans = Loan::search($request->search)
            ->when($request->status ?? false, function (Builder $query, string $status) {
                if ($status === 'all') {
                    return;
                }

                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate()
            ->appends(['query' => null]);

        if ($load) {
            $loans->loadMissing($load);
        }

        if ($count) {
            $loans->loadCount($count);
        }

        if ($map) {
            $loans = $loans->map(function ($loan) use ($map) {
                return $loan->only($map);
            });
        }

        return LoanResource::collection($loans);
    }
}
