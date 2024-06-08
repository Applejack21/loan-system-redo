<?php

namespace App\Actions\Equipment;

use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetEquipments
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
        $equipments = Equipment::search($request->search)
            ->orderBy('name');

        if ($paginate) {
            $equipments = $equipments->paginate($request->perPage ?? 15, page: $request->page ?? 1)
                ->appends(['query' => null]);
        } else {
            $equipments = $equipments->get();
        }

        if (count($load) > 0) {
            $equipments->loadMissing($load);
        }

        if (count($count) > 0) {
            $equipments->loadCount($count);
        }

        return EquipmentResource::collection($equipments);
    }
}
