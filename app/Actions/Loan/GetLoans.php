<?php

namespace App\Actions\Loan;

use App\Http\Resources\LoanResource;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetLoans
{
	public function execute(Request $request, array $load = [], array $count = []): AnonymousResourceCollection
	{
		$loans = Loan::search($request->search)
			->orderBy('created_at')
			->paginate()
			->appends(['query' => null]);

		if ($load) {
			$loans->loadMissing($load);
		}

		if ($count) {
			$loans->loadCount($count);
		}

		return LoanResource::collection($loans);
	}
}
