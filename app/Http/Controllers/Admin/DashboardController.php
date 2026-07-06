<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Scooter;
use App\Models\User;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(): View
    {
        $now = Carbon::now();

        $totalScooters      = Scooter::count();
        $activeScooters     = Scooter::where('status', 'available')->where('is_active', true)->count();
        $totalReservations  = Reservation::count();
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $activeReservations = Reservation::where('status', 'active')->count();
        $totalUsers         = User::where('role', 'client')->count();

        $totalRevenue   = Reservation::where('payment_status', 'completed')->sum('total_price');
        $monthlyRevenue = Reservation::where('payment_status', 'completed')
            ->whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
            ->sum('total_price');

        // Revenue last 6 months for chart
        $revenueByMonth = [];
        $monthLabels    = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $monthLabels[] = $month->translatedFormat('M Y');
            $revenueByMonth[] = (float) Reservation::where('payment_status', 'completed')
                ->whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
                ->sum('total_price');
        }

        // Reservations by status for pie chart
        $statusCounts = Reservation::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $topScooters = Scooter::withCount(['reservations' => fn ($q) => $q->where('status', '!=', 'cancelled')])
            ->orderByDesc('reservations_count')
            ->take(5)
            ->get();

        $recentReservations = Reservation::with(['user', 'scooter'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalScooters', 'activeScooters',
            'totalReservations', 'pendingReservations', 'activeReservations',
            'totalUsers',
            'totalRevenue', 'monthlyRevenue',
            'revenueByMonth', 'monthLabels',
            'statusCounts',
            'topScooters',
            'recentReservations'
        ));
    }
}
