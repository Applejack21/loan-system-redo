<?php

namespace App\Rules;

use App\Models\Equipment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EquipmentInStock implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Get equipment ID.
        $equipmentId = $value;

        // Get the equipment from the database.
        $equipment = Equipment::where('id', $equipmentId)->first();

        // If the equipment is out of stock, return a validation error.
        if ($equipment->outOfStock()) {
            $fail('The equipment "' . $equipment->name . '" is out of stock.');
        }
    }
}
