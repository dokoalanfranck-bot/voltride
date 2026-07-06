<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    // Stripe removed — payments handled cash-on-site.
    // Admin validates payments manually via AdminReservationController::validatePayment().
}
