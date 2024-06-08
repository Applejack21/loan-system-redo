<?php

namespace App\Actions\Category;

use App\Actions\General\SyncMedia;
use App\Actions\General\SyncToPivot;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreateCategory
{
    public function execute(array $request): Category
    {
        $this->validate($request);

        $equipments = $request['equipments'] ?? null;
        $image = $request['image'] ?? null;

        unset($request['equipments']);
        unset($request['image']);

        $category = Category::create([
            ...$request,
            'slug' => Str::slug($request['slug']),
            'created_by_user_id' => auth()->user()->id,
            'last_updated_by_user_id' => auth()->user()->id,
        ]);

        // Link equipment to this category.
        if (isset($equipments) && ! is_null($equipments) && is_array($equipments)) {
            (new SyncToPivot())->addData($equipments, $category, 'equipments');
        }

        // Add an image to this category if we have one passed.
        if (isset($image) && ! is_null($image)) {
            (new SyncMedia())->execute($category, $image['data'], 'image');
        }

        return tap($category)->refresh();
    }

    private function validate(array $request): array
    {
        return Validator::validate($request, [
            'name' => ['required', 'unique:categories,name', 'max:255'],
            'slug' => ['required', 'unique:categories,slug', 'max:255'],
            'image' => ['sometimes'],
            'equipments' => ['sometimes', 'array'],
        ]);
    }
}
