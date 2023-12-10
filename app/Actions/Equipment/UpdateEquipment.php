<?php

namespace App\Actions\Equipment;

use App\Models\Equipment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateEquipment
{
    public function execute(Equipment $equipment, array $request): Equipment
    {
        $this->validate($request, $equipment);

        $equipment->update([
            ...$request,
			'last_updated_by_user_id' => auth()->user()->id,
        ]);

        return tap($equipment)->refresh();
    }

    private function validate(array $request, Equipment $equipment): array
    {
        return Validator::validate($request, [
            'category_id' => 'sometimes|required|exists:categories,id',
            'location_id' => 'sometimes|required|exists:locations,id',
            'name' => 'sometimes|required|max:255',
            'code' => ['sometimes', 'nullable', Rule::unique('equipments', 'code')->ignore($equipment->id), 'max:255'],
            'description' => 'sometimes|nullable',
            'price' => 'sometimes|required',
            'details' => 'sometimes|nullable',
            'amount' => 'sometimes|required|integer',
        ]);
    }
}
