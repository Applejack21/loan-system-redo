<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Number;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Equipment extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use Searchable;
    use SoftDeletes;

    // should work without this but doesn't...
    protected $table = 'equipments';

    protected $guarded = [];

    protected $casts = [
        'details' => 'array',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->withResponsiveImages();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('preview')
            ->performOnCollections('images')
            ->nonQueued();
    }

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

    public function loans(): BelongsToMany
    {
        return $this->belongsToMany(Loan::class, 'equipment_loans')
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
            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
            'amount' => $this->amount,
            'details' => $this->details,
        ];
    }

    /**
     * Return the price of the equipment based on currency and locale set in env.
     */
    public function priceFormatted(): string
    {
        return Number::currency($this->price, in: config('app.currency'), locale: config('app.locale'));
    }

    /**
     * Calculate the amount of this equipment left in stock based on how many we have in total compared to how many on loan and not returned.
     */
    public function calculateAmountInStock(): int
    {
        $total = $this->amount;
        $onLoan = $this->loans->filter(function ($loan) {
            return $loan->pivot->returned === 0;
        })->count();

        $this->unsetRelation('loans');

        return $total - $onLoan;
    }

    /**
     * Calculate how many of this equipment is out on loan.
     */
    public function calculateAmountOnLoan(): int
    {
        $count = $this->loans->filter(function ($loan) {
            return $loan->pivot->returned === 0;
        })->count();

        $this->unsetRelation('loans');

        return $count;
    }
}
