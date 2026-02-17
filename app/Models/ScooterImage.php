<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScooterImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'scooter_id',
        'image_path',
        'alt_text',
        'order',
    ];

    public function scooter(): BelongsTo
    {
        return $this->belongsTo(Scooter::class);
    }

    public function getUrl(): string
    {
        // Try to use the stored image path first
        $path = storage_path('app/public/' . $this->image_path);
        
        // If file exists, return asset URL
        if (file_exists($path)) {
            return asset('storage/' . $this->image_path);
        }
        
        // Otherwise, return a dynamic placeholder
        return 'https://via.placeholder.com/400x250?text=' . urlencode($this->alt_text ?? 'Scooter Image');
    }
}
