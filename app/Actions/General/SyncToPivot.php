<?php

namespace App\Actions\General;

use Illuminate\Database\Eloquent\Model;

class SyncToPivot
{
    /**
     * Add data to a pivot table.
     *
     * @param  array  $data Data to add to pivot table, usually an array of IDs.
     * @param  Model  $model We're going to be using for this pivot table.
     * @param  string  $relationshipName Name of the relationship in the model file.
     */
    public function addData(array $data, Model $model, string $relationshipName): void
    {
        $model->$relationshipName()->sync($data);
    }
}
