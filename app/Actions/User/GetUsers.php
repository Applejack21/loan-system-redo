<?php

namespace App\Actions\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetUsers
{
    public function execute(): AnonymousResourceCollection
    {
        $users = User::paginate();

        return UserResource::collection($users);
    }
}
