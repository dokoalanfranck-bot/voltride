@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>

    <!-- Key Metrics -->
    <div class="grid md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-semibold">Total Scooters</h3>
            <p class="text-3xl font-bold">{{ $totalScooters }}</p>
            <p class="text-green-600 text-sm mt-2">{{ $activeScooters }} available</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-semibold">Total Reservations</h3>
            <p class="text-3xl font-bold">{{ $totalReservations }}</p>
            <p class="text-green-600 text-sm mt-2">{{ $completedReservations }} completed</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-semibold">Total Revenue</h3>
            <p class="text-3xl font-bold">${{ number_format($totalRevenue, 2) }}</p>
            <p class="text-blue-600 text-sm mt-2">${{ number_format($monthlyRevenue, 2) }} this month</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-semibold">Active Users</h3>
            <p class="text-3xl font-bold">{{ $totalUsers }}</p>
            <p class="text-purple-600 text-sm mt-2">{{ $occupancyRate }}% occupancy</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid md:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('admin.scooters.create') }}" class="bg-blue-600 text-white p-4 rounded-lg hover:bg-blue-700">
            <h4 class="font-bold">Add New Scooter</h4>
            <p class="text-sm">Create and configure a new scooter</p>
        </a>

        <a href="{{ route('admin.scooters.index') }}" class="bg-green-600 text-white p-4 rounded-lg hover:bg-green-700">
            <h4 class="font-bold">Manage Scooters</h4>
            <p class="text-sm">Edit, update or delete scooters</p>
        </a>

        <a href="{{ route('admin.reservations.index') }}" class="bg-purple-600 text-white p-4 rounded-lg hover:bg-purple-700">
            <h4 class="font-bold">View Reservations</h4>
            <p class="text-sm">Manage all user reservations</p>
        </a>
    </div>

    <!-- Top Scooters -->
    <div class="grid md:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Top Scooters</h3>
            <div class="space-y-3">
                @foreach($topScooters as $scooter)
                <div class="flex justify-between items-center pb-3 border-b">
                    <div>
                        <p class="font-semibold">{{ $scooter->name }}</p>
                        <p class="text-sm text-gray-600">{{ $scooter->reservations_count }} reservations</p>
                    </div>
                    <p class="font-bold">${{ number_format($scooter->price_day * $scooter->reservations_count / 4, 2) }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Last 30 Days -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Last 30 Days</h3>
            <div class="space-y-2">
                <p><strong>Completed Reservations:</strong> {{ $last30Days }}</p>
                <p><strong>Revenue This Month:</strong> ${{ number_format($monthlyRevenue, 2) }}</p>
                <p><strong>Average per Reservation:</strong> ${{ number_format($monthlyRevenue / max($last30Days, 1), 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Recent Reservations -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold mb-4">Recent Reservations</h3>
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">User</th>
                    <th class="px-4 py-2 text-left">Scooter</th>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Amount</th>
                    <th class="px-4 py-2 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentReservations as $reservation)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $reservation->user?->name ?? 'Utilisateur supprimé' }}</td>
                    <td class="px-4 py-2">{{ $reservation->scooter?->name ?? 'Trottinette supprimée' }}</td>
                    <td class="px-4 py-2">{{ $reservation->created_at->format('M d') }}</td>
                    <td class="px-4 py-2">${{ number_format($reservation->total_price, 2) }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($reservation->status === 'completed') bg-green-100 text-green-800
                            @else bg-yellow-100 text-yellow-800
                            @endif
                        ">
                            {{ ucfirst($reservation->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
