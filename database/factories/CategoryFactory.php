<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create(['type' => 'admin']);
        $name = $this->faker->unique()->words(asText: true);

        return [
            'created_by_user_id' => $user->id,
            'last_updated_by_user_id' => $user->id,
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
