<?php

namespace App\Actions\Category;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetCategories
{
	public function execute(): AnonymousResourceCollection
	{
		$categories = Category::search($request['search'] ?? '')
			->orderBy($request['order_by'] ?? 'name')
			->paginate($request['per_page'] ?? 15)
			->appends(['query' => null]);

		return CategoryResource::collection($categories);
	}
}
