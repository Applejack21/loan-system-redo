<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Category extends Model
{
	use HasFactory;
	use SoftDeletes;
	use Searchable;

	protected $guarded  = [];

	public function createdBy(): BelongsTo
	{
		return $this->belongsTo(User::class, 'created_by_user_id');
	}

	public function updatedBy(): BelongsTo
	{
		return $this->belongsTo(User::class, 'last_updated_by_user_id');
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
			'slug' => $this->slug,
		];
	}
}
