<?php

namespace App\Policies;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RatingPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any models.
	 */
	public function viewAny(User $user): Response
	{
		return $user->isAdmin() ? $this->allow() : $this->deny('You do not have permission to do this.');
	}

	/**
	 * Determine whether the user can view the model.
	 */
	public function view(User $user, Rating $rating): Response
	{
		if ($user->id === $rating->created_by_user_id || $user->isAdmin()) {
			return $this->allow();
		}

		return $this->deny('You do not have permission to do this.');
	}

	/**
	 * Determine whether the user can create models.
	 */
	public function create(User $user): Response
	{
		return $this->allow();
	}

	/**
	 * Determine whether the user can update the model.
	 */
	public function update(User $user, Rating $rating): Response
	{
		if ($user->id === $rating->created_by_user_id || $user->isAdmin()) {
			return $this->allow();
		}

		return $this->deny('You do not have permission to do this.');
	}

	/**
	 * Determine whether the user can delete the model.
	 */
	public function delete(User $user, Rating $rating): Response
	{
		return $user->isAdmin() ? $this->allow() : $this->deny('You do not have permission to do this.');
	}

	/**
	 * Determine whether the user can restore the model.
	 */
	public function restore(User $user, Rating $rating): Response
	{
		return $user->isAdmin() ? $this->allow() : $this->deny('You do not have permission to do this.');
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 */
	public function forceDelete(User $user, Rating $rating): Response
	{
		return $user->isAdmin() ? $this->allow() : $this->deny('You do not have permission to do this.');
	}
}
