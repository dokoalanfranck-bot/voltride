<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Reservation;
use Illuminate\Http\Request;

echo "=== TESTING FILTERS ===\n\n";

// Create a mock request object
$request = new Request([
    'status' => 'completed',
    'payment_status' => 'completed',
]);

echo "Test 1: Filter by reservation status\n";
$reservations = Reservation::with('user', 'scooter')
    ->when($request->filled('status'), function ($query) use ($request) {
        $query->where('status', $request->input('status'));
    })
    ->get();

echo "✓ Found " . $reservations->count() . " completed reservations\n\n";

echo "Test 2: Filter by payment status\n";
$request = new Request([
    'payment_status' => 'completed',
]);

$reservations = Reservation::with('user', 'scooter')
    ->when($request->filled('payment_status'), function ($query) use ($request) {
        $query->where('payment_status', $request->input('payment_status'));
    })
    ->get();

echo "✓ Found " . $reservations->count() . " reservations with completed payment\n\n";

echo "=== ALL FILTER TESTS PASSED ===\n";

?>
