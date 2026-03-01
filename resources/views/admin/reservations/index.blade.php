@extends('layouts.app')

@section('title', 'Gestion des Réservations - VoltRide Admin')

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 60px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; flex-wrap: wrap; gap: 16px;">
        <div>
            <a href="{{ route('admin.dashboard') }}" style="color: var(--gray); text-decoration: none; font-size: 0.9rem; display: inline-block; margin-bottom: 8px;">← Retour au dashboard</a>
            <h1 style="font-size: 2rem; font-weight: 800; letter-spacing: -1px;">
                📋 Gestion des <span style="color: var(--primary);">Réservations</span>
            </h1>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-body">
            <form action="{{ route('admin.reservations.index') }}" method="GET" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end;">
                <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-input">
                        <option value="">Tous</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>En cours</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminées</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulées</option>
                    </select>
                </div>
                <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                    <label class="form-label">Paiement</label>
                    <select name="payment_status" class="form-input">
                        <option value="">Tous</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Payé</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">🔍 Filtrer</button>
                <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">Réinitialiser</a>
            </form>
        </div>
    </div>

    <!-- Stats -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 32px;">
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <p style="color: var(--gray); font-size: 0.85rem; margin-bottom: 8px;">Total</p>
                <p style="font-size: 2rem; font-weight: 800; color: var(--primary);">{{ $reservations->total() }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <p style="color: var(--gray); font-size: 0.85rem; margin-bottom: 8px;">En attente</p>
                <p style="font-size: 2rem; font-weight: 800; color: #f59e0b;">{{ $reservations->where('status', 'pending')->count() }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <p style="color: var(--gray); font-size: 0.85rem; margin-bottom: 8px;">En cours</p>
                <p style="font-size: 2rem; font-weight: 800; color: #3b82f6;">{{ $reservations->where('status', 'active')->count() }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <p style="color: var(--gray); font-size: 0.85rem; margin-bottom: 8px;">Terminées</p>
                <p style="font-size: 2rem; font-weight: 800; color: #22c55e;">{{ $reservations->where('status', 'completed')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Reservations Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Trottinette</th>
                            <th>Période</th>
                            <th>Statut</th>
                            <th>Paiement</th>
                            <th>Prix</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $reservation)
                            <tr>
                                <td style="font-weight: 600; color: var(--primary);">#{{ $reservation->id }}</td>
                                <td>
                                    <p style="font-weight: 600;">{{ $reservation->guest_name ?? $reservation->user?->name ?? 'N/A' }}</p>
                                    <p style="color: var(--gray); font-size: 0.85rem;">{{ $reservation->guest_email ?? $reservation->user?->email ?? '' }}</p>
                                </td>
                                <td>{{ $reservation->scooter?->name ?? 'N/A' }}</td>
                                <td>
                                    <p style="font-size: 0.85rem;">{{ $reservation->start_time?->format('d/m/Y H:i') }}</p>
                                    <p style="color: var(--gray); font-size: 0.85rem;">{{ $reservation->end_time?->format('d/m/Y H:i') }}</p>
                                </td>
                                <td>
                                    @if($reservation->status === 'pending')
                                        <span class="badge badge-warning">⏳ En attente</span>
                                    @elseif($reservation->status === 'active')
                                        <span class="badge badge-info">✓ En cours</span>
                                    @elseif($reservation->status === 'completed')
                                        <span class="badge badge-success">✓ Terminée</span>
                                    @else
                                        <span class="badge badge-danger">✗ Annulée</span>
                                    @endif
                                </td>
                                <td>
                                    @if($reservation->payment_status === 'pending')
                                        <span class="badge badge-warning">💳 En attente</span>
                                    @elseif($reservation->payment_status === 'paid')
                                        <span class="badge badge-success">💳 Payé</span>
                                    @else
                                        <span class="badge badge-info">💳 Remboursé</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="price">{{ number_format($reservation->total_price, 2) }} $</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.reservations.show', $reservation) }}" class="btn btn-secondary" style="padding: 8px 12px; font-size: 0.85rem;">
                                        👁️ Voir
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px;">
                                    <div style="font-size: 3rem; margin-bottom: 16px; opacity: 0.3;">📋</div>
                                    <p style="color: var(--gray);">Aucune réservation trouvée</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($reservations->hasPages())
                <div style="margin-top: 24px; display: flex; justify-content: center;">
                    {{ $reservations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .container > div:nth-child(4) {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
    @media (max-width: 500px) {
        .container > div:nth-child(4) {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
