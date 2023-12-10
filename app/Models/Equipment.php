<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Number;
use Laravel\Scout\Searchable;

class Equipment extends Model
{
	use HasFactory;
	use Searchable;
	use SoftDeletes;

	protected $table = 'equipments';

	protected $guarded = [];

	protected $casts = [
		'details' => 'array',
	];

	public function createdBy(): BelongsTo
	{
		return $this->belongsTo(User::class, 'created_by_user_id');
	}

	public function updatedBy(): BelongsTo
	{
		return $this->belongsTo(User::class, 'last_updated_by_user_id');
	}

	public function category(): BelongsTo
	{
		return $this->belongsTo(Category::class);
	}

	public function location(): BelongsTo
	{
		return $this->belongsTo(Location::class);
	}

	/**
	 * Get the indexable data array for the model.
	 *
	 * @return array<string, mixed>
	 */
	public function toSearchableArray(): array
	{
		return [
			'name' => $this->name,
			'code' => $this->slug,
		];
	}

	/**
	 * Return the price of the equipment in currency and locale set in env.
	 */
	public function priceFormatted()
	{
		return Number::currency($this->price, in: config('app.currency'), locale: config('app.locale'));
	}

	/**
	 * Calculate the amount of products left in stock based on how many we have in total compared to how many on loan
	 */
	public function calculateAmountInStock()
	{
		// TODO: this functionality once loan model is done, for now just return amount
		return $this->amount;
	}
}
