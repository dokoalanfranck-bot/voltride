<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Scooter;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Seed the database with reservations.
     */
    public function run(): void
    {
        $users = User::where('role', 'client')->get();
        $scooters = Scooter::all();

        if ($users->isEmpty() || $scooters->isEmpty()) {
            return;
        }

        // Create 15 reservations with completed status and completed payment_status
        for ($i = 0; $i < 15; $i++) {
            $user = $users->random();
            $scooter = $scooters->random();

            // Mix of past and recent reservations (including this month)
            $daysAgo = rand(0, 60);
            $startTime = now()->subDays($daysAgo)->setHour(rand(8, 18))->setMinute(0);
            $endTime = $startTime->copy()->addHours(rand(2, 8));

            $reservation = Reservation::create([
                'user_id' => $user->id,
                'scooter_id' => $scooter->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'completed',
                'payment_status' => 'completed',
                'delay_minutes' => rand(0, 120),
                'delay_fee' => rand(0, 1) ? rand(5, 20) : 0,
            ]);

            // Calculate and set the total_price
            $hours = $startTime->diffInHours($endTime);
            $days = intdiv($hours, 24);
            $remainingHours = $hours % 24;

            $price = ($days * $scooter->price_day) + ($remainingHours * $scooter->price_hour);
            
            if ($reservation->delay_minutes > 0) {
                $delayHours = ceil($reservation->delay_minutes / 60);
                $price += ($delayHours * $scooter->price_hour);
            }

            $reservation->update(['total_price' => $price]);
        }

        // Create some pending reservations (not completed)
        for ($i = 0; $i < 5; $i++) {
            $user = $users->random();
            $scooter = $scooters->random();

            $startTime = now()->addDays(rand(1, 7))->setHour(rand(8, 18))->setMinute(0);
            $endTime = $startTime->copy()->addHours(rand(2, 8));

            Reservation::create([
                'user_id' => $user->id,
                'scooter_id' => $scooter->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'pending',
                'payment_status' => 'pending',
                'delay_minutes' => 0,
                'delay_fee' => 0,
                'total_price' => 0,
            ]);
        }
    }
}
