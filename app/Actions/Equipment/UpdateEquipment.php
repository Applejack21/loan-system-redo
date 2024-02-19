<?php

namespace App\Actions\Equipment;

use App\Actions\General\SyncToPivot;
use App\Models\Equipment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateEquipment
{
    public function execute(Equipment $equipment, array $request): Equipment
    {
        $this->validate($request, $equipment);

        $categories = $request['categories'] ?? null;
        unset($request['categories']);

        $equipment->update([
            ...$request,
            'last_updated_by_user_id' => auth()->user()->id,
        ]);

        // link categories to this equipment
        if (isset($categories) && ! is_null($categories) && is_array($categories)) {
            (new SyncToPivot())->addData($categories, $equipment, 'categories');
        }

        return tap($equipment)->refresh();
    }

    private function validate(array $request, Equipment $equipment): array
    {
        return Validator::validate($request, [
            'location_id' => 'sometimes|required|exists:locations,id',
            'name' => 'sometimes|required|max:255',
            'code' => ['sometimes', 'nullable', Rule::unique('equipments', 'code')->ignore($equipment->id), 'max:255'],
            'description' => 'sometimes|nullable',
            'price' => 'sometimes|required|numeric',
            'details' => 'sometimes|nullable|array',
            'amount' => 'sometimes|required|integer',
            'categories' => 'sometimes|array',
        ]);
    }
}
