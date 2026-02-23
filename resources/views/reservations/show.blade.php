@extends('layouts.app')

@section('title', 'Détails de ma réservation - VoltRide')

@section('content')
@include('components.responsive-styles')

<style>
    @media (max-width: 768px) {
        .show-grid {
            grid-template-columns: 1fr !important;
        }
    }
    
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        animation: fadeInUp 0.6s ease-out;
    }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div style="max-width: 1000px; margin: 0 auto; padding: 1rem 1.5rem; overflow-x: hidden;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="color: #0a9b3a; font-size: clamp(1.8rem, 6vw, 2.5rem); font-weight: 700; margin-bottom: 8px;">
                📋 Détails de ma réservation
            </h1>
            <p style="color: #4a5568; font-size: clamp(0.95rem, 2vw, 1.1rem); margin: 0;">
                Réservation #{{ $reservation->id }}
            </p>
        </div>
        <a href="{{ route('reservations.index') }}" style="
            display: inline-block;
            background: linear-gradient(135deg, #47F55B 0%, #07d65d 100%);
            color: #0f172a;
            padding: clamp(10px, 2vw, 14px) clamp(16px, 4vw, 24px);
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            font-size: clamp(0.9rem, 2vw, 1rem);
            white-space: nowrap;
        ">← Retour</a>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div style="background: #fee; border: 1px solid #fcc; color: #c33; padding: 16px; border-radius: 8px; margin-bottom: 24px; font-size: clamp(0.9rem, 2vw, 1rem);">
            @foreach ($errors->all() as $error)
                <p style="margin: 8px 0; padding: 0;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Main Info -->
    <div class="card show-grid" style="overflow: hidden; margin-bottom: 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 32px; padding: clamp(20px, 4vw, 32px);">
        <!-- Scooter Image & Info -->
        <div>
            <img 
                src="{{ $reservation->scooter?->images->first()?->getUrl() ?? 'https://via.placeholder.com/400x300?text=Trottinette' }}" 
                alt="{{ $reservation->scooter?->name ?? 'Trottinette' }}" 
                style="width: 100%; height: clamp(200px, 50vw, 300px); border-radius: 12px; object-fit: contain; background: #f9f9f9; padding: 20px; margin-bottom: 20px; box-sizing: border-box;">
            
            <h2 style="color: #1f7550; font-size: clamp(1.2rem, 4vw, 1.8rem); font-weight: 700; margin-bottom: 8px;">
                {{ $reservation->scooter?->name ?? 'Trottinette' }}
            </h2>
            <p style="color: #4a5568; margin-bottom: 16px; font-size: clamp(0.9rem, 2vw, 1rem);">
                📍 {{ $reservation->scooter?->location ?? 'Localisation non disponible' }}
            </p>
            
            <div style="background: #f5f5f5; padding: 16px; border-radius: 8px;">
                <p style="color: #999; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 8px;">Type</p>
                <p style="color: #333; font-weight: 600; font-size: clamp(0.95rem, 2vw, 1.1rem);">{{ $reservation->scooter?->type ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Reservation Details -->
        <div>
            <!-- Status -->
            <div style="background: #f5f5f5; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                <p style="color: #999; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 8px;">Statut</p>
                <span style="
                    display: inline-block;
                    padding: 8px 14px;
                    border-radius: 20px;
                    font-weight: 600;
                    font-size: clamp(0.85rem, 2vw, 1rem);
                    @if ($reservation->status === 'pending')
                        background: #fff3cd;
                        color: #856404;
                    @elseif ($reservation->status === 'active')
                        background: #dcfce7;
                        color: #166534;
                    @elseif ($reservation->status === 'completed')
                        background: #d1fae5;
                        color: #065f46;
                    @elseif ($reservation->status === 'cancelled')
                        background: #fed7aa;
                        color: #92400e;
                    @else
                        background: #e5e7eb;
                        color: #374151;
                    @endif
                ">
                    @if ($reservation->status === 'pending') ⏳ En attente
                    @elseif ($reservation->status === 'active') ✓ En cours
                    @elseif ($reservation->status === 'completed') ✓ Terminée
                    @elseif ($reservation->status === 'cancelled') ✗ Annulée
                    @else {{ $reservation->status }}
                    @endif
                </span>
            </div>

            <!-- Payment Status -->
            <div style="background: #f5f5f5; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                <p style="color: #999; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 8px;">Paiement</p>
                <span style="
                    display: inline-block;
                    padding: 8px 14px;
                    border-radius: 20px;
                    font-weight: 600;
                    font-size: clamp(0.85rem, 2vw, 1rem);
                    @if ($reservation->payment_status === 'pending')
                        background: #fff3cd;
                        color: #856404;
                    @elseif ($reservation->payment_status === 'paid')
                        background: #dcfce7;
                        color: #166534;
                    @elseif ($reservation->payment_status === 'refunded')
                        background: #d1fae5;
                        color: #065f46;
                    @else
                        background: #e5e7eb;
                        color: #374151;
                    @endif
                ">
                    @if ($reservation->payment_status === 'pending') ⏳ En attente
                    @elseif ($reservation->payment_status === 'paid') ✓ Payé ({{ $reservation->payment_method ?? 'N/A' }})
                    @elseif ($reservation->payment_status === 'refunded') ✓ Remboursé
                    @else {{ $reservation->payment_status }}
                    @endif
                </span>
            </div>

            <!-- Dates & Times -->
            <div style="background: #f5f5f5; padding: 16px; border-radius: 8px;">
                <p style="color: #999; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 8px;">Période</p>
                <p style="color: #333; font-weight: 600; margin: 8px 0; font-size: clamp(0.9rem, 2vw, 1rem);">
                    {{ $reservation->start_time->format('d/m/Y H:i') }}
                    <br>
                    {{ $reservation->end_time->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Pricing Details -->
    <div class="card" style="padding: clamp(20px, 4vw, 32px); margin-bottom: 24px;">
            <h2 style="color: #0a9b3a; font-size: clamp(1.2rem, 4vw, 1.5rem); font-weight: 700; margin-bottom: 20px;">💰 Détails du prix</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div style="background: #f9f9f9; padding: 16px; border-radius: 8px;">
                <p style="color: #999; font-size: 0.9rem; margin-bottom: 8px;">Durée de location</p>
                <p style="font-size: clamp(1.1rem, 3vw, 1.4rem); font-weight: 700; color: #1f7550; margin: 0;">
                    {{ intval($reservation->start_time->diffInMinutes($reservation->end_time) / 60) }}h 
                    {{ $reservation->start_time->diffInMinutes($reservation->end_time) % 60 }}min
                </p>
            </div>

            <div style="background: #f9f9f9; padding: 16px; border-radius: 8px;">
                <p style="color: #999; font-size: 0.9rem; margin-bottom: 8px;">Statut client</p>
                <p style="font-size: clamp(1rem, 2vw, 1.1rem); font-weight: 700; color: #1f7550; margin: 0;">
                    {{ $reservation->is_tourist ? '✈️ Touriste' : '👤 Habitant' }}
                </p>
            </div>
        </div>

        <hr style="border: none; border-top: 2px solid #e2e8f0; margin: 20px 0;">

        <div style="display: flex; justify-content: space-between; align-items: center; font-size: clamp(1.1rem, 3vw, 1.3rem);">
            <span style="font-weight: 700; color: #1f7550;">Total:</span>
            <span style="font-weight: 700; color: #1f7550; font-size: clamp(1.5rem, 4vw, 2rem);">{{ number_format($reservation->total_price, 2) }}€</span>
        </div>
    </div>

    <!-- Guest/User Info -->
    <div class="card" style="padding: clamp(20px, 4vw, 32px); margin-bottom: 24px;">
<h3 style="color: #0a9b3a; font-size: clamp(1.2rem, 3vw, 1.5rem); font-weight: 700; margin-bottom: 20px;">👤 Informations</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div>
                <p style="color: #999; font-size: 0.9rem; margin-bottom: 8px;">Nom</p>
                <p style="color: #333; font-weight: 600; font-size: clamp(1rem, 2vw, 1.1rem);">{{ $reservation->guest_name ?? $reservation->user?->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p style="color: #999; font-size: 0.9rem; margin-bottom: 8px;">Email</p>
                <p style="color: #333; font-weight: 600; font-size: clamp(0.9rem, 2vw, 1rem); word-break: break-all;">{{ $reservation->guest_email ?? $reservation->user?->email ?? 'N/A' }}</p>
            </div>
            <div>
                <p style="color: #999; font-size: 0.9rem; margin-bottom: 8px;">Téléphone</p>
                <p style="color: #333; font-weight: 600; font-size: clamp(1rem, 2vw, 1.1rem);">{{ $reservation->guest_phone ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Actions -->
    @if ($reservation->status === 'pending' || $reservation->status === 'active')
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            @if ($reservation->status === 'active')
                <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST" style="flex: 1; min-width: 200px;">
                    @csrf
                    @method('POST')
                    <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation?')" style="
                        width: 100%;
                        padding: clamp(12px, 2vw, 16px);
                        background: #dc2626;
                        color: white;
                        border: none;
                        border-radius: 8px;
                        font-weight: 700;
                        cursor: pointer;
                        font-size: clamp(0.95rem, 2vw, 1.1rem);
                    ">
                        ✗ Annuler la réservation
                    </button>
                </form>
            @endif
        </div>
    @endif
</div>
@endsection
