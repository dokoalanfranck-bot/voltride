<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminReservationController extends Controller
{
    /**
     * Valide le paiement d'une réservation (admin)
     */
    public function validatePayment(Reservation $reservation): RedirectResponse
    {
        // Si Payment existe, on le met à jour, sinon on met juste Reservation
        $payment = $reservation->payment;
        if ($payment) {
            $payment->update(['status' => 'completed']);
        }
        $reservation->update(['payment_status' => 'completed']);
        return redirect()->back()->with('success', 'Paiement validé avec succès.');
    }
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request): View
    {
        $query = Reservation::with('user', 'scooter');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('start_time', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('end_time', '<=', $request->input('date_to'));
        }

        // Search by user email or name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $reservations = $query->latest()->paginate(15);

        return view('admin.reservations.index', compact('reservations'));
    }

    public function show(Reservation $reservation): View
    {
        $payment = $reservation->payment;

        return view('admin.reservations.show', compact('reservation', 'payment'));
    }

    /**
     * Update reservation status (admin)
     */
    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'required|in:pending,active,completed,cancelled',
        ]);

        $status = $data['status'];

        if ($status === 'completed') {
            $reservation->markAsCompleted();
        } else {
            $reservation->update(['status' => $status]);
        }

        return redirect()->back()->with('success', 'Statut de la réservation mis à jour.');
    }

    public function markCompleted(Reservation $reservation): RedirectResponse
    {
        $reservation->markAsCompleted();

        return redirect()->back()->with('success', 'Reservation marked as completed');
    }

    public function refund(Payment $payment): RedirectResponse
    {
        try {
            $payment->update(['status' => 'refunded']);
            $payment->reservation->update(['payment_status' => 'refunded']);

            return redirect()->back()->with('success', 'Refund processed');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Refund failed: ' . $e->getMessage());
        }
    }
}
