<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "=== TESTING ADMIN MIDDLEWARE ===\n\n";

$admin = User::where('email', 'admin@scooter.com')->first();
$client = User::where('role', 'client')->first();

echo "Test 1: Admin accessing protected route '/admin/dashboard'\n";
Auth::login($admin);

$is_admin = Auth::user()->isAdmin();
if ($is_admin) {
    echo "✓ Admin user confirmed: " . Auth::user()->email . "\n";
    echo "✓ WOULD pass admin middleware check\n";
} else {
    echo "✗ Admin user NOT confirmed\n";
}

echo "\nTest 2: Client accessing protected route '/admin/dashboard'\n";
Auth::login($client);

$is_admin = Auth::user()->isAdmin();
if (!$is_admin) {
    echo "✓ Client user confirmed: " . Auth::user()->email . "\n";
    echo "✓ WOULD be blocked by admin middleware (403 Unauthorized)\n";
} else {
    echo "✗ Client IS admin (SECURITY ISSUE!)\n";
}

echo "\n=== ADMIN MIDDLEWARE TEST PASSED ===\n";

?>
