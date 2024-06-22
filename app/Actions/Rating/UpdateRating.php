<?php

namespace App\Actions\Rating;

use App\Models\Rating;
use Illuminate\Support\Facades\Validator;

class UpdateRating
{
	public function execute(Rating $rating, array $request): Rating
	{
		$this->validate($request, $rating);

		$rating->update([
			...$request,
			'last_updated_by_user_id' => auth()->user()->id,
		]);

		return tap($rating)->refresh();
	}

	private function validate(array $request, Rating $rating): array
	{
		return Validator::validate($request, [
			'equipment_id' => 'sometimes|required|exists:equipment,id',
			'rating' => 'sometimes|required|decimal:1',
			'content' => 'nullable',
		]);
	}
}
