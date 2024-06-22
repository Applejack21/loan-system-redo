<?php

namespace App\Actions\Rating;

use App\Http\Resources\RatingResource;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetRatings
{
	/**
	 * Get data for this model.
	 *
	 * @param  Request  $request  The request data, mainly for search or filtering.
	 * @param  bool  $paginate  Decide if the data should be paginated. Default is true.
	 * @param  array  $loan  Load relationship data of the passed relationships in the array.
	 * @param  array  $count  Count relationship data of the passed relationships in the array.
	 */
	public function execute(Request $request, $paginate = true, array $load = [], array $count = []): AnonymousResourceCollection
	{
		$ratings = Rating::search($request->search)
			->latest();

		if ($paginate) {
			$ratings = $ratings->paginate($request->perPage ?? 15, page: $request->page ?? 1)
				->appends(['query' => null]);
		} else {
			$ratings = $ratings->get();
		}

		if (count($load) > 0) {
			$ratings->loadMissing($load);
		}

		if (count($count) > 0) {
			$ratings->loadCount($count);
		}

		return RatingResource::collection($ratings);
	}
}
