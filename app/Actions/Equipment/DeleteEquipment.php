<?php

namespace App\Actions\Equipment;

use App\Models\Equipment;

class DeleteEquipment
{
    public function execute(Equipment $equipment): Equipment
    {
        $equipment->delete();

        return tap($equipment)->refresh();
    }
}
