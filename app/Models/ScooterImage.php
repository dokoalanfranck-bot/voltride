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
        // Use route-based image serving (works in production without symlink)
        return route('storage.image', ['path' => $this->image_path]);
    }
}
