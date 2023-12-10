<?php

namespace App\Actions\Equipment;

use App\Models\Equipment;
use Illuminate\Support\Facades\Validator;

class CreateEquipment
{
    public function execute(array $request): Equipment
    {
        $this->validate($request);

        $equipment = Equipment::create([
            ...$request,
			'created_by_user_id' => auth()->user()->id,
            'last_updated_by_user_id' => auth()->user()->id,
        ]);

        return tap($equipment)->refresh();
    }

    private function validate(array $request): array
    {
        return Validator::validate($request, [
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|max:255',
            'code' => 'nullable|unique:equipments,code|max:255',
            'description' => 'nullable',
            'price' => 'required',
            'details' => 'nullable',
            'amount' => 'required|integer',
        ]);
    }
}
