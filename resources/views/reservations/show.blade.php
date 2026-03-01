@extends('layouts.app')

@section('title', 'Réservation #' . $reservation->id . ' - VoltRide')

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 60px; max-width: 1000px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; flex-wrap: wrap; gap: 16px;">
        <div>
            <a href="{{ route('reservations.index') }}" style="color: var(--gray); text-decoration: none; font-size: 0.9rem; display: inline-block; margin-bottom: 12px;">
                ← Retour aux réservations
            </a>
            <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 8px; letter-spacing: -1px;">
                📋 Réservation <span style="color: var(--primary);">#{{ $reservation->id }}</span>
            </h1>
        </div>
        
        <!-- Status Badges -->
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            @if($reservation->status === 'pending')
                <span class="badge badge-warning">⏳ En attente</span>
            @elseif($reservation->status === 'active')
                <span class="badge badge-info">✓ En cours</span>
            @elseif($reservation->status === 'completed')
                <span class="badge badge-success">✓ Terminée</span>
            @elseif($reservation->status === 'cancelled')
                <span class="badge badge-danger">✗ Annulée</span>
            @endif
            
            @if($reservation->payment_status === 'pending')
                <span class="badge badge-warning">💳 Paiement en attente</span>
            @elseif($reservation->payment_status === 'paid')
                <span class="badge badge-success">💳 Payé</span>
            @elseif($reservation->payment_status === 'refunded')
                <span class="badge badge-info">💳 Remboursé</span>
            @endif
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 24px;">
            @foreach ($errors->all() as $error)
                <p style="margin: 4px 0;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Main Content Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-bottom: 32px;">
        <!-- Scooter Info -->
        <div class="card">
            <div class="card-body">
                @if($reservation->scooter?->images->count() > 0)
                    <img src="{{ asset('storage/' . $reservation->scooter->images->first()->image_path) }}" alt="{{ $reservation->scooter->name }}" style="width: 100%; height: 200px; border-radius: 12px; object-fit: contain; background: var(--dark-lighter); margin-bottom: 20px;">
                @else
                    <div style="width: 100%; height: 200px; border-radius: 12px; background: var(--dark-lighter); display: flex; align-items: center; justify-content: center; font-size: 4rem; opacity: 0.3; margin-bottom: 20px;">🛴</div>
                @endif
                
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--primary); margin-bottom: 8px;">
                    {{ $reservation->scooter?->name ?? 'Trottinette supprimée' }}
                </h2>
                <p style="color: var(--gray); margin-bottom: 16px;">
                    📍 {{ $reservation->scooter?->location ?? 'Localisation non disponible' }}
                </p>
                
                <div style="background: var(--dark-lighter); padding: 16px; border-radius: 8px;">
                    <p style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 8px;">Type</p>
                    <p style="font-weight: 600;">{{ $reservation->scooter?->type ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Reservation Details -->
        <div class="card">
            <div class="card-body">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px;">📅 Détails de la réservation</h3>
                
                <!-- Dates -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                    <div style="background: var(--dark-lighter); padding: 16px; border-radius: 8px;">
                        <p style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 8px;">Début</p>
                        <p style="font-weight: 600; color: var(--primary);">
                            {{ $reservation->start_time->format('d/m/Y') }}<br>
                            <span style="font-size: 1.2rem;">{{ $reservation->start_time->format('H:i') }}</span>
                        </p>
                    </div>
                    <div style="background: var(--dark-lighter); padding: 16px; border-radius: 8px;">
                        <p style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 8px;">Fin</p>
                        <p style="font-weight: 600; color: var(--primary);">
                            {{ $reservation->end_time->format('d/m/Y') }}<br>
                            <span style="font-size: 1.2rem;">{{ $reservation->end_time->format('H:i') }}</span>
                        </p>
                    </div>
                </div>

                <!-- Duration -->
                <div style="background: var(--dark-lighter); padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                    <p style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 8px;">Durée totale</p>
                    <p style="font-size: 1.5rem; font-weight: 800; color: var(--primary); margin: 0;">
                        @php
                            $totalMinutes = $reservation->start_time->diffInMinutes($reservation->end_time);
                            $hours = intval($totalMinutes / 60);
                            $minutes = $totalMinutes % 60;
                        @endphp
                        {{ $hours > 0 ? $hours . 'h ' : '' }}{{ $minutes > 0 ? $minutes . 'min' : '' }}
                    </p>
                </div>

                <!-- Customer Type -->
                <div style="background: var(--dark-lighter); padding: 16px; border-radius: 8px;">
                    <p style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 8px;">Type de client</p>
                    <p style="font-weight: 600; font-size: 1.1rem;">
                        {{ $reservation->is_tourist ? '✈️ Touriste' : '👤 Local' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Details -->
    <div class="card" style="margin-bottom: 32px;">
        <div class="card-body">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 24px;">💰 Détails du prix</h3>
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px; background: rgba(0, 255, 106, 0.1); border-radius: 12px; border: 1px solid rgba(0, 255, 106, 0.2);">
                <span style="font-weight: 700; font-size: 1.2rem;">Total à payer</span>
                <span class="price" style="font-size: 2.5rem; font-weight: 800;">{{ number_format($reservation->total_price, 2) }} $</span>
            </div>

            @if($reservation->payment_method)
                <div style="margin-top: 16px; padding: 16px; background: var(--dark-lighter); border-radius: 8px; display: flex; justify-content: space-between;">
                    <span style="color: var(--gray);">Méthode de paiement</span>
                    <span style="font-weight: 600;">{{ ucfirst($reservation->payment_method) }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Guest Info -->
    <div class="card" style="margin-bottom: 32px;">
        <div class="card-body">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 24px;">👤 Informations du client</h3>
            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                <div>
                    <p style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 8px;">Nom</p>
                    <p style="font-weight: 600; font-size: 1.1rem;">{{ $reservation->guest_name ?? $reservation->user?->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 8px;">Email</p>
                    <p style="font-weight: 600;">{{ $reservation->guest_email ?? $reservation->user?->email ?? 'N/A' }}</p>
                </div>
                <div>
                    <p style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 8px;">Téléphone</p>
                    <p style="font-weight: 600;">{{ $reservation->guest_phone ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    @if($reservation->status === 'pending' || $reservation->status === 'active')
        <div class="card">
            <div class="card-body">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px;">⚡ Actions</h3>
                
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    @if($reservation->status === 'pending')
                        <form action="{{ route('reservations.cancel', $reservation) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger">
                                ✗ Annuler la réservation
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('scooters.index') }}" class="btn btn-secondary">
                        🛴 Voir d'autres trottinettes
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    @media (max-width: 768px) {
        .container > div:nth-child(4) {
            grid-template-columns: 1fr !important;
        }
        .card-body > div:last-child {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
