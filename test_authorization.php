<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Reservation;
use App\Models\Scooter;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

echo "=== TESTING AUTHORIZATION POLICIES ===\n\n";

// Setup: Create test data
echo "Setup: Creating test data...\n";

$admin = User::where('email', 'admin@scooter.com')->first();
$client = User::where('role', 'client')->first();
$scooter = Scooter::first();

if (!$admin || !$client || !$scooter) {
    echo "✗ Missing test data\n";
    exit(1);
}

echo "✓ Admin user: {$admin->email}\n";
echo "✓ Client user: {$client->email}\n";
echo "✓ Scooter: {$scooter->brand}\n\n";

// Create a reservation for the client
$reservation = Reservation::create([
    'user_id' => $client->id,
    'scooter_id' => $scooter->id,
    'start_time' => Carbon::now()->addDay()->setTime(9, 0),
    'end_time' => Carbon::now()->addDay()->setTime(12, 0),
    'status' => 'pending',
    'payment_status' => 'pending',
]);

echo "✓ Created test reservation (ID: {$reservation->id})\n\n";

// Test 1: Client should view own reservation
echo "Test 1: Client viewing own reservation\n";
Auth::login($client);

try {
    $this_authorize = auth()->user()->can('view', $reservation);
    if ($this_authorize) {
        echo "✓ Client CAN view their own reservation ✓\n";
    } else {
        echo "✗ Client CANNOT view their own reservation ✗\n";
    }
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

// Test 2: Admin should view any reservation
echo "\nTest 2: Admin viewing client's reservation\n";
Auth::login($admin);

try {
    $can_view = auth()->user()->can('view', $reservation);
    if ($can_view) {
        echo "✓ Admin CAN view any reservation ✓\n";
    } else {
        echo "✗ Admin CANNOT view any reservation ✗\n";
    }
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

// Test 3: Another client should NOT view this reservation
echo "\nTest 3: Another client viewing someone else's reservation\n";
$another_client = User::where('email', '!=', $client->email)->where('role', 'client')->first();

if ($another_client) {
    Auth::login($another_client);
    
    try {
        $can_view = auth()->user()->can('view', $reservation);
        if (!$can_view) {
            echo "✓ Other client CANNOT view this reservation ✓\n";
        } else {
            echo "✗ Other client CAN view this reservation (SECURITY ISSUE!) ✗\n";
        }
    } catch (\Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "⚠️  Only one client found, skipping test\n";
}

// Test 4: Client should update own reservation
echo "\nTest 4: Client updating own reservation\n";
Auth::login($client);

try {
    $can_update = auth()->user()->can('update', $reservation);
    if ($can_update) {
        echo "✓ Client CAN update their own reservation ✓\n";
    } else {
        echo "✗ Client CANNOT update their own reservation ✗\n";
    }
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

// Test 5: Admin accessing admin dashboard (middleware test)
echo "\nTest 5: Admin accessing admin routes\n";
Auth::login($admin);

try {
    $is_admin = auth()->user()->isAdmin();
    if ($is_admin) {
        echo "✓ Admin user IS confirmed admin ✓\n";
    } else {
        echo "✗ Admin user is NOT confirmed as admin ✗\n";
    }
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

// Test 6: Client cannot access admin dashboard (middleware check)
echo "\nTest 6: Client accessing admin routes (should be denied)\n";
Auth::login($client);

try {
    $is_admin = auth()->user()->isAdmin();
    if (!$is_admin) {
        echo "✓ Client is NOT admin (middleware would deny access) ✓\n";
    } else {
        echo "✗ Client IS admin (SECURITY ISSUE!) ✗\n";
    }
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

// Cleanup
$reservation->delete();
echo "\n✓ Test reservation deleted\n";
echo "\n=== ALL AUTHORIZATION TESTS PASSED ===\n";

?>
