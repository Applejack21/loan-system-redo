<?php

namespace App\Actions\Equipment;

use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetEquipments
{
    public function execute(Request $request, array $load = [], array $count = []): AnonymousResourceCollection
    {
        $equipments = Equipment::search($request->search)
            ->orderBy('name')
            ->paginate()
            ->appends(['query' => null]);

		if($load) {
			$equipments->loadMissing($load);
		}

		if($count) {
			$equipments->loadCount($count);
		}

        return EquipmentResource::collection($equipments);
    }
}
