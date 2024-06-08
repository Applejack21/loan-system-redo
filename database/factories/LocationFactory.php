<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create(['type' => 'admin']);

        return [
            'created_by_user_id' => $user->id,
            'last_updated_by_user_id' => $user->id,
            'name' => $this->faker->words(asText: true),
            'room_code' => sprintf(
                '%s/%s',
                Str::upper(Str::random(2)) . mt_rand(1, 9),
                mt_rand(1, 25),
            ), // Generate a room code, e.g. FL1/12 means Floor 1, room 12.
        ];
    }
}
