@extends('layouts.app')

@section('title', 'Admin Dashboard - VoltRide')

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 60px;">
    <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 32px; letter-spacing: -1px;">
        📊 Tableau de bord <span style="color: var(--primary);">Admin</span>
    </h1>

    <!-- Key Metrics -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px;">
        <div class="card" style="border-left: 4px solid var(--primary);">
            <div class="card-body">
                <h3 style="color: var(--gray); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">Total Trottinettes</h3>
                <p style="font-size: 2.5rem; font-weight: 800; color: var(--primary); margin-bottom: 8px;">{{ $totalScooters }}</p>
                <p style="color: var(--primary); font-size: 0.9rem; font-weight: 600;">{{ $activeScooters }} disponibles</p>
            </div>
        </div>

        <div class="card" style="border-left: 4px solid #22c55e;">
            <div class="card-body">
                <h3 style="color: var(--gray); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">Total Réservations</h3>
                <p style="font-size: 2.5rem; font-weight: 800; color: #22c55e; margin-bottom: 8px;">{{ $totalReservations }}</p>
                <p style="color: #22c55e; font-size: 0.9rem; font-weight: 600;">{{ $completedReservations }} complétées</p>
            </div>
        </div>

        <div class="card" style="border-left: 4px solid #f59e0b;">
            <div class="card-body">
                <h3 style="color: var(--gray); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">Revenu Total</h3>
                <p style="font-size: 2.5rem; font-weight: 800; color: #f59e0b; margin-bottom: 8px;">{{ number_format($totalRevenue, 0) }} $</p>
                <p style="color: #f59e0b; font-size: 0.9rem; font-weight: 600;">📈 {{ number_format($monthlyRevenue, 0) }} $ ce mois</p>
            </div>
        </div>

        <div class="card" style="border-left: 4px solid #3b82f6;">
            <div class="card-body">
                <h3 style="color: var(--gray); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">👥 Utilisateurs</h3>
                <p style="font-size: 2.5rem; font-weight: 800; color: #3b82f6; margin-bottom: 8px;">{{ $totalUsers }}</p>
                <p style="color: #3b82f6; font-size: 0.9rem; font-weight: 600;">📊 {{ $occupancyRate }}% d'occupation</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px;">
        <a href="{{ route('admin.scooters.create') }}" class="btn btn-primary btn-lg" style="justify-content: center; padding: 24px;">
            <span style="font-size: 1.5rem; margin-right: 12px;"><i class="fa-solid fa-plus-circle"></i></span>
            <div style="text-align: left;">
                <div style="font-weight: 700;">Nouvelle Trottinette</div>
                <div style="font-size: 0.85rem; opacity: 0.8;">Ajouter au parc</div>
            </div>
        </a>
        <a href="{{ route('admin.scooters.index') }}" class="btn btn-secondary btn-lg" style="justify-content: center; padding: 24px;">
            <span style="font-size: 1.5rem; margin-right: 12px;"><i class="fa-solid fa-motorcycle"></i></span>
            <div style="text-align: left;">
                <div style="font-weight: 700;">Gérer les Trottinettes</div>
                <div style="font-size: 0.85rem; opacity: 0.8;">{{ $totalScooters }} au total</div>
            </div>
        </a>
        <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary btn-lg" style="justify-content: center; padding: 24px;">
            <span style="font-size: 1.5rem; margin-right: 12px;"><i class="fa-solid fa-calendar-check"></i></span>
            <div style="text-align: left;">
                <div style="font-weight: 700;">Gérer les Réservations</div>
                <div style="font-size: 0.85rem; opacity: 0.8;">{{ $pendingReservations ?? 0 }} en attente</div>
            </div>
        </a>
    </div>

    <!-- Recent Reservations & Scooters -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        <!-- Recent Reservations -->
        <div class="card">
            <div class="card-body">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="font-size: 1.1rem; font-weight: 700;">Réservations récentes</h3>
                    <a href="{{ route('admin.reservations.index') }}" style="color: var(--primary); text-decoration: none; font-size: 0.9rem;">Voir tout →</a>
                </div>
                
                @if(isset($recentReservations) && $recentReservations->count() > 0)
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Statut</th>
                                    <th>Prix</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentReservations->take(5) as $reservation)
                                    <tr>
                                        <td>#{{ $reservation->id }}</td>
                                        <td>{{ $reservation->guest_name ?? $reservation->user?->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($reservation->status === 'pending')
                                                <span class="badge badge-warning">En attente</span>
                                            @elseif($reservation->status === 'active')
                                                <span class="badge badge-info">En cours</span>
                                            @elseif($reservation->status === 'completed')
                                                <span class="badge badge-success">Terminée</span>
                                            @else
                                                <span class="badge badge-danger">Annulée</span>
                                            @endif
                                        </td>
                                        <td class="price">{{ number_format($reservation->total_price, 2) }} $</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p style="color: var(--gray); text-align: center; padding: 20px;">Aucune réservation récente</p>
                @endif
            </div>
        </div>

        <!-- Recent Scooters -->
        <div class="card">
            <div class="card-body">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="font-size: 1.1rem; font-weight: 700;">Trottinettes</h3>
                    <a href="{{ route('admin.scooters.index') }}" style="color: var(--primary); text-decoration: none; font-size: 0.9rem;">Voir tout →</a>
                </div>
                
                @if(isset($recentScooters) && $recentScooters->count() > 0)
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Statut</th>
                                    <th>Prix/h</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentScooters->take(5) as $scooter)
                                    <tr>
                                        <td style="font-weight: 600;">{{ $scooter->name }}</td>
                                        <td>
                                            @if($scooter->status === 'available')
                                                <span class="badge badge-success">Disponible</span>
                                            @else
                                                <span class="badge badge-warning">En location</span>
                                            @endif
                                        </td>
                                        <td class="price">{{ number_format($scooter->price_hour, 2) }} $</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p style="color: var(--gray); text-align: center; padding: 20px;">Aucune trottinette</p>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 1024px) {
        .container > div:nth-child(2) {
            grid-template-columns: repeat(2, 1fr) !important;
        }
        .container > div:nth-child(3) {
            grid-template-columns: 1fr !important;
        }
        .container > div:last-child {
            grid-template-columns: 1fr !important;
        }
    }
    @media (max-width: 600px) {
        .container > div:nth-child(2) {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
