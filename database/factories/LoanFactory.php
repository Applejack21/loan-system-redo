<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create(['type' => 'customer']);
        $admin = User::query()->adminUsers()->inRandomOrder()->first();
        $approved = $this->faker->boolean();

        return [
            'created_by_user_id' => $user,
            'last_updated_by_user_id' => $user,
            'approved_by_user_id' => $approved ? $admin : null,
            'loanee_id' => $user,
            'status' => $approved ? 'approved' : 'requested',
            'reference' => $this->faker->unique()->uuid(),
            'approval_date' => $approved ? now()->addMinutes(mt_rand(1, 100))->toDateTimeString() : null,
            'start_date' => now()->toDateTimeString(),
            'end_date' => $this->faker->dateTimeBetween(today()->addDay(), today()->addDays(7)),
            'date_returned' => $this->faker->boolean() ? $this->faker->dateTimeBetween(today()->addDay(3), today()->addDays(15)) : null,
        ];
    }
}
