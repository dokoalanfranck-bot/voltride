<?php
require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\ParameterBag;

echo "=== TESTING LOGIN REQUEST PROCESSING ===\n\n";

// Create a fake HTTP request that mimics the form submission
$request = new Request();
$request->setMethod('POST');
$request->request->add([
    'email' => 'admin@scooter.com',
    'password' => 'password123'
]);
$request->headers->add(['X-XSRF-TOKEN' => 'fake-token']);

// Create fake session and cookies
$session = new \Illuminate\Session\Store('test', new \Illuminate\Session\Middleware\EncryptCookies());
$request->setSession($session);

echo "Request Data:\n";
echo "  Email: " . $request->input('email') . "\n";
echo "  Password: " . ($request->input('password') ? "***" : "MISSING") . "\n";
echo "  Session: " . get_class($request->getSession()) . "\n";

// Now try to create a LoginRequest from our fake request to see if validation works
echo "\n=== VALIDATING REQUEST ===\n";

try {
    // The LoginRequest will validate the incoming request
    $login_request = LoginRequest::createFrom($request);
    echo "✓ LoginRequest created successfully\n";
    echo "  Validated Email: " . $login_request->input('email') . "\n";
    echo "  Validated Password: (" . strlen($login_request->input('password')) . " chars)\n";
    
    // Check if Laravel can find the route
    echo "\n=== CHECKING LOGIN ROUTE ===\n";
    $login_route = \Illuminate\Support\Facades\Route::getRoutes()->getByName('login');
    if ($login_route) {
        echo "✓ Login route found\n";
        echo "  Methods: " . implode(', ', $login_route->methods) . "\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error creating LoginRequest: " . $e->getMessage() . "\n";
}

// Check guest middleware
echo "\n=== CHECKING GUEST MIDDLEWARE ===\n";
echo "Current user: " . (Auth::user() ? Auth::user()->email : "NONE") . "\n";
echo "Is guest: " . (Auth::guest() ? "YES" : "NO") . "\n";

?>
