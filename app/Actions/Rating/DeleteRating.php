<?php

namespace App\Actions\Rating;

use App\Models\Rating;

class DeleteRating
{
	public function execute(Rating $rating): Rating
	{
		$rating->delete();

		return tap($rating)->refresh();
	}
}
