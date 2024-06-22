<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$user = User::factory()->create(['type' => 'customer']);
		$equipment = Equipment::factory()->create();

		return [
			'created_by_user_id' => $user->id,
			'last_updated_by_user_id' => $user->id,
			'equipment_id' => $equipment->id,
			'rating' => $this->faker->randomElement([0, 0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5]),
			'content' => $this->faker->realText(),
		];
	}
}
