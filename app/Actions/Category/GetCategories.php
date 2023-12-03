<?php

namespace App\Actions\Category;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetCategories
{
	public function execute(Request $request): AnonymousResourceCollection
	{
		$categories = Category::search($request->search)
			->orderBy('name')
			->paginate()
			->appends(['query' => null]);

		return CategoryResource::collection($categories);
	}
}
