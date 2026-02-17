<?php
require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

echo "=== CHECKING ROUTE REGISTRATION ===\n\n";

// Get all routes
$routes = Route::getRoutes();

echo "Total routes: " . count($routes) . "\n\n";

// Check for login-related routes
$auth_routes = [];
foreach ($routes as $route) {
    if (strpos($route->uri, 'login') !== false || strpos($route->uri, 'register') !== false || strpos($route->uri, 'logout') !== false) {
        $auth_routes[] = $route;
    }
}

echo "=== AUTHENTICATION ROUTES ===\n";
if (empty($auth_routes)) {
    echo "⚠️  NO AUTH ROUTES FOUND! This is the problem!\n";
} else {
    foreach ($auth_routes as $route) {
        echo sprintf(
            "✓ %s %-6s -> %s (name: %s, middleware: %s)\n",
            str_pad($route->uri, 20),
            implode(',', $route->methods),
            $route->action['controller'] ?? 'Closure',
            $route->getName() ?? 'NONE',
            implode(',', $route->middleware)
        );
    }
}

// Check if POST /login has the correct middleware
echo "\n=== CHECKING POST /LOGIN REQUIREMENTS ===\n";
$login_post = null;
foreach ($routes as $route) {
    if ($route->uri === 'login' && in_array('POST', $route->methods)) {
        $login_post = $route;
        break;
    }
}

if ($login_post) {
    echo "✓ POST /login route found\n";
    echo "  Controller: " . ($login_post->action['uses'] ?? $login_post->action['controller'] ?? 'Unknown') . "\n";
    echo "  Middleware: " . implode(', ', $login_post->middleware) . "\n";
    echo "  Requires 'guest' middleware: " . (in_array('guest', $login_post->middleware) ? "YES" : "NO") . "\n";
} else {
    echo "✗ POST /login route NOT FOUND\n";
}

// Check current auth state
echo "\n=== CURRENT AUTH STATE ===\n";
echo "User authenticated: " . (Auth::check() ? "YES" : "NO") . "\n";
echo "Is guest: " . (Auth::guest() ? "YES" : "NO") . "\n";
echo "Current user: " . (Auth::user() ? Auth::user()->email : "NONE") . "\n";

// List all users for testing
echo "\n=== DATABASE USERS ===\n";
$users = \App\Models\User::select('email', 'role')->get();
foreach ($users as $user) {
    echo sprintf("  %s (%s)\n", $user->email, $user->role);
}

?>
