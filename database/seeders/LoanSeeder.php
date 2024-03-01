<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\Loan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		Loan::factory()
			// create a random amount of loans
			->count(mt_rand(1, 10))
			->create()
			->each(function ($loan) {
				// attach 1-3 equipment to each loan, each having a random quantity and random returned boolean
				$equipments = Equipment::factory()->count(mt_rand(1, 3))->create();

				$equipments->each(function ($equipment) use ($loan) {
					$loan->equipments()->attach($equipment->id, [
						'quantity' => mt_rand(1, 5),
						'returned' => (bool)mt_rand(0, 1),
					]);
				});
			});
	}
}
