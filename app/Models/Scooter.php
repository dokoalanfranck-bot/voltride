<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scooter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price_hour',
        'price_minute',
        'price_day',
        'battery_level',
        'status',
        'is_active',
        'max_speed',
        'qr_code',
        'location',
    ];

    protected $casts = [
        'price_hour' => 'decimal:2',
        'price_minute' => 'decimal:2',
        'price_day' => 'decimal:2',
        'max_speed' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(ScooterImage::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRating(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available' && $this->is_active;
    }
}
