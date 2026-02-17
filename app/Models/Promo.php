<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_percent',
        'discount_amount',
        'max_uses',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function isValid(): bool
    {
        return $this->is_active 
            && now()->between($this->valid_from, $this->valid_until)
            && ($this->max_uses === null || $this->used_count < $this->max_uses);
    }

    public function canBeUsed(): bool
    {
        return $this->isValid();
    }

    public function incrementUseCount(): void
    {
        $this->increment('used_count');
    }
}
