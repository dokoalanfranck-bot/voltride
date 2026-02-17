<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Test Auth::attempt
echo "Testing Auth::attempt...\n";
$attempt = \Illuminate\Support\Facades\Auth::attempt(['email' => 'admin@scooter.com', 'password' => 'password123']);
if ($attempt) {
    echo "✓ Auth::attempt SUCCESS\n";
    echo "Authenticated user: " . \Illuminate\Support\Facades\Auth::user()->email . "\n";
} else {
    echo "✗ Auth::attempt FAILED\n";
}

// Test with wrong password
echo "\nTesting with wrong password...\n";
$wrongPass = \Illuminate\Support\Facades\Auth::attempt(['email' => 'admin@scooter.com', 'password' => 'wrongpassword']);
if ($wrongPass) {
    echo "✓ Wrong password authenticated (unexpected!)\n";
} else {
    echo "✓ Wrong password correctly rejected\n";
}
