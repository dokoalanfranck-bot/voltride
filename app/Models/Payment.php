<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'stripe_payment_id',
        'amount',
        'status',
        'stripe_response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function markAsCompleted(string $stripePaymentId): void
    {
        $this->status = 'completed';
        $this->stripe_payment_id = $stripePaymentId;
        $this->save();

        $this->reservation->update([
            'payment_status' => 'completed',
        ]);
    }

    public function markAsFailed(): void
    {
        $this->status = 'failed';
        $this->save();

        $this->reservation->update([
            'payment_status' => 'failed',
        ]);
    }
}
