<?php

namespace App\Actions\Location;

use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetLocations
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
        $locations = Location::search($request->search)
            ->orderBy('name');

        if ($paginate) {
            $locations = $locations->paginate($request->perPage ?? 15, page: $request->page ?? 1)
                ->appends(['query' => null]);
        } else {
            $locations = $locations->get();
        }

        if (count($load) > 0) {
            $locations->loadMissing($load);
        }

        if (count($count) > 0) {
            $locations->loadCount($count);
        }

        return LocationResource::collection($locations);
    }
}
