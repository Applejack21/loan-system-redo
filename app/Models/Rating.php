<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Rating extends Model
{
	use HasFactory;
	use Searchable;
	use SoftDeletes;

	protected $guarded = [];

	protected $casts = [
		'rating' => 'decimal:1',
	];

	/**
	 * Return the user who created this location.
	 */
	public function createdBy(): BelongsTo
	{
		return $this->belongsTo(User::class, 'created_by_user_id');
	}

	/**
	 * Return the user who updated this location.
	 */
	public function updatedBy(): BelongsTo
	{
		return $this->belongsTo(User::class, 'last_updated_by_user_id');
	}

	/**
	 * Return the equipment the rating is for.
	 */
	public function equipment(): BelongsTo
	{
		return $this->belongsTo(Equipment::class);
	}

	/**
	 * Get the indexable data array for the model.
	 *
	 * @return array<string, mixed>
	 */
	public function toSearchableArray(): array
	{
		return [
			'rating' => $this->rating,
			'content' => $this->content,
		];
	}
}
