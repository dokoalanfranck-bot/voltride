@extends('layouts.app')

@section('title', 'Mes réservations - voltride')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <!-- En-tête -->
    <div style="margin-bottom: 40px;">
        <h1 style="color: #1f7550; font-size: 2.5rem; font-weight: 700; margin-bottom: 8px;">
            📅 Mes réservations
        </h1>
        <p style="color: #4a5568; font-size: 1.1rem;">
            Suivez et gérez toutes vos réservations de trottinettes électriques
        </p>
    </div>

    @if($reservations->count() > 0)
        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4" style="margin-bottom: 40px;">
            <div class="card" style="padding: 20px; text-align: center; border-top: 3px solid #1f7550;">
                <p style="color: #999; font-size: 0.9rem; margin-bottom: 8px;">TOTAL RÉSERVATIONS</p>
                <p style="color: #1f7550; font-size: 2rem; font-weight: 700;">{{ $reservations->count() }}</p>
            </div>
            <div class="card" style="padding: 20px; text-align: center; border-top: 3px solid #2d9b6f;">
                <p style="color: #999; font-size: 0.9rem; margin-bottom: 8px;">EN COURS</p>
                <p style="color: #2d9b6f; font-size: 2rem; font-weight: 700;">{{ $reservations->where('status', 'active')->count() }}</p>
            </div>
            <div class="card" style="padding: 20px; text-align: center; border-top: 3px solid #4ade80;">
                <p style="color: #999; font-size: 0.9rem; margin-bottom: 8px;">COMPLÉTÉES</p>
                <p style="color: #4ade80; font-size: 2rem; font-weight: 700;">{{ $reservations->where('status', 'completed')->count() }}</p>
            </div>
            <div class="card" style="padding: 20px; text-align: center; border-top: 3px solid #fbbf24;">
                <p style="color: #999; font-size: 0.9rem; margin-bottom: 8px;">DÉPENSE TOTALE</p>
                <p style="color: #1f7550; font-size: 2rem; font-weight: 700;">{{ number_format($reservations->sum('total_price'), 2) }}€</p>
            </div>
        </div>

        <!-- Liste des réservations -->
        <div style="display: grid; gap: 16px;">
            @foreach($reservations as $reservation)
                <div class="card" style="overflow: hidden;">
                    <div style="display: grid; grid-template-columns: auto 1fr auto; gap: 20px; padding: 20px; align-items: center;">
                        <!-- Image de la trottinette -->
                        <img src="{{ $reservation->scooter?->images->first()?->getUrl() ?? 'https://via.placeholder.com/120x120?text=No+Image' }}" alt="{{ $reservation->scooter?->name ?? 'Trottinette' }}" style="width: 120px; height: 120px; border-radius: 8px; object-fit: contain; background: #f9f9f9; padding: 8px;">

                        <!-- Détails de la réservation -->
                        <div>
                            <h3 style="color: #1f7550; font-size: 1.25rem; font-weight: 700; margin-bottom: 8px;">
                                {{ $reservation->scooter?->name ?? 'Trottinette supprimée' }}
                            </h3>
                            <p style="color: #4a5568; margin-bottom: 12px;">
                                📍 {{ $reservation->scooter?->location ?? 'Localisation non disponible' }}
                            </p>
                            
                            <div style="display: grid; grid-template-columns: auto auto; gap: 20px; margin-bottom: 12px;">
                                <div>
                                    <p style="color: #999; font-size: 0.9rem;">Du</p>
                                    <p style="color: #1f7550; font-weight: 700;">
                                       {{ optional($reservation->start_date)->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div>
                                    <p style="color: #999; font-size: 0.9rem;">Au</p>
                                    <p style="color: #1f7550; font-weight: 700;">
                                        {{ optional($reservation->start_date)->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Statut -->
                            @if($reservation->status === 'active')
                                <span style="display: inline-block; background: #dcfce7; color: #166534; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                    ✓ En cours
                                </span>
                            @elseif($reservation->status === 'completed')
                                <span style="display: inline-block; background: #d1fae5; color: #065f46; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                    ✓ Complétée
                                </span>
                            @elseif($reservation->status === 'cancelled')
                                <span style="display: inline-block; background: #fed7aa; color: #92400e; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                    ✗ Annulée
                                </span>
                            @else
                                <span style="display: inline-block; background: #f3f4f6; color: #6b7280; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                    ⏳ En attente
                                </span>
                            @endif
                        </div>

                        <!-- Prix et actions -->
                        <div style="text-align: right;">
                            <p style="color: #999; font-size: 0.9rem; margin-bottom: 8px;">PRIX TOTAL</p>
                            <p style="color: #1f7550; font-size: 1.75rem; font-weight: 700; margin-bottom: 16px;">
                                {{ number_format($reservation->total_price, 2) }}€
                            </p>
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <a href="{{ route('reservations.show', $reservation->id) }}" class="btn-secondary" style="text-align: center; padding: 10px; font-size: 0.95rem;">
                                    Détails →
                                </a>
                                @if($reservation->status === 'pending' || $reservation->status === 'active')
                                    <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-secondary" style="width: 100%; padding: 10px; font-size: 0.95rem; background: #fee2e2; color: #dc2626; border-color: #dc2626;" onclick="return confirm('Êtes-vous sûr ?')">
                                            Annuler
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Aucune réservation -->
        <div class="card" style="padding: 60px 40px; text-align: center;">
            <div style="font-size: 4rem; margin-bottom: 16px;">🛴</div>
            <h2 style="color: #1f7550; font-size: 1.5rem; font-weight: 700; margin-bottom: 12px;">
                Aucune réservation pour le moment
            </h2>
            <p style="color: #4a5568; line-height: 1.8; margin-bottom: 24px; max-width: 500px; margin-left: auto; margin-right: auto;">
                Vous n'avez pas encore réservé de trottinette. Parcourez notre flotte et trouvez la trottinette parfaite pour votre prochain trajet !
            </p>
            <a href="{{ route('scooters.index') }}" class="btn-primary" style="padding: 12px 32px; display: inline-block;">
                💨 Parcourir les trottinettes
            </a>
        </div>
    @endif
</div>
@endsection
