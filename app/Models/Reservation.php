<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'scooter_id',
        'start_time',
        'end_time',
        'total_price',
        'status',
        'payment_status',
        'payment_method',
        'delay_minutes',
        'delay_fee',
        'guest_name',
        'guest_phone',
        'guest_email',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'total_price' => 'decimal:2',
        'delay_fee' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scooter(): BelongsTo
    {
        return $this->belongsTo(Scooter::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function calculatePrice(): decimal|float
    {
        if (!$this->end_time) {
            return 0;
        }

        $minutes = $this->start_time->diffInMinutes($this->end_time);
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        // Price calculation: hours + remaining minutes
        $price = ($hours * $this->scooter->price_hour) + ($remainingMinutes * $this->scooter->price_minute);
        
        if ($this->delay_minutes > 0) {
            $price += ($this->delay_minutes * $this->scooter->price_minute);
        }

        return $price;
    }

    public function markAsCompleted(): void
    {
        $this->status = 'completed';
        $this->total_price = $this->calculatePrice();
        $this->save();
    }
}
