<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipment>
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create(['type' => 'admin']);
        $name = $this->faker->words(asText: true);

        return [
            'created_by_user_id' => $user->id,
            'last_updated_by_user_id' => $user->id,
            'location_id' => Location::factory()->create(),
            'name' => $name,
            'slug' => Str::slug($name),
            'code' => $this->faker->boolean() ? Str::uuid()->toString() : null,
            'description' => $this->faker->boolean() ? $this->faker->realText() : null,
            'price' => $this->faker->randomFloat(min: 10, max: 50),
            'details' => null,
            'amount' => $this->faker->randomDigitNotZero(),
        ];
    }
}
