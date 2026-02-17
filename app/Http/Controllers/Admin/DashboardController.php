<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Scooter;
use App\Models\User;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(): View
    {
        $totalScooters = Scooter::count();
        $activeScooters = Scooter::where('status', 'available')->count();
        $totalReservations = Reservation::count();
        $completedReservations = Reservation::where('status', 'completed')->count();
        $totalUsers = User::where('role', 'client')->count();
        
        // Total revenue from completed reservations with completed payments
        $totalRevenue = Reservation::where('payment_status', 'completed')
            ->sum('total_price');

        // Monthly revenue from completed reservations with completed payments
        $monthlyRevenue = Reservation::where('payment_status', 'completed')
            ->whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->sum('total_price');

        // Last 30 days reservations
        $last30Days = Reservation::where('status', 'completed')
            ->whereBetween('created_at', [
                Carbon::now()->subDays(30),
                Carbon::now()
            ])
            ->count();

        // Top scooters
        $topScooters = Scooter::withCount('reservations')
            ->orderBy('reservations_count', 'desc')
            ->take(5)
            ->get();

        // Recent reservations
        $recentReservations = Reservation::latest()
            ->take(10)
            ->get();

        // Occupancy rate
        $occupancyRate = ($completedReservations / max($totalReservations, 1)) * 100;

        return view('admin.dashboard', [
            'totalScooters' => $totalScooters,
            'activeScooters' => $activeScooters,
            'totalReservations' => $totalReservations,
            'completedReservations' => $completedReservations,
            'totalUsers' => $totalUsers,
            'totalRevenue' => $totalRevenue,
            'monthlyRevenue' => $monthlyRevenue,
            'last30Days' => $last30Days,
            'topScooters' => $topScooters,
            'recentReservations' => $recentReservations,
            'occupancyRate' => round($occupancyRate, 2),
        ]);
    }
}
