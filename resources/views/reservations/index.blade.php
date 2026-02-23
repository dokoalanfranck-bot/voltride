@extends('layouts.app')

@section('title', 'Mes réservations - VoltRide')

@section('content')
@include('components.responsive-styles')

<style>
    @media (max-width: 768px) {
        .reservation-card {
            grid-template-columns: 1fr !important;
        }
    }
    
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.12);
    }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .stat-card {
        animation: fadeInUp 0.6s ease-out;
    }
    
    .stat-card:nth-child(1) { animation-delay: 0.1s; opacity: 0; animation-fill-mode: forwards; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; opacity: 0; animation-fill-mode: forwards; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; opacity: 0; animation-fill-mode: forwards; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; opacity: 0; animation-fill-mode: forwards; }
</style>

<div style="max-width: 1200px; margin: 0 auto; padding: 1rem 1.5rem; overflow-x: hidden;">
    <!-- Header -->
    <div style="margin-bottom: 40px;">
        <h1 style="color: #0a9b3a; font-size: clamp(1.8rem, 6vw, 2.5rem); font-weight: 700; margin-bottom: 8px;">
            📅 Mes réservations
        </h1>
        <p style="color: #4a5568; font-size: clamp(1rem, 2vw, 1.1rem);">
            Suivez et gérez toutes vos réservations de trottinettes
        </p>
    </div>

    @if($reservations->count() > 0)
        <!-- Stats -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px; margin-bottom: 40px;">
            <div class="card stat-card" style="padding: 20px; text-align: center; border-top: 3px solid #07d65d;">
                <p style="color: #999; font-size: clamp(0.8rem, 2vw, 0.9rem); margin-bottom: 8px; text-transform: uppercase;">Total</p>
                <p style="color: #0a9b3a; font-size: clamp(1.5rem, 4vw, 2rem); font-weight: 700; margin: 0;">{{ $reservations->count() }}</p>
            </div>
            <div class="card stat-card" style="padding: 20px; text-align: center; border-top: 3px solid #2d9b6f;">
                <p style="color: #999; font-size: clamp(0.8rem, 2vw, 0.9rem); margin-bottom: 8px; text-transform: uppercase;">En cours</p>
                <p style="color: #2d9b6f; font-size: clamp(1.5rem, 4vw, 2rem); font-weight: 700; margin: 0;">{{ $reservations->where('status', 'active')->count() }}</p>
            </div>
            <div class="card stat-card" style="padding: 20px; text-align: center; border-top: 3px solid #4ade80;">
                <p style="color: #999; font-size: clamp(0.8rem, 2vw, 0.9rem); margin-bottom: 8px; text-transform: uppercase;">Complétées</p>
                <p style="color: #4ade80; font-size: clamp(1.5rem, 4vw, 2rem); font-weight: 700; margin: 0;">{{ $reservations->where('status', 'completed')->count() }}</p>
            </div>
            <div class="card stat-card" style="padding: 20px; text-align: center; border-top: 3px solid #07d65d;">
                <p style="color: #999; font-size: clamp(0.8rem, 2vw, 0.9rem); margin-bottom: 8px; text-transform: uppercase;">Dépensé</p>
                <p style="color: #0a9b3a; font-size: clamp(1.5rem, 4vw, 2rem); font-weight: 700; margin: 0;">{{ number_format($reservations->sum('total_price'), 2) }}€</p>
            </div>
        </div>

        <!-- Reservations List -->
        <div style="display: flex; flex-direction: column; gap: 16px;">
            @foreach($reservations as $reservation)
                <div class="card" style="overflow: hidden;">
                    <div class="reservation-card" style="display: grid; grid-template-columns: auto 1fr auto; gap: clamp(16px, 4vw, 24px); padding: clamp(16px, 4vw, 20px); align-items: center;">
                        <!-- Scooter Image -->
                        <img src="{{ $reservation->scooter?->images->first()?->getUrl() ?? 'https://via.placeholder.com/120x120?text=No+Image' }}" alt="{{ $reservation->scooter?->name ?? 'Trottinette' }}" style="width: clamp(80px, 20vw, 120px); height: clamp(80px, 20vw, 120px); border-radius: 8px; object-fit: contain; background: #f9f9f9; padding: 8px; flex-shrink: 0;">

                        <!-- Reservation Details -->
                        <div>
                            <h3 style="color: #1f7550; font-size: clamp(1.1rem, 3vw, 1.25rem); font-weight: 700; margin-bottom: 8px;">
                                {{ $reservation->scooter?->name ?? 'Trottinette supprimée' }}
                            </h3>
                            <p style="color: #4a5568; margin-bottom: 12px; font-size: clamp(0.9rem, 2vw, 1rem);">
                                📍 {{ $reservation->scooter?->location ?? 'Localisation non disponible' }}
                            </p>
                            
                            <div style="display: flex; gap: 24px; margin-bottom: 12px; flex-wrap: wrap;">
                                <div>
                                    <p style="color: #999; font-size: 0.85rem; margin-bottom: 2px;">Du</p>
                                    <p style="color: #1f7550; font-weight: 700; font-size: clamp(0.9rem, 2vw, 1rem);">
                                       {{ $reservation->start_time?->format('d/m/Y H:i') ?? 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <p style="color: #999; font-size: 0.85rem; margin-bottom: 2px;">Au</p>
                                    <p style="color: #1f7550; font-weight: 700; font-size: clamp(0.9rem, 2vw, 1rem);">
                                        {{ $reservation->end_time?->format('d/m/Y H:i') ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Status -->
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
                                <span style="display: inline-block; background: #fff3cd; color: #856404; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                    ⏳ En attente
                                </span>
                            @endif
                        </div>

                        <!-- Price & Action -->
                        <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 12px;">
                            <div>
                                <p style="color: #999; font-size: 0.85rem; margin-bottom: 4px;">Total</p>
                                <p style="color: #1f7550; font-size: clamp(1.2rem, 3vw, 1.5rem); font-weight: 700; margin: 0;">{{ number_format($reservation->total_price, 2) }}€</p>
                            </div>
                            <a href="{{ route('reservations.show', $reservation->id) }}" style="
                                display: inline-block;
                                background: #1f7550;
                                color: white;
                                padding: 8px 16px;
                                border-radius: 6px;
                                text-decoration: none;
                                font-weight: 600;
                                font-size: clamp(0.85rem, 2vw, 0.95rem);
                            ">
                                Voir →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="card" style="padding: clamp(40px, 8vw, 60px); text-align: center;">
            <p style="color: #999; font-size: clamp(1rem, 2vw, 1.1rem); margin-bottom: 24px;">
                Vous n'avez pas encore de réservations.
            </p>
            <a href="{{ route('scooters.index') }}" style="
                display: inline-block;
                background: linear-gradient(135deg, #47F55B 0%, #07d65d 100%);
                color: #0f172a;
                padding: clamp(12px, 2vw, 16px) clamp(24px, 4vw, 32px);
                border-radius: 8px;
                text-decoration: none;
                font-weight: 700;
                font-size: clamp(0.95rem, 2vw, 1.1rem);
            ">
                🚀 Explorez nos trottinettes
            </a>
        </div>
    @endif
</div>
@endsection
