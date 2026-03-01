<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationNotificationAdmin;

$reservation = Reservation::first();
if (!$reservation) {
    echo "No reservations found. Create one first.\n";
    exit(1);
}

$adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
$envAdminList = env('ADMIN_EMAILS', env('MAIL_FROM_ADDRESS')) ?: '';
$envAdmins = array_filter(array_map('trim', explode(',', $envAdminList)));
$allAdminEmails = array_values(array_unique(array_merge($adminEmails, $envAdmins)));

if (empty($allAdminEmails)) {
    echo "No admin emails configured.\n";
    exit(1);
}

foreach ($allAdminEmails as $email) {
    try {
        Mail::to($email)->send(new ReservationNotificationAdmin($reservation));
        echo "Sent admin notification to: {$email}\n";
    } catch (\Exception $e) {
        echo "Error sending to {$email}: " . $e->getMessage() . "\n";
    }
}
