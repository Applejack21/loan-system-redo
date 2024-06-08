<?php

namespace App\Actions\Category;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetCategories
{
    /**
     * Get data for this model.
     *
     * @param  Request  $request  The request data, mainly for search or filtering.
     * @param  bool  $paginate  Decide if the data should be paginated. Default is true.
     * @param  array  $loan  Load relationship data of the passed relationships in the array.
     * @param  array  $count  Count relationship data of the passed relationships in the array.
     */
    public function execute(Request $request, $paginate = true, array $load = [], array $count = []): AnonymousResourceCollection
    {
        $categories = Category::search($request->search)
            ->orderBy('name');

        if ($paginate) {
            $categories = $categories->paginate($request->perPage ?? 15, page: $request->page ?? 1)
                ->appends(['query' => null]);
        } else {
            $categories = $categories->get();
        }

        if (count($load) > 0) {
            $categories->loadMissing($load);
        }

        if (count($count) > 0) {
            $categories->loadCount($count);
        }

        return CategoryResource::collection($categories);
    }
}
