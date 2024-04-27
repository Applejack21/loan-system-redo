<?php

namespace App\Models;

use App\Helpers\StatusHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Laravel\Scout\Searchable;

class Loan extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'approval_date' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'date_returned' => 'datetime',
    ];

    protected $attributes = [
        'status' => StatusHelper::STATUS_REQUESTED,
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_updated_by_user_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    public function deniedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'denied_by_user_id');
    }

    public function loanee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'loanee_id');
    }

    public function equipments(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'equipment_loans')
            ->withPivot(['quantity', 'returned'])
            ->withTimestamps();
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'loanee_name' => $this->loanee->name,
            'status' => $this->status,
            'reference' => $this->reference,
            'start_date' => $this->start_date->tz(config('app.convert_timezone'))->format(config('app.date_time_format')),
            'end_date' => $this->end_date->tz(config('app.convert_timezone'))->format(config('app.date_time_format')),
        ];
    }

    /**
     * Use the collection search engine on this model to be able to search relationships.
     */
    public function searchableUsing(): Engine
    {
        return app(EngineManager::class)->engine('collection');
    }

    /**
     * Return the equipments/count of collection that haven't been returned for this loan.
     *
     * @param  int  $count  Decide if the number of equipments should be returned instead. Default is false.
     */
    public function nonReturnedEquipments($count = false): Collection|int
    {
        $equipments = $this->equipments->filter(function (Equipment $equipment) {
            return $equipment->pivot->returned === 0;
        });

        $this->unsetRelation('equipments');

        if ($count) {
            return $equipments->count();
        }

        return $equipments;
    }

    /**
     * Return true/false if all equipments for this loan have been returned.
     */
    public function fullyReturned(): bool
    {
        $fullyReturned = $this->equipments->every(function (Equipment $equipment) {
            return $equipment->pivot->returned === 1;
        });

        $this->unsetRelation('equipments');

        return $fullyReturned;
    }
}
