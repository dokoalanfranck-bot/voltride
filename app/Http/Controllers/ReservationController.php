<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Scooter;
use App\Models\User;
use App\Mail\ReservationConfirmationClient;
use App\Mail\ReservationNotificationAdmin;
use App\Rules\NotDisposableEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index(): View
    {
        $reservations = Reservation::query()
            ->where('user_id', auth()->id())
            ->with('scooter')
            ->latest()
            ->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    public function create(Scooter $scooter): View
    {
        if (!$scooter->isAvailable()) {
            return redirect()->route('scooters.show', $scooter)
                ->with('error', 'Cette trottinette n\'est pas disponible actuellement.');
        }

        return view('reservations.create', compact('scooter'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'scooter_id'       => 'required|exists:scooters,id',
            'start_date'       => 'required|date_format:Y-m-d|after_or_equal:today',
            'start_time'       => 'required|date_format:H:i',
            'duration_minutes' => 'required|integer|min:5|max:480',
            'guest_name'       => 'required_if:user_id,null|nullable|string|max:255',
            'guest_phone'      => 'required_if:user_id,null|nullable|string|max:20|regex:/^[+\d\s\-\(\)]{6,20}$/',
            'guest_email'      => ['required_if:user_id,null', 'nullable', 'email:rfc,dns', 'max:255', new NotDisposableEmail()],
            'is_tourist'       => 'nullable|boolean',
            'accept_terms'     => 'required|accepted',
        ]);

        $startTime = Carbon::createFromFormat('Y-m-d H:i', $validated['start_date'] . ' ' . $validated['start_time']);
        $endTime   = $startTime->copy()->addMinutes((int) $validated['duration_minutes']);

        $scooter = Scooter::findOrFail($validated['scooter_id']);

        if (!$scooter->isAvailable()) {
            return redirect()->back()->with('error', 'Cette trottinette n\'est plus disponible.')->withInput();
        }

        $conflict = Reservation::where('scooter_id', $scooter->id)
            ->whereNotIn('status', ['cancelled'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                  ->orWhereBetween('end_time', [$startTime, $endTime])
                  ->orWhere(fn ($q2) => $q2->where('start_time', '<=', $startTime)->where('end_time', '>=', $endTime));
            })->exists();

        if ($conflict) {
            return redirect()->back()
                ->with('error', 'Cette trottinette n\'est pas disponible pour la période sélectionnée.')
                ->withInput();
        }

        // Guests aren't authenticated, so cap how many unpaid reservations a single
        // email/phone can hold at once — stops one person from mass-booking scooters
        // under fake identities to block them from real customers.
        if (!auth()->check()) {
            $activeGuestReservations = Reservation::whereNull('user_id')
                ->whereNotIn('status', ['cancelled', 'completed'])
                ->where(function ($q) use ($validated) {
                    $q->where('guest_email', $validated['guest_email'] ?? null)
                      ->orWhere('guest_phone', $validated['guest_phone'] ?? null);
                })
                ->count();

            if ($activeGuestReservations >= 2) {
                return redirect()->back()
                    ->with('error', 'Vous avez déjà des réservations en attente. Merci de les régler ou de les annuler avant d\'en créer une nouvelle.')
                    ->withInput();
            }
        }

        $reservation = DB::transaction(function () use ($validated, $scooter, $startTime, $endTime) {
            $reservation = Reservation::create([
                'user_id'        => auth()?->id(),
                'scooter_id'     => $scooter->id,
                'start_time'     => $startTime,
                'end_time'       => $endTime,
                'guest_name'     => $validated['guest_name'] ?? null,
                'guest_phone'    => $validated['guest_phone'] ?? null,
                'guest_email'    => $validated['guest_email'] ?? null,
                'payment_method' => 'cash',
                'status'         => 'pending',
                'payment_status' => 'pending',
            ]);

            $reservation->total_price = $reservation->calculatePrice();
            $reservation->save();

            return $reservation;
        });

        try {
            $clientEmail = $validated['guest_email'] ?? auth()?->user()?->email;
            if ($clientEmail) {
                Mail::to($clientEmail)->queue(new ReservationConfirmationClient($reservation));
            }

            $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
            $envAdmins   = array_filter(array_map('trim', explode(',', env('ADMIN_EMAILS', env('MAIL_FROM_ADDRESS', '')))));
            $allAdmins   = array_values(array_unique(array_merge($adminEmails, $envAdmins)));

            foreach ($allAdmins as $adminEmail) {
                try {
                    Mail::to($adminEmail)->queue(new ReservationNotificationAdmin($reservation));
                } catch (\Exception $e) {
                    \Log::error("Erreur email admin ({$adminEmail}): " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            \Log::error('Erreur envoi emails réservation: ' . $e->getMessage());
        }

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Réservation créée. Réglez le montant sur place avant de démarrer.');
    }

    public function show(Reservation $reservation): View
    {
        if (auth()->check()) {
            if ($reservation->user_id && auth()->id() !== $reservation->user_id && !auth()->user()->isAdmin()) {
                abort(403);
            }
        } elseif ($reservation->user_id !== null) {
            abort(403);
        }

        return view('reservations.show', compact('reservation'));
    }

    public function cancel(Reservation $reservation): RedirectResponse
    {
        if ($reservation->user_id && auth()->id() !== $reservation->user_id) {
            abort(403);
        }

        if (in_array($reservation->status, ['completed', 'cancelled'])) {
            return redirect()->back()->with('error', 'Cette réservation ne peut pas être annulée.');
        }

        $reservation->update(['status' => 'cancelled']);

        return redirect()->route('reservations.index')
            ->with('success', 'Réservation annulée avec succès.');
    }

    public function apiCheckAvailability(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'scooter_id'       => 'required|exists:scooters,id',
            'start_time'       => 'required|date_format:Y-m-d H:i',
            'duration_minutes' => 'required|integer|min:5|max:480',
        ]);

        $scooter   = Scooter::findOrFail($validated['scooter_id']);
        $startTime = Carbon::createFromFormat('Y-m-d H:i', $validated['start_time']);
        $endTime   = $startTime->copy()->addMinutes((int) $validated['duration_minutes']);

        $conflict = Reservation::where('scooter_id', $scooter->id)
            ->whereNotIn('status', ['cancelled'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                  ->orWhereBetween('end_time', [$startTime, $endTime])
                  ->orWhere(fn ($q2) => $q2->where('start_time', '<=', $startTime)->where('end_time', '>=', $endTime));
            })->exists();

        $minutes  = (int) $validated['duration_minutes'];
        $hours    = intdiv($minutes, 60);
        $rem      = $minutes % 60;
        $price    = ($hours * $scooter->price_hour) + ($rem * $scooter->price_minute);

        return response()->json([
            'available'       => !$conflict,
            'price'           => round($price, 2),
            'formatted_price' => number_format($price, 2) . ' €',
        ]);
    }
}
