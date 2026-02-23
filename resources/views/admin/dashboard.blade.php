@extends('layouts.app')

@section('content')
<style>
    .stat-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 1.5rem;
        transition: all 0.3s ease;
         border-left: 4px solid transparent;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .stat-card.primary { border-left-color: #07d65d; }
    .stat-card.success { border-left-color: #10b981; }
    .stat-card.warning { border-left-color: #f59e0b; }
    .stat-card.info { border-left-color: #3b82f6; }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #47F55B 0%, #07d65d 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0.5rem 0;
    }

    .quick-action {
        background: linear-gradient(135deg, #47F55B 0%, #07d65d 100%);
        color: #0f172a;
        padding: 1.25rem;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s;
        display: block;
        box-shadow: 0 2px 8px rgba(71, 245, 91, 0.2);
    }

    .quick-action:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(71, 245, 91, 0.3);
    }

    .section-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 1.5rem;
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        width: 100%;
        font-size: 0.9rem;
        border-collapse: collapse;
    }

    thead {
        background: linear-gradient(135deg, rgba(71, 245, 91, 0.1) 0%, rgba(7, 214, 93, 0.1) 100%);
    }

    th {
        padding: 12px 16px;
        text-align: left;
        font-weight: 700;
        color: #0a9b3a;
    }

    tbody tr {
        border-bottom: 1px solid #e2e8f0;
        transition: background 0.2s;
    }

    tbody tr:hover {
        background: #f9fafb;
    }

    td {
        padding: 12px 16px;
    }

    .badge {
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-success {
        background: #d1fae5;
        color: #0a9b3a;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    @media (max-width: 768px) {
        .stat-number {
            font-size: 2rem;
        }
    }
</style>

<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 style="font-size: clamp(1.75rem, 5vw, 2.25rem); font-weight: 800; margin-bottom: 2rem; background: linear-gradient(135deg, #47F55B 0%, #07d65d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
        📊 Tableau de bord Admin
    </h1>

    <!-- Key Metrics -->
    <div class="grid md:grid-cols-4 gap-4 mb-8">
        <div class="stat-card primary">
            <h3 style="color: #4a5568; font-size: 0.875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">🛴 Total Trottinettes</h3>
            <p class="stat-number">{{ $totalScooters }}</p>
            <p style="color: #0a9b3a; font-size: 0.875rem; font-weight: 600;">✓ {{ $activeScooters }} disponibles</p>
        </div>

        <div class="stat-card success">
            <h3 style="color: #4a5568; font-size: 0.875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">📋 Total Réservations</h3>
            <p class="stat-number">{{ $totalReservations }}</p>
            <p style="color: #10b981; font-size: 0.875rem; font-weight: 600;">✓ {{ $completedReservations }} complétées</p>
        </div>

        <div class="stat-card warning">
            <h3 style="color: #4a5568; font-size: 0.875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">💰 Revenu Total</h3>
            <p class="stat-number">{{ number_format($totalRevenue, 0) }}€</p>
            <p style="color: #f59e0b; font-size: 0.875rem; font-weight: 600;">📈 {{ number_format($monthlyRevenue, 0) }}€ ce mois</p>
        </div>

        <div class="stat-card info">
            <h3 style="color: #4a5568; font-size: 0.875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">👥 Utilisateurs Actifs</h3>
            <p class="stat-number">{{ $totalUsers }}</p>
            <p style="color: #3b82f6; font-size: 0.875rem; font-weight: 600;">📊 {{ $occupancyRate }}% d'occupation</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid md:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('admin.scooters.create') }}" class="quick-action">
            <h4 style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">➕ Ajouter une Trottinette</h4>
            <p style="font-size: 0.875rem; opacity: 0.9;">Créer et configurer une nouvelle trottinette</p>
        </a>

        <a href="{{ route('admin.scooters.index') }}" class="quick-action">
            <h4 style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">⚙️ Gérer les Trottinettes</h4>
            <p style="font-size: 0.875rem; opacity: 0.9;">Éditer, mettre à jour ou supprimer des trottinettes</p>
        </a>

        <a href="{{ route('admin.reservations.index') }}" class="quick-action">
            <h4 style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">📝 Voir les Réservations</h4>
            <p style="font-size: 0.875rem; opacity: 0.9;">Gérer toutes les réservations utilisateur</p>
        </a>
    </div>

    <!-- Top Scooters & Stats -->
    <div class="grid md:grid-cols-2 gap-8 mb-8">
        <div class="section-card">
            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; color: #0a9b3a;">🏆 Top Trottinettes</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($topScooters as $index => $scooter)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: linear-gradient(135deg, rgba(71, 245, 91, 0.05) 0%, rgba(7, 214, 93, 0.05) 100%); border-radius: 8px; border-left: 4px solid #07d65d;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <span style="width: 32px; height: 32px; background: linear-gradient(135deg, #47F55B 0%, #07d65d 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #0f172a;">{{ $index + 1 }}</span>
                        <div>
                            <p style="font-weight: 600; margin: 0;">{{ $scooter->name }}</p>
                            <p style="font-size: 0.875rem; color: #4a5568; margin: 0;">📊 {{ $scooter->reservations_count }} réservations</p>
                        </div>
                    </div>
                    <p style="font-weight: 700; font-size: 1.1rem; color: #0a9b3a; margin: 0;">{{ number_format($scooter->price_day * $scooter->reservations_count / 4, 0) }}€</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="section-card">
            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; color: #0a9b3a;">📈 30 Derniers Jours</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="padding: 1rem; background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%); border-radius: 8px; border-left: 4px solid #10b981;">
                    <p style="color: #4a5568; font-size: 0.875rem; margin: 0;">Réservations Complétées</p>
                    <p style="font-size: 2rem; font-weight: 700; color: #10b981; margin: 0.5rem 0 0 0;">{{ $last30Days }}</p>
                </div>
                <div style="padding: 1rem; background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(245, 158, 11, 0.05) 100%); border-radius: 8px; border-left: 4px solid #f59e0b;">
                    <p style="color: #4a5568; font-size: 0.875rem; margin: 0;">Revenu Ce Mois</p>
                    <p style="font-size: 2rem; font-weight: 700; color: #f59e0b; margin: 0.5rem 0 0 0;">{{ number_format($monthlyRevenue, 0) }}€</p>
                </div>
                <div style="padding: 1rem; background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(59, 130, 246, 0.05) 100%); border-radius: 8px; border-left: 4px solid #3b82f6;">
                    <p style="color: #4a5568; font-size: 0.875rem; margin: 0;">Moyenne par Réservation</p>
                    <p style="font-size: 2rem; font-weight: 700; color: #3b82f6; margin: 0.5rem 0 0 0;">{{ number_format($monthlyRevenue / max($last30Days, 1), 0) }}€</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reservations -->
    <div class="section-card">
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; color: #0a9b3a;">🕐 Réservations Récentes</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>👤 Utilisateur</th>
                        <th>🛴 Trottinette</th>
                        <th>📅 Date</th>
                        <th>💰 Montant</th>
                        <th>📊 Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentReservations as $reservation)
                    <tr>
                        <td><strong>{{ $reservation->user?->name ?? 'Utilisateur supprimé' }}</strong></td>
                        <td>{{ $reservation->scooter?->name ?? 'Trottinette supprimée' }}</td>
                        <td>{{ $reservation->created_at->format('d/m/Y') }}</td>
                        <td><strong>{{ number_format($reservation->total_price, 2) }}€</strong></td>
                        <td>
                            <span class="badge {{ $reservation->status === 'completed' ? 'badge-success' : 'badge-warning' }}">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
