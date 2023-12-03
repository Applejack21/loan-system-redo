<?php

namespace App\Actions\Category;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateCategory
{
	public function execute(Category $category, array $request): Category
	{
		$this->validate($request, $category);

		$category->update([
			...$request,
			'last_updated_by_user_id' => auth()->user()->id,
		]);

		return tap($category)->refresh();
	}

	private function validate(array $request, Category $category): array
	{
		return Validator::validate($request, [
			'name' => ['required', Rule::unique('categories', 'name')->ignore($category->id), 'max:255'],
			'slug' => ['required', Rule::unique('categories', 'slug')->ignore($category->id), 'max:255'],
		]);
	}
}
