<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@scooter.com',
            'password' => Hash::make('password123'),
            'phone' => '+1234567890',
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Client users
        User::factory()->count(10)->create([
            'role' => 'client',
            'is_active' => true,
        ]);
    }
}
