<?php
require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Route;

echo "=== CHECKING IF LOGIN ROUTE EXISTS ===\n\n";

try {
    $login_get = Route::getRoutes()->getByName('login');
    if ($login_get) {
        echo "✓ Named route 'login' FOUND\n";
        echo "  URI: " . $login_get->uri . "\n";
        echo "  Methods: " . implode(', ', $login_get->methods) . "\n";
    } else {
        echo "✗ Named route 'login' NOT FOUND\n";
    }
} catch (\Exception $e) {
    echo "✗ Error checking login route: " . $e->getMessage() . "\n";
}

echo "\n=== CHECKING ROUTE WEB ALIASES ===\n";

$route_collection = Route::getRoutes();
foreach ($route_collection as $route) {
    if ($route->named) {
        $name = $route->getName();
        if (strpos($name, 'login') !== false || strpos($name, 'register') !== false) {
            echo "✓ Found route: $name\n";
        }
    }
}

?>
