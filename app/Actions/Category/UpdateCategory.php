<?php

namespace App\Actions\Category;

use App\Actions\General\SyncMedia;
use App\Actions\General\SyncToPivot;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UpdateCategory
{
    public function execute(Category $category, array $request): Category
    {
        $this->validate($request, $category);

        $equipments = $request['equipments'] ?? null;
        $image = $request['image'] ?? null;

        unset($request['equipments']);
        unset($request['image']);

        $category->update([
            ...$request,
            'slug' => Str::slug($request['slug']),
            'last_updated_by_user_id' => auth()->user()->id,
        ]);

        // link equipment to this category
        if (isset($equipments) && ! is_null($equipments) && is_array($equipments)) {
            (new SyncToPivot())->addData($equipments, $category, 'equipments');
        }

        // remove the image if null - i.e the user doesn't want an image
        if (is_null($image)) {
            $category->clearMediaCollection('image');
        }

        // only update the image if its new - i.e. it's an UploadedFile instance
        // data will only be returned if its a new image uploaded
        if (isset($image) && ! is_null($image) && $image['data'] instanceof UploadedFile) {
            // remove current image
            $category->clearMediaCollection('image');

            // save new image
            (new SyncMedia())->execute($category, $image['data'], 'image');
        }

        return tap($category)->refresh();
    }

    private function validate(array $request, Category $category): array
    {
        return Validator::validate($request, [
            'name' => ['required', Rule::unique('categories', 'name')->ignore($category->id), 'max:255'],
            'slug' => ['required', Rule::unique('categories', 'slug')->ignore($category->id), 'max:255'],
            'equipments' => ['sometimes', 'array'],
        ]);
    }
}
