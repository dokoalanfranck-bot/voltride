<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        Promo::create([
            'code' => 'FIRST20',
            'description' => '20% off on first reservation',
            'discount_percent' => 20,
            'max_uses' => 100,
            'valid_from' => Carbon::now()->subDays(30),
            'valid_until' => Carbon::now()->addDays(30),
            'is_active' => true,
        ]);

        Promo::create([
            'code' => 'WEEKEND15',
            'description' => '15% off on weekend rentals',
            'discount_percent' => 15,
            'max_uses' => null,
            'valid_from' => Carbon::now()->subDays(7),
            'valid_until' => Carbon::now()->addDays(90),
            'is_active' => true,
        ]);

        Promo::create([
            'code' => 'SAVE10',
            'description' => '$10 off for all rentals',
            'discount_amount' => 10,
            'max_uses' => 200,
            'valid_from' => Carbon::now(),
            'valid_until' => Carbon::now()->addDays(60),
            'is_active' => true,
        ]);
    }
}
