<?php

namespace App\Actions\Location;

use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetLocations
{
	public function execute(Request $request, array $load = [], array $count = []): AnonymousResourceCollection
	{
		$locations = Location::search($request->search)
			->orderBy('name')
			->paginate()
			->appends(['query' => null]);

		if ($load) {
			$locations->loadMissing($load);
		}

		if ($count) {
			$locations->loadCount($count);
		}

		return LocationResource::collection($locations);
	}
}
