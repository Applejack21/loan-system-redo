<?php

namespace App\Actions\Equipment;

use App\Actions\General\SyncMedia;
use App\Actions\General\SyncToPivot;
use App\Models\Equipment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreateEquipment
{
    public function execute(array $request): Equipment
    {
        $this->validate($request);

        $categories = $request['categories'] ?? null;
        $images = $request['images'] ?? null;

        unset($request['categories']);
        unset($request['images']);

        $equipment = Equipment::create([
            ...$request,
            'slug' => Str::slug($request['slug']),
            'created_by_user_id' => auth()->user()->id,
            'last_updated_by_user_id' => auth()->user()->id,
        ]);

        // link categories to this equipment
        if (isset($categories) && ! is_null($categories) && is_array($categories)) {
            (new SyncToPivot())->addData($categories, $equipment, 'categories');
        }

        // link images to this equipment
        if (isset($images) && ! is_null($images) && is_array($images) && count($images) > 0) {
            foreach ($images as $image) {
                (new SyncMedia())->execute($equipment, $image['data'], 'images');
            }
            // (new SyncMedia())->addFromFile($equipment, $images, 'images', storage_path('app/public/temp/uploads/'));
        }

        return tap($equipment)->refresh();
    }

    private function validate(array $request): array
    {
        return Validator::validate($request, [
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|max:255',
            'slug' => 'required|unique:equipments,slug|max:255',
            'code' => 'nullable|unique:equipments,code|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'details' => 'nullable|array',
            'amount' => 'required|integer',
            'categories' => 'sometimes|array',
            'images' => 'sometimes|array',
        ]);
    }
}
