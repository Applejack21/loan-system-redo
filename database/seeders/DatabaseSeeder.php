<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		// Only add the user to the database if they don't exist.
		if (!User::where('email', 'test@example.com')->first()) {
			User::factory()->create([
				'name' => 'Test User',
				'email' => 'test@example.com',
				'type' => 'admin',
			]);
		}

		User::factory(9)->create();

		$this->call([
			CategorySeeder::class,
			LocationSeeder::class,
			EquipmentSeeder::class,
			LoanSeeder::class,
			RatingSeeder::class,
		]);
	}
}
