<?php

namespace App\Actions\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetUsers
{
	public function execute(array|null $request): AnonymousResourceCollection
	{
		$users = User::search($request['search'] ?? '')
			->orderBy('name')
			->paginate()
			->appends(['query' => null]);

		return UserResource::collection($users);
	}
}
