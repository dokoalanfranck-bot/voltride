@extends('layouts.app')

@section('title', 'Réservation #' . $reservation->id . ' - VoltRide Admin')

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 60px; max-width: 1000px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; flex-wrap: wrap; gap: 16px;">
        <div>
            <a href="{{ route('admin.reservations.index') }}" style="color: var(--gray); text-decoration: none; font-size: 0.9rem; display: inline-block; margin-bottom: 8px;">← Retour aux réservations</a>
            <h1 style="font-size: 2rem; font-weight: 800; letter-spacing: -1px;">
                <i class="fa fa-file-alt" aria-hidden="true" title="Réservation"></i> <span style="color: var(--primary);">#{{ $reservation->id }}</span>
            </h1>
        </div>
        
        <!-- Status Badges -->
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            @if($reservation->status === 'pending')
                <span class="badge badge-warning"><i class="fa fa-hourglass-half" aria-hidden="true" title="En attente"></i> En attente</span>
            @elseif($reservation->status === 'active')
                <span class="badge badge-info"><i class="fa fa-play-circle" aria-hidden="true" title="En cours"></i> En cours</span>
            @elseif($reservation->status === 'completed')
                <span class="badge badge-success"><i class="fa fa-check-circle" aria-hidden="true" title="Terminée"></i> Terminée</span>
            @else
                <span class="badge badge-danger">✗ Annulée</span>
            @endif
            
            @if($reservation->payment_status === 'pending')
                <span class="badge badge-warning">💳 Paiement en attente</span>
            @elseif($reservation->payment_status === 'paid')
                <span class="badge badge-success">💳 Payé</span>
            @else
                <span class="badge badge-info">💳 Remboursé</span>
            @endif
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 24px;">
            @foreach ($errors->all() as $error)
                <p style="margin: 4px 0;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Main Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
        <!-- Client Info -->
        <div class="card">
            <div class="card-body">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px;">👤 Informations client</h3>
                
                <div style="display: grid; gap: 16px;">
                    <div>
                        <p style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 4px;">Nom</p>
                        <p style="font-weight: 600; font-size: 1.1rem;">{{ $reservation->guest_name ?? $reservation->user?->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 4px;">Email</p>
                        <p style="font-weight: 600;">{{ $reservation->guest_email ?? $reservation->user?->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 4px;">Téléphone</p>
                        <p style="font-weight: 600;">{{ $reservation->guest_phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 4px;">Type de client</p>
                        <p style="font-weight: 600;">{!! $reservation->is_tourist ? '<i class=\'fa fa-plane\' aria-hidden=\'true\' title=\'Touriste\'></i> Touriste' : '<i class=\'fa fa-user\' aria-hidden=\'true\' title=\'Local\'></i> Local' !!}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scooter Info -->
        <div class="card">
            <div class="card-body">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px;"><i class="fa fa-bicycle" aria-hidden="true" title="Trottinette"></i> Trottinette</h3>
                
                <div style="display: flex; gap: 16px; align-items: center; margin-bottom: 16px;">
                    @if($reservation->scooter?->images->count() > 0)
                        <img src="{{ asset('storage/' . $reservation->scooter->images->first()->image_path) }}" alt="{{ $reservation->scooter->name }}" style="width: 80px; height: 80px; border-radius: 8px; object-fit: contain; background: var(--dark-lighter);">
                    @else
                        <div style="width: 80px; height: 80px; border-radius: 8px; background: var(--dark-lighter); display: flex; align-items: center; justify-content: center; font-size: 2rem; opacity: 0.5;"><i class="fa fa-bicycle" aria-hidden="true" title="Trottinette"></i></div>
                    @endif
                    <div>
                        <p style="font-weight: 700; color: var(--primary); font-size: 1.2rem;">{{ $reservation->scooter?->name ?? 'N/A' }}</p>
                        <p style="color: var(--gray);"><i class="fa fa-map-marker-alt" aria-hidden="true" title="Localisation"></i> {{ $reservation->scooter?->location ?? 'Paris' }}</p>
                    </div>
                </div>
                
                <div style="background: var(--dark-lighter); padding: 12px; border-radius: 8px;">
                    <p style="color: var(--gray); font-size: 0.8rem; margin-bottom: 4px;"><i class="fa fa-map-marker-alt" aria-hidden="true" title="Localisation"></i> Localisation</p>
                    <p style="font-weight: 600;">{{ $reservation->scooter?->location ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservation Details -->
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-body">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px;"><i class="fa fa-calendar-alt" aria-hidden="true" title="Détails de la réservation"></i> Détails de la réservation</h3>
            
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
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
                <div style="background: var(--dark-lighter); padding: 16px; border-radius: 8px;">
                    <p style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 8px;">Durée</p>
                    <p style="font-weight: 800; font-size: 1.3rem; color: var(--primary);">
                        @php
                            $totalMinutes = $reservation->start_time->diffInMinutes($reservation->end_time);
                            $hours = intval($totalMinutes / 60);
                            $minutes = $totalMinutes % 60;
                        @endphp
                        {{ $hours > 0 ? $hours . 'h ' : '' }}{{ $minutes > 0 ? $minutes . 'min' : '' }}
                    </p>
                </div>
                <div style="background: rgba(0, 255, 106, 0.1); padding: 16px; border-radius: 8px; border: 1px solid rgba(0, 255, 106, 0.2);">
                    <p style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 8px;">Total</p>
                    <p class="price" style="font-size: 1.8rem; font-weight: 800;">{{ number_format($reservation->total_price, 2) }} $</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Actions -->
    <div class="card">
        <div class="card-body">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px;"><i class="fa fa-cogs" aria-hidden="true" title="Actions administrateur"></i> Actions administrateur</h3>
            
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <!-- Status Update -->
                @if($reservation->status === 'pending')
                    <form action="{{ route('admin.reservations.update', $reservation) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check" aria-hidden="true" title="Activer"></i> Activer la réservation
                        </button>
                    </form>
                    <form action="{{ route('admin.reservations.update', $reservation) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn" style="background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3);" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                            ✗ Annuler
                        </button>
                    </form>
                @endif
                
                @if($reservation->status === 'active')
                    <form action="{{ route('admin.reservations.update', $reservation) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-flag-checkered" aria-hidden="true" title="Terminer"></i> Marquer comme terminée
                        </button>
                    </form>
                @endif

                <!-- Payment Validation -->
                @if($reservation->payment_status === 'pending')
                    <form action="{{ route('admin.reservations.validatePayment', $reservation) }}" method="POST" style="display: inline;">
                        @csrf
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <select name="payment_method" class="form-input" style="width: auto; padding: 10px 16px;">
                                <option value="cash">💵 Espèces</option>
                                <option value="card">💳 Carte</option>
                                <option value="transfer">🏦 Virement</option>
                            </select>
                            <button type="submit" class="btn" style="background: rgba(34, 197, 94, 0.2); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3);">
                                <i class="fa fa-credit-card" aria-hidden="true" title="Valider paiement"></i> Valider le paiement
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            <!-- Payment Info -->
            @if($reservation->payment_status === 'paid')
                <div style="margin-top: 20px; padding: 16px; background: rgba(34, 197, 94, 0.1); border-radius: 8px; border: 1px solid rgba(34, 197, 94, 0.2);">
                    <p style="color: #22c55e; font-weight: 600;">
                        <i class="fa fa-check-double" aria-hidden="true" title="Paiement validé"></i> Paiement validé
                        @if($reservation->payment_method)
                            <span style="opacity: 0.8;">- {{ ucfirst($reservation->payment_method) }}</span>
                        @endif
                        @if($reservation->paid_at)
                            <span style="opacity: 0.8;">le {{ $reservation->paid_at->format('d/m/Y à H:i') }}</span>
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .container > div:nth-child(4) {
            grid-template-columns: 1fr !important;
        }
        .card-body > div[style*="grid-template-columns: repeat(4"] {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
    @media (max-width: 500px) {
        .card-body > div[style*="grid-template-columns: repeat"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
