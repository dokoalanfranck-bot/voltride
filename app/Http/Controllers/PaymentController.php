<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
        // Initialize Stripe with API key
        if (config('stripe.secret')) {
            Stripe::setApiKey(config('stripe.secret'));
        }
    }

    public function show(Reservation $reservation): View
    {
        $this->authorize('view', $reservation);

        $payment = $reservation->payment ?? new Payment();

        return view('payments.show', [
            'reservation' => $reservation,
            'payment' => $payment,
            'stripeKey' => config('stripe.public'),
        ]);
    }

    public function store(Request $request, Reservation $reservation): RedirectResponse
    {
        $this->authorize('view', $reservation);

        $validated = $request->validate([
            'stripeToken' => 'required|string',
        ]);

        try {
            $charge = Charge::create([
                'amount' => (int)($reservation->total_price * 100),
                'currency' => 'usd',
                'source' => $validated['stripeToken'],
                'description' => "Scooter Reservation #{$reservation->id}",
            ]);

            $payment = Payment::create([
                'reservation_id' => $reservation->id,
                'amount' => $reservation->total_price,
                'stripe_payment_id' => $charge->id,
                'status' => 'completed',
                'stripe_response' => json_encode($charge),
            ]);

            $reservation->update([
                'payment_status' => 'completed',
                'status' => 'active',
            ]);

            return redirect()->route('reservations.show', $reservation)
                ->with('success', 'Payment successful!');
        } catch (\Exception $e) {
            $payment = Payment::create([
                'reservation_id' => $reservation->id,
                'amount' => $reservation->total_price,
                'status' => 'failed',
                'stripe_response' => json_encode(['error' => $e->getMessage()]),
            ]);

            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    public function refund(Payment $payment): RedirectResponse
    {
        if (auth()->user()->isAdmin()) {
            try {
                \Stripe\Refund::create([
                    'charge' => $payment->stripe_payment_id,
                ]);

                $payment->update(['status' => 'refunded']);
                $payment->reservation->update(['payment_status' => 'refunded']);

                return redirect()->back()->with('success', 'Refund processed successfully');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Refund failed: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Unauthorized');
    }
}
