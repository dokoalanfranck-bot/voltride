<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "All users in database:\n";
\App\Models\User::all()->each(function($u) {
    echo "- " . $u->email . " (role: " . $u->role . ")\n";
});

echo "\n\nTesting each known user with various passwords:\n";
$users = \App\Models\User::all();
foreach ($users as $user) {
    echo "\n" . $user->email . ":\n";
    
    // Test password123
    if (\Illuminate\Support\Facades\Hash::check('password123', $user->password)) {
        echo "  ✓ password 'password123' works\n";
    }
    
    // Test the email as password
    if (\Illuminate\Support\Facades\Hash::check($user->email, $user->password)) {
        echo "  ✓ password '" . $user->email . "' works\n";
    }
}
