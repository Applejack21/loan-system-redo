<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\Loan;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Loan::factory()
            // Create a random amount of loans
            ->count(mt_rand(5, 10))
            ->create()
            ->each(function ($loan) {
                // Attach 1-3 equipment to each loan, each having a random quantity and random returned boolean
                $equipments = Equipment::factory()->count(mt_rand(1, 3))->create();

                $equipments->each(function ($equipment) use ($loan) {
                    $loan->equipments()->attach($equipment->id, [
                        'quantity' => mt_rand(1, $equipment->amount), // Random quantity between 1 and how many we have of this equipment.
                        'returned' => (bool) mt_rand(0, 1),
                    ]);
                });
            });
    }
}
