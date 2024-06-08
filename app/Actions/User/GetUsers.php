<?php

namespace App\Actions\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Laravel\Scout\Builder;

class GetUsers
{
    /**
     * Get data for this model.
     *
     * @param  Request  $request  The request data, mainly for search or filtering.
     * @param  bool  $paginate  Decide if the data should be paginated. Default is true.
     * @param  array  $loan  Load relationship data of the passed relationships in the array.
     * @param  array  $count  Count relationship data of the passed relationships in the array.
     */
    public function execute(Request $request, bool $paginate = true, array $load = [], array $count = []): AnonymousResourceCollection
    {
        $users = User::search($request->search)
            ->when($request->type ?? false, function (Builder $query, string $type) {
                if ($type === 'all') {
                    return;
                }

                $query->where('type', $type);
            })
            ->orderBy('name');

        if ($paginate) {
            $users = $users->paginate($request->perPage ?? 15, page: $request->page ?? 1)
                ->appends(['query' => null]);
        } else {
            $users = $users->get();
        }

        if (count($load) > 0) {
            $users->loadMissing($load);
        }

        if (count($count) > 0) {
            $users->loadCount($count);
        }

        return UserResource::collection($users);
    }
}
