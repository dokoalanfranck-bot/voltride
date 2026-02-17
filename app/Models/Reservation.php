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
        'delay_minutes',
        'delay_fee',
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

        $hours = $this->start_time->diffInHours($this->end_time);
        $days = intdiv($hours, 24);
        $remainingHours = $hours % 24;

        $price = ($days * $this->scooter->price_day) + ($remainingHours * $this->scooter->price_hour);
        
        if ($this->delay_minutes > 0) {
            $delayHours = ceil($this->delay_minutes / 60);
            $price += ($delayHours * $this->scooter->price_hour);
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
