<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Scooter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $reservations = auth()->user()->reservations()
            ->with('scooter')
            ->latest()
            ->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    public function create(Scooter $scooter): View
    {
        if (!$scooter->isAvailable()) {
            return redirect()->route('scooters.show', $scooter)
                ->with('error', 'This scooter is not available');
        }

        return view('reservations.create', compact('scooter'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'scooter_id' => 'required|exists:scooters,id',
            'start_date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'required|date_format:Y-m-d',
            'end_time' => 'required|date_format:H:i',
            'promo_code' => 'nullable|string',
        ]);

        // Combine date and time fields
        $startDateTime = $validated['start_date'] . ' ' . $validated['start_time'];
        $endDateTime = $validated['end_date'] . ' ' . $validated['end_time'];

        $startTime = Carbon::createFromFormat('Y-m-d H:i', $startDateTime);
        $endTime = Carbon::createFromFormat('Y-m-d H:i', $endDateTime);

        // Validate that end time is after start time
        if ($endTime->lessThanOrEqualTo($startTime)) {
            return redirect()->back()
                ->withErrors(['end_time' => 'End time must be after start time'])
                ->withInput();
        }

        $scooter = Scooter::find($validated['scooter_id']);
        
        if (!$scooter->isAvailable()) {
            return redirect()->back()->with('error', 'Scooter not available');
        }

        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'scooter_id' => $scooter->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        $reservation->total_price = $reservation->calculatePrice();
        $reservation->save();

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Reservation created successfully');
    }

    public function show(Reservation $reservation): View
    {
        $this->authorize('view', $reservation);

        return view('reservations.show', compact('reservation'));
    }

    public function cancel(Reservation $reservation): RedirectResponse
    {
        $this->authorize('update', $reservation);

        if ($reservation->status === 'completed') {
            return redirect()->back()->with('error', 'Cannot cancel completed reservation');
        }

        $reservation->update(['status' => 'cancelled']);

        return redirect()->route('reservations.index')
            ->with('success', 'Reservation cancelled');
    }

    public function apiCheckAvailability(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'scooter_id' => 'required|exists:scooters,id',
            'start_time' => 'required|date_format:Y-m-d H:i',
            'end_time' => 'required|date_format:Y-m-d H:i|after:start_time',
        ]);

        $scooter = Scooter::find($validated['scooter_id']);
        $startTime = Carbon::createFromFormat('Y-m-d H:i', $validated['start_time']);
        $endTime = Carbon::createFromFormat('Y-m-d H:i', $validated['end_time']);

        $conflictingReservations = Reservation::where('scooter_id', $scooter->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime]);
            })
            ->exists();

        $hours = $startTime->diffInHours($endTime);
        $days = intdiv($hours, 24);
        $remainingHours = $hours % 24;

        $price = ($days * $scooter->price_day) + ($remainingHours * $scooter->price_hour);

        return response()->json([
            'available' => !$conflictingReservations,
            'price' => $price,
        ]);
    }
}
