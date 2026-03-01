<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Scooter;
use App\Models\User;
use App\Mail\ReservationConfirmationClient;
use App\Mail\ReservationNotificationAdmin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ReservationController extends Controller
{
    // No middleware in constructor - controlled via routes


    public function index(): View
    {
        $reservations = Reservation::query()
            ->with('scooter')
            ->latest()
            ->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    public function create(Scooter $scooter): View
    {
        if (!$scooter->isAvailable()) {
            return redirect()->route('scooters.show', $scooter)
                ->with('error', 'Cette trottinette n\'est pas disponible actuellement');
        }

        return view('reservations.create', compact('scooter'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Validate input
        $validated = $request->validate([
            'scooter_id' => 'required|exists:scooters,id',
            'start_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'duration_minutes' => 'required|integer|min:5|max:120',
            'guest_name' => 'required_without:user_id|string|max:255',
            'guest_phone' => 'required_without:user_id|string|max:20',
            'guest_email' => 'required_without:user_id|email|max:255',
            'is_tourist' => 'boolean',
            'accept_terms' => 'required|accepted',
        ]);

        // Combine date and time, calculate end time
        $startDateTime = $validated['start_date'] . ' ' . $validated['start_time'];
        $startTime = Carbon::createFromFormat('Y-m-d H:i', $startDateTime);
        $endTime = $startTime->copy()->addMinutes($validated['duration_minutes']);

        // Check if duration exceeds 2 hours for non-tourists
        if (!($validated['is_tourist'] ?? false) && $endTime->diffInMinutes($startTime) > 120) {
            return redirect()->back()
                ->withErrors(['duration_minutes' => 'Seuls les touristes peuvent réserver pour plus de 2 heures'])
                ->withInput();
        }

        // Check scooter availability
        $scooter = Scooter::find($validated['scooter_id']);
        
        if (!$scooter->isAvailable()) {
            return redirect()->back()->with('error', 'Cette trottinette n\'est pas disponible');
        }

        // Check for conflicting reservations
        $conflictingReservation = Reservation::where('scooter_id', $scooter->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime])
                      ->orWhere(function ($q) use ($startTime, $endTime) {
                          $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                      });
            })
            ->exists();

        if ($conflictingReservation) {
            return redirect()->back()
                ->with('error', 'Cette trottinette n\'est pas disponible pour la période sélectionnée')
                ->withInput();
        }

        // Create reservation
        $reservation = Reservation::create([
            'user_id' => auth()?->id(),
            'scooter_id' => $scooter->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'guest_name' => $validated['guest_name'] ?? null,
            'guest_phone' => $validated['guest_phone'] ?? null,
            'guest_email' => $validated['guest_email'] ?? null,
            'payment_method' => 'cash',
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // Calculate total price
        $reservation->total_price = $reservation->calculatePrice();
        $reservation->save();

        // Send confirmation emails
        try {
            // Send email to client
            $clientEmail = $validated['guest_email'] ?? auth()?->user()?->email;
            if ($clientEmail) {
                Mail::to($clientEmail)->queue(new ReservationConfirmationClient($reservation));
            }

            // Send notification email to admin(s).
            // Prefer admin users from DB, but fall back to ADMIN_EMAILS env var or MAIL_FROM_ADDRESS.
            $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
            $envAdminList = env('ADMIN_EMAILS', env('MAIL_FROM_ADDRESS')) ?: '';
            $envAdmins = array_filter(array_map('trim', explode(',', $envAdminList)));
            $allAdminEmails = array_values(array_unique(array_merge($adminEmails, $envAdmins)));

            if (!empty($allAdminEmails)) {
                foreach ($allAdminEmails as $adminEmail) {
                    try {
                        Mail::to($adminEmail)->queue(new ReservationNotificationAdmin($reservation));
                    } catch (\Exception $e) {
                        \Log::error("Erreur en envoyant la notification admin à {$adminEmail}: " . $e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            // Log the error but don't fail the reservation creation
            \Log::error('Erreur lors de l\'envoi des emails de réservation: ' . $e->getMessage());
        }

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Réservation créée avec succès. Veuillez payer sur place avant de démarrer votre location.');
    }

    public function show(Reservation $reservation): View
    {
        // Allow guests to view their reservation or authenticated users their own
        if (!auth()->check() || (auth()->id() !== $reservation->user_id && $reservation->user_id !== null)) {
            if ($reservation->user_id !== null) {
                $this->authorize('view', $reservation);
            }
        }

        return view('reservations.show', compact('reservation'));
    }

    public function cancel(Reservation $reservation): RedirectResponse
    {
        // Only authenticated users can cancel their own reservations
        if ($reservation->user_id) {
            $this->authorize('update', $reservation);
        }

        if ($reservation->status === 'completed') {
            return redirect()->back()->with('error', 'Impossible d\'annuler une réservation terminée');
        }

        $reservation->update(['status' => 'cancelled']);

        return redirect()->route('reservations.index')
            ->with('success', 'Réservation annulée');
    }

    public function apiCheckAvailability(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'scooter_id' => 'required|exists:scooters,id',
            'start_time' => 'required|date_format:Y-m-d H:i',
            'duration_minutes' => 'required|integer|min:5|max:120',
        ]);

        $scooter = Scooter::find($validated['scooter_id']);
        $startTime = Carbon::createFromFormat('Y-m-d H:i', $validated['start_time']);
        $endTime = $startTime->copy()->addMinutes($validated['duration_minutes']);

        $conflictingReservations = Reservation::where('scooter_id', $scooter->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime])
                      ->orWhere(function ($q) use ($startTime, $endTime) {
                          $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                      });
            })
            ->exists();

        // Calculate price based on minutes
        $minutes = $validated['duration_minutes'];
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;
        $price = ($hours * $scooter->price_hour) + ($remainingMinutes * $scooter->price_minute);

        return response()->json([
            'available' => !$conflictingReservations,
            'price' => round($price, 2),
            'formatted_price' => number_format($price, 2) . '€',
        ]);
    }
}
