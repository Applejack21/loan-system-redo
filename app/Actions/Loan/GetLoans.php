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
     * @param  bool  $paginate  Decide if the data should be paginated. Default is true.
     * @param  array  $loan  Load relationship data of the passed relationships in the array.
     * @param  array  $count  Count relationship data of the passed relationships in the array.
     */
    public function execute(Request $request, bool $paginate = true, array $load = [], array $count = []): AnonymousResourceCollection
    {
        $loans = Loan::search($request->search)
            ->when($request->status ?? false, function (Builder $query, string $status) {
                if ($status === 'all') {
                    return;
                }

                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc');

        if ($paginate) {
            $loans = $loans->paginate($request->perPage ?? 15, page: $request->page ?? 1)
                ->appends(['query' => null]);
        } else {
            $loans = $loans->get();
        }

        if (count($load) > 0) {
            $loans->loadMissing($load);
        }

        if (count($count) > 0) {
            $loans->loadCount($count);
        }

        return LoanResource::collection($loans);
    }
}
