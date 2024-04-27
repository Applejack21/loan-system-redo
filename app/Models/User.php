<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use Searchable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'phone_number',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'address' => 'array',
        'deleted_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function createdCategories(): HasMany
    {
        return $this->hasMany(Category::class, 'id', 'created_by_user_id');
    }

    public function updatedCategories(): HasMany
    {
        return $this->hasMany(Category::class, 'id', 'last_updated_by_user_id');
    }

    public function createdEquipments(): HasMany
    {
        return $this->hasMany(Equipment::class, 'id', 'created_by_user_id');
    }

    public function updatedEquipments(): HasMany
    {
        return $this->hasMany(Equipment::class, 'id', 'last_updated_by_user_id');
    }

    public function createdLoans(): HasMany
    {
        return $this->hasMany(Loan::class, 'id', 'created_by_user_id');
    }

    public function updatedLoans(): HasMany
    {
        return $this->hasMany(Loan::class, 'id', 'last_updated_by_user_id');
    }

    public function approvedLoans(): HasMany
    {
        return $this->hasMany(Loan::class, 'id', 'approved_by_user_id');
    }

    public function deniedLoans(): HasMany
    {
        return $this->hasMany(Loan::class, 'id', 'denied_by_user_id');
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class, 'id', 'loanee_id');
    }

    public function createdLocations(): HasMany
    {
        return $this->hasMany(Location::class, 'id', 'created_by_user_id');
    }

    public function updatedLocations(): HasMany
    {
        return $this->hasMany(Location::class, 'id', 'last_updated_by_user_id');
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
            'email' => $this->email,
            'type' => $this->type,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
        ];
    }

    /**
     * Determine if the user is a customer
     */
    public function isCustomer(): bool
    {
        return strtolower($this->type) == 'customer';
    }

    /**
     * Determine if the user is an admin
     */
    public function isAdmin(): bool
    {
        return strtolower($this->type) == 'admin';
    }

    /**
     * Scope a query to only include customer users
     */
    public function scopeCustomerUsers(Builder $query): void
    {
        $query->where('type', 'admin');
    }

    /**
     * Scope a query to only include admin users
     */
    public function scopeAdminUsers(Builder $query): void
    {
        $query->where('type', 'admin');
    }
}
