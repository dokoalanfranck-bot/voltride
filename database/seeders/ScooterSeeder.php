<?php

namespace Database\Seeders;

use App\Models\Scooter;
use App\Models\ScooterImage;
use Illuminate\Database\Seeder;

class ScooterSeeder extends Seeder
{
    public function run(): void
    {
        $scooters = [
            [
                'name' => 'Turbo Max Pro',
                'description' => 'High-performance scooter with 40km/h top speed',
                'price_hour' => 15.99,
                'price_minute' => 0.35,
                'price_day' => 79.99,
                'battery_level' => 100,
                'status' => 'available',
                'max_speed' => 40,
                'qr_code' => 'SCOOTER001',
                'location' => 'Downtown Station',
                'images' => 3,
            ],
            [
                'name' => 'City Cruiser',
                'description' => 'Perfect for urban commuting',
                'price_hour' => 12.99,
                'price_minute' => 0.25,
                'price_day' => 59.99,
                'battery_level' => 85,
                'status' => 'available',
                'max_speed' => 35,
                'qr_code' => 'SCOOTER002',
                'location' => 'Central Park',
                'images' => 3,
            ],
            [
                'name' => 'Swift Eagle',
                'description' => 'Lightweight and easy to maneuver',
                'price_hour' => 10.99,
                'price_minute' => 0.20,
                'price_day' => 49.99,
                'battery_level' => 95,
                'status' => 'available',
                'max_speed' => 30,
                'qr_code' => 'SCOOTER003',
                'location' => 'Train Station',
                'images' => 3,
            ],
            [
                'name' => 'Power Rider',
                'description' => 'Professional scooter for long distances',
                'price_hour' => 18.99,
                'price_minute' => 0.40,
                'price_day' => 89.99,
                'battery_level' => 75,
                'status' => 'rented',
                'max_speed' => 45,
                'qr_code' => 'SCOOTER004',
                'location' => 'Tech Campus',
                'images' => 3,
            ],
            [
                'name' => 'Echo Pro',
                'description' => 'Silent and environmentally friendly',
                'price_hour' => 14.99,
                'price_minute' => 0.30,
                'price_day' => 69.99,
                'battery_level' => 100,
                'status' => 'available',
                'max_speed' => 38,
                'qr_code' => 'SCOOTER005',
                'location' => 'Shopping Mall',
                'images' => 3,
            ],
        ];

        foreach ($scooters as $data) {
            $imageCount = $data['images'] ?? 0;
            unset($data['images']);
            
            $scooter = Scooter::create($data);

            // Create sample images for each scooter
            for ($i = 1; $i <= $imageCount; $i++) {
                ScooterImage::create([
                    'scooter_id' => $scooter->id,
                    'image_path' => "placeholder/scooter-{$scooter->id}-{$i}.jpg",
                    'alt_text' => "{$scooter->name} - Image {$i}",
                    'order' => $i - 1,
                ]);
            }
        }
    }
}
