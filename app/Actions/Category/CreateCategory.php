<?php

namespace App\Actions\Category;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CreateCategory
{
    public function execute(array $request): Category
    {
        $this->validate($request);

        $category = Category::create([
            ...$request,
            'created_by_user_id' => auth()->user()->id,
            'last_updated_by_user_id' => auth()->user()->id,
        ]);

        return tap($category)->refresh();
    }

    private function validate(array $request): array
    {
        return Validator::validate($request, [
            'name' => 'required|unique:categories,name|max:255',
            'slug' => 'required|unique:categories,slug|max:255',
        ]);
    }
}
