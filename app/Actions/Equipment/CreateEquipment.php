<?php

namespace App\Actions\Equipment;

use App\Actions\General\SyncToPivot;
use App\Models\Equipment;
use Illuminate\Support\Facades\Validator;

class CreateEquipment
{
    public function execute(array $request): Equipment
    {
        $this->validate($request);

        $categories = $request['categories'] ?? null;
        unset($request['categories']);

        $equipment = Equipment::create([
            ...$request,
            'created_by_user_id' => auth()->user()->id,
            'last_updated_by_user_id' => auth()->user()->id,
        ]);

        // link categories to this equipment
        if (isset($categories) && ! is_null($categories) && is_array($categories)) {
            (new SyncToPivot())->addData($categories, $equipment, 'categories');
        }

        return tap($equipment)->refresh();
    }

    private function validate(array $request): array
    {
        return Validator::validate($request, [
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|max:255',
            'code' => 'nullable|unique:equipments,code|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'details' => 'nullable|array',
            'amount' => 'required|integer',
            'categories' => 'sometimes|array',
        ]);
    }
}
