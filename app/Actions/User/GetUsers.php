<?php

namespace App\Actions\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Laravel\Scout\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetUsers
{
	public function execute(array|null $request): AnonymousResourceCollection
	{
		$users = User::search($request['search'] ?? '')
			->when($request['type'] ?? false, function (Builder $query, string $type) {
				if($type === 'all') {
					return;
				}

				$query->where('type', $type);
			})
			->orderBy($request['order_by'] ?? 'name')
			->paginate($request['per_page'] ?? 15)
			->appends(['query' => null]);

		return UserResource::collection($users);
	}
}
