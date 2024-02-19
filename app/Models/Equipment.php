<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Number;
use Laravel\Scout\Searchable;

class Equipment extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    // should work without this but doesn't...
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

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_equipment');
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
            'amount' => $this->amount,
            'details' => $this->details,
        ];
    }

    /**
     * Return the price of the equipment based on currency and locale set in env.
	 * @return string
     */
    public function priceFormatted(): string
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

    /**
     * Calculate how many of this equipment are out on loan
     */
    public function calculateAmountOnLoan()
    {
        // TODO: this functionality once loan model is done, for now just return 0
        return 0;
    }
}
