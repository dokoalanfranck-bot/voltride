@extends('layouts.app')

@section('title', 'Mes réservations - VoltRide')

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 60px;">
    <!-- Header -->
    <div style="margin-bottom: 40px;">
        <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 8px; letter-spacing: -1px;">
            <i class="fa-solid fa-calendar-check"></i> Mes <span style="color: var(--primary);">réservations</span>
        </h1>
        <p style="color: var(--gray); font-size: 1.1rem;">
            Suivez et gérez toutes vos locations de trottinettes
        </p>
    </div>

    @if($reservations->count() > 0)
        <!-- Stats Cards -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 40px;">
            <div class="card" style="border-top: 3px solid var(--primary);">
                <div class="card-body" style="text-align: center;">
                    <p style="color: var(--gray); font-size: 0.85rem; margin-bottom: 8px; text-transform: uppercase;">Total</p>
                    <p style="color: var(--primary); font-size: 2rem; font-weight: 800; margin: 0;">{{ $reservations->count() }}</p>
                </div>
            </div>
            <div class="card" style="border-top: 3px solid #3b82f6;">
                <div class="card-body" style="text-align: center;">
                    <p style="color: var(--gray); font-size: 0.85rem; margin-bottom: 8px; text-transform: uppercase;">En cours</p>
                    <p style="color: #3b82f6; font-size: 2rem; font-weight: 800; margin: 0;">{{ $reservations->where('status', 'active')->count() }}</p>
                </div>
            </div>
            <div class="card" style="border-top: 3px solid #22c55e;">
                <div class="card-body" style="text-align: center;">
                    <p style="color: var(--gray); font-size: 0.85rem; margin-bottom: 8px; text-transform: uppercase;">Complétées</p>
                    <p style="color: #22c55e; font-size: 2rem; font-weight: 800; margin: 0;">{{ $reservations->where('status', 'completed')->count() }}</p>
                </div>
            </div>
            <div class="card" style="border-top: 3px solid #f59e0b;">
                <div class="card-body" style="text-align: center;">
                    <p style="color: var(--gray); font-size: 0.85rem; margin-bottom: 8px; text-transform: uppercase;">Dépensé</p>
                    <p style="color: #f59e0b; font-size: 2rem; font-weight: 800; margin: 0;">{{ number_format($reservations->sum('total_price'), 2) }} $</p>
                </div>
            </div>
        </div>

        <!-- Reservations List -->
        <div style="display: flex; flex-direction: column; gap: 16px;">
            @foreach($reservations as $reservation)
                <div class="card" style="overflow: hidden; transition: transform 0.3s, box-shadow 0.3s;">
                    <div class="card-body" style="display: grid; grid-template-columns: auto 1fr auto; gap: 24px; align-items: center;">
                        <!-- Scooter Image -->
                        @if($reservation->scooter?->images->count() > 0)
                            <img src="{{ asset('storage/' . $reservation->scooter->images->first()->image_path) }}" alt="{{ $reservation->scooter->name }}" style="width: 100px; height: 100px; border-radius: 12px; object-fit: contain; background: var(--dark-lighter);">
                        @else
                            <div style="width: 100px; height: 100px; border-radius: 12px; background: var(--dark-lighter); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; opacity: 0.5;"><i class="fa-solid fa-motorcycle"></i></div>
                        @endif

                        <!-- Reservation Details -->
                        <div>
                            <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--primary); margin-bottom: 8px;">
                                {{ $reservation->scooter?->name ?? 'Trottinette supprimée' }}
                            </h3>
                            <p style="color: var(--gray); margin-bottom: 12px;">
                                <i class="fa-solid fa-map-marker-alt"></i> {{ $reservation->scooter?->location ?? 'Localisation non disponible' }}
                            </p>
                            
                            <div style="display: flex; gap: 32px; margin-bottom: 12px; flex-wrap: wrap;">
                                <div>
                                    <p style="color: var(--gray); font-size: 0.8rem; margin-bottom: 2px;">Du</p>
                                    <p style="font-weight: 600;">
                                       {{ $reservation->start_time?->format('d/m/Y H:i') ?? 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <p style="color: var(--gray); font-size: 0.8rem; margin-bottom: 2px;">Au</p>
                                    <p style="font-weight: 600;">
                                        {{ $reservation->end_time?->format('d/m/Y H:i') ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Status -->
                            @if($reservation->status === 'active')
                                <span class="badge badge-info"><i class="fa-solid fa-play-circle"></i> En cours</span>
                            @elseif($reservation->status === 'completed')
                                <span class="badge badge-success"><i class="fa-solid fa-check-circle"></i> Complétée</span>
                            @elseif($reservation->status === 'cancelled')
                                <span class="badge badge-danger">✗ Annulée</span>
                            @else
                                <span class="badge badge-warning"><i class="fa-solid fa-hourglass-half"></i> En attente</span>
                            @endif
                        </div>

                        <!-- Price & Action -->
                        <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 12px;">
                            <div>
                                <p style="color: var(--gray); font-size: 0.8rem; margin-bottom: 4px;">Total</p>
                                <p class="price price-lg">{{ number_format($reservation->total_price, 2) }} $</p>
                            </div>
                            <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-primary">
                                Voir détails →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($reservations->hasPages())
            <div style="margin-top: 32px; display: flex; justify-content: center;">
                {{ $reservations->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="card" style="text-align: center;">
            <div class="card-body" style="padding: 60px 20px;">
                <div style="font-size: 4rem; margin-bottom: 24px; opacity: 0.5;"><i class="fa-solid fa-motorcycle"></i></div>
                <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 12px;">Aucune réservation</h3>
                <p style="color: var(--gray); margin-bottom: 24px; max-width: 400px; margin-left: auto; margin-right: auto;">
                    Vous n'avez pas encore effectué de réservation. Explorez notre flotte et réservez votre première trottinette !
                </p>
                <a href="{{ route('scooters.index') }}" class="btn btn-primary btn-lg">
                    <i class="fa-solid fa-arrow-right"></i> Découvrir nos trottinettes
                </a>
            </div>
        </div>
    @endif
</div>

<style>
    @media (max-width: 900px) {
        .container > div:nth-child(2) {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
    @media (max-width: 600px) {
        .container > div:nth-child(2) {
            grid-template-columns: 1fr !important;
        }
        .card-body {
            grid-template-columns: 1fr !important;
            text-align: center;
        }
        .card-body > div:last-child {
            align-items: center !important;
        }
    }
</style>
@endsection
