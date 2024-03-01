<?php

namespace App\Models;

use App\Helpers\StatusHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Engines\Engine;
use Laravel\Scout\EngineManager;
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
			'denied_by_name' => $this->deniedBy->name,
			'approved_by_name' => $this->approvedBy->name,
			'loanee_name' => $this->loanee->name,
			'equipment_count' => $this->equipments->count(),
			'status' => $this->status,
		];
	}

	/**
	 * Use the collection search engine on this model to be able to search relationships.
	 */
	public function searchableUsing(): Engine
    {
        return app(EngineManager::class)->engine('collection');
    }
}
