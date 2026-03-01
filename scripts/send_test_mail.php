<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('Test depuis VoltRide', function ($m) {
        $m->to('dokoalanfranck@gmail.com')->subject('Test SMTP Gmail');
    });
    echo "Mail command executed\n";
} catch (\Exception $e) {
    echo "Mail send error: " . $e->getMessage() . "\n";
}
