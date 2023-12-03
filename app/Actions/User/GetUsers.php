<?php

namespace App\Actions\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Laravel\Scout\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetUsers
{
	public function execute(Request $request): AnonymousResourceCollection
	{
		$users = User::search($request->search)
			->when($request->type ?? false, function (Builder $query, string $type) {
				if($type === 'all') {
					return;
				}

				$query->where('type', $type);
			})
			->orderBy('name')
			->paginate()
			->appends(['query' => null]);

		return UserResource::collection($users);
	}
}
