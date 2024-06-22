<?php

namespace App\Actions\Rating;

use App\Models\Rating;
use Illuminate\Support\Facades\Validator;

class CreateRating
{
	public function execute(array $request): Rating
	{
		$this->validate($request);

		$rating = Rating::create([
			...$request,
			'created_by_user_id' => auth()->user()->id,
			'last_updated_by_user_id' => auth()->user()->id,
		]);

		return tap($rating)->refresh();
	}

	private function validate(array $request): array
	{
		return Validator::validate($request, [
			'equipment_id' => 'required|exists:equipment,id',
			'rating' => 'required|decimal:1',
			'content' => 'nullable',
		]);
	}
}
