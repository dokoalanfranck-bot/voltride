<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Scooter;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

echo "=== TESTING RESERVATION CREATION ===\n\n";

// Authenticate as admin
$user = User::where('email', 'admin@scooter.com')->first();
if (!$user) {
    echo "✗ Admin user not found\n";
    exit(1);
}

Auth::login($user);
echo "✓ Authenticated as: " . Auth::user()->email . "\n\n";

// Get first available scooter
$scooter = Scooter::first();
if (!$scooter) {
    echo "✗ No scooters found\n";
    exit(1);
}

echo "✓ Using scooter: {$scooter->brand} {$scooter->model}\n\n";

// Try to create a reservation
echo "Attempting to create reservation...\n";

try {
    $startTime = Carbon::now()->addDay()->setTime(9, 0);
    $endTime = Carbon::now()->addDay()->setTime(12, 0);
    
    echo "  Start: " . $startTime->format('Y-m-d H:i') . "\n";
    echo "  End: " . $endTime->format('Y-m-d H:i') . "\n\n";
    
    $reservation = Reservation::create([
        'user_id' => Auth::id(),
        'scooter_id' => $scooter->id,
        'start_time' => $startTime,  // Changed from start_date
        'end_time' => $endTime,       // Changed from end_date
        'status' => 'pending',
        'payment_status' => 'pending',
    ]);
    
    echo "✓ Reservation created successfully!\n";
    echo "  ID: " . $reservation->id . "\n";
    echo "  Start: " . $reservation->start_time->format('Y-m-d H:i') . "\n";
    echo "  End: " . $reservation->end_time->format('Y-m-d H:i') . "\n";
    
    // Clean up
    $reservation->delete();
    echo "\n✓ Test reservation deleted (cleanup)\n";
    
} catch (\Exception $e) {
    echo "✗ Error creating reservation:\n";
    echo "  " . $e->getMessage() . "\n";
    
    if ($e instanceof \Illuminate\Database\QueryException) {
        echo "  SQL Error: " . $e->getSql() . "\n";
    }
}

?>
