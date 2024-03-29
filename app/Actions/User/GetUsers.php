<?php

namespace App\Actions\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Laravel\Scout\Builder;

class GetUsers
{
    public function execute(Request $request, array $load = [], array $count = []): AnonymousResourceCollection
    {
        $users = User::search($request->search)
            ->when($request->type ?? false, function (Builder $query, string $type) {
                if ($type === 'all') {
                    return;
                }

                $query->where('type', $type);
            })
            ->orderBy('name')
            ->paginate()
            ->appends(['query' => null]);

        if ($load) {
            $users->loadMissing($load);
        }

        if ($count) {
            $users->loadCount($count);
        }

        return UserResource::collection($users);
    }
}
