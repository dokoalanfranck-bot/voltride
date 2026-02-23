@extends('layouts.app')

@section('content')
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    tbody tr {
        animation: fadeIn 0.5s ease-out;
    }
    tbody tr:hover {
        background-color: #f0fdf4;
    }
    input:focus, select:focus {
        border-color: #07d65d !important;
        box-shadow: 0 0 0 3px rgba(7, 214, 93, 0.2);
        outline: none;
    }
</style>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <h1 style="font-size: 2rem; font-weight: 800; color: #1f7550;">Gestion des Réservations</h1>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 24px;">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: #1f7550; margin-bottom: 16px;">Filtres</h3>
        
        <form method="GET" action="{{ route('admin.reservations.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <!-- Search -->
            <div>
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px;">Recherche (email/nom)</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Chercher..." style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem;">
            </div>

            <!-- Status Filter -->
            <div>
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px;">Statut</label>
                <select name="status" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem;">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>En cours</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Complètée</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>

            <!-- Payment Status Filter -->
            <div>
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px;">Statut Paiement</label>
                <select name="payment_status" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem;">
                    <option value="">Tous les paiements</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="completed" {{ request('payment_status') === 'completed' ? 'selected' : '' }}>Payé</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Échoué</option>
                    <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Remboursé</option>
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px;">Du</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem;">
            </div>

            <!-- Date To -->
            <div>
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px;">Au</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem;">
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 8px; align-items: flex-end;">
                <button type="submit" style="background: #1f7550; color: white; padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; flex: 1;">
                    🔍 Filtrer
                </button>
                <a href="{{ route('admin.reservations.index') }}" style="background: #6c757d; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 600; text-align: center;">
                    ↺ Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Reservations Table -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #1f7550 0%, #2d9b6f 100%); color: white;">
                        <th style="padding: 16px; text-align: left; font-weight: 600;">ID</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600;">Client</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600;">Trottinette</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600;">Début</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600;">Fin</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600;">Statut</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600;">Paiement</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600;">Prix</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservations as $reservation)
                        <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.2s;">
                            <td style="padding: 16px; font-weight: 600; color: #1f7550;">{{ $reservation->id }}</td>
                            <td style="padding: 16px;">{{ $reservation->guest_name ?? $reservation->user?->name ?? 'N/A' }}<br><span style="color: #666; font-size: 0.85rem;">{{ $reservation->user?->email ?? '' }}</span></td>
                            <td style="padding: 16px;">{{ $reservation->scooter?->brand ?? 'N/A' }} {{ $reservation->scooter?->model ?? '' }}</td>
                            <td style="padding: 16px;">{{ $reservation->start_time->format('d/m/Y H:i') }}</td>
                            <td style="padding: 16px;">{{ $reservation->end_time->format('d/m/Y H:i') }}</td>
                            <td style="padding: 16px;">
                                <span style="
                                    padding: 6px 12px;
                                    border-radius: 20px;
                                    font-size: 0.85rem;
                                    font-weight: 600;
                                    @if ($reservation->status === 'pending')
                                        background: #fff3cd;
                                        color: #856404;
                                    @elseif ($reservation->status === 'active')
                                        background: #d1ecf1;
                                        color: #0c5460;
                                    @elseif ($reservation->status === 'completed')
                                        background: #d4edda;
                                        color: #155724;
                                    @elseif ($reservation->status === 'cancelled')
                                        background: #f8d7da;
                                        color: #721c24;
                                    @endif
                                ">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </td>
                            <td style="padding: 16px;">
                                <span style="
                                    padding: 6px 12px;
                                    border-radius: 20px;
                                    font-size: 0.85rem;
                                    font-weight: 600;
                                    @if ($reservation->payment_status === 'pending')
                                        background: #fff3cd;
                                        color: #856404;
                                    @elseif ($reservation->payment_status === 'completed')
                                        background: #d4edda;
                                        color: #155724;
                                    @elseif ($reservation->payment_status === 'failed')
                                        background: #f8d7da;
                                        color: #721c24;
                                    @elseif ($reservation->payment_status === 'refunded')
                                        background: #e7e7ff;
                                        color: #3f3fcc;
                                    @endif
                                ">
                                    {{ ucfirst($reservation->payment_status) }}
                                </span>
                            </td>
                            <td style="padding: 16px; font-weight: 600; color: #1f7550;">{{ $reservation->total_price ? number_format($reservation->total_price, 2) . ' €' : 'N/A' }}</td>
                                <td style="padding: 16px; font-weight: 600; color: #1f7550;">{{ $reservation->total_price ? number_format($reservation->total_price, 2) . ' $' : 'N/A' }}</td>
                            <td style="padding: 16px;">
                                <a href="{{ route('admin.reservations.show', $reservation) }}" style="
                                    display: inline-block;
                                    background: #1f7550;
                                    color: white;
                                    padding: 8px 16px;
                                    border-radius: 6px;
                                    text-decoration: none;
                                    font-weight: 600;
                                    font-size: 0.85rem;
                                    transition: background 0.3s;
                                ">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding: 32px; text-align: center; color: #999;">
                                Aucune réservation trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if ($reservations->hasPages())
        <div style="margin-top: 32px;">
            {{ $reservations->links() }}
        </div>
    @endif
</div>
@endsection
