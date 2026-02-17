<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'admin@scooter.com')->first();
if ($user) {
    echo "✓ Admin user found\n";
    echo "Email: " . $user->email . "\n";
    echo "Role: " . $user->role . "\n";
    echo "Password hash exists: " . (!empty($user->password) ? "Yes" : "No") . "\n";
    
    if (\Illuminate\Support\Facades\Hash::check('password123', $user->password)) {
        echo "✓ Password 'password123' is CORRECT\n";
    } else {
        echo "✗ Password 'password123' is INCORRECT\n";
    }
} else {
    echo "✗ Admin user not found. Users in database:\n";
    \App\Models\User::all()->each(function($u) {
        echo "  - " . $u->email . " (" . $u->role . ")\n";
    });
}
