<?php

namespace App\Actions\User;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;

class CreateUser
{
    public function execute(array $request): User
    {
        $user = (new CreateNewUser())->create($request);

        return tap($user)->refresh();
    }
}
