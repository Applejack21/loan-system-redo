<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateUser
{
	public function execute(User $user, array $request): User
	{
		$this->validate($request, $user);

		$user->update([
			...$request,
		]);

		return tap($user)->refresh();
	}

	private function validate(array $request, User $user): array
	{
		return Validator::validate($request, [
			'name' => 'required|max:255',
			'email' => ['required', Rule::unique('users')->ignore($user->id), 'max:255', 'email'],
			'role' => 'required|string|in:customer,admin',
		]);
	}
}
