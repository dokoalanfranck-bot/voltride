@extends('layouts.app')

@section('title', 'Détails de ma réservation - voltride')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <!-- En-tête -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <div>
            <h1 style="color: #1f7550; font-size: 2.5rem; font-weight: 700; margin-bottom: 8px;">
                📋 Détails de ma réservation
            </h1>
            <p style="color: #4a5568; font-size: 1rem;">
                Réservation #{{ $reservation->id }}
            </p>
        </div>
        <a href="{{ route('reservations.index') }}" style="
            display: inline-block;
            background: #1f7550;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        ">← Retour</a>
    </div>

    <!-- Messages d'erreur/succès -->
    @if ($errors->any())
        <div style="background: #fee; border: 1px solid #fcc; color: #c33; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Infos principales -->
    <div class="card" style="overflow: hidden; margin-bottom: 24px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; padding: 32px;">
            <!-- Image et infos trottinette -->
            <div>
                <img 
                    src="{{ $reservation->scooter?->images->first()?->getUrl() ?? 'https://via.placeholder.com/400x300?text=Trottinette' }}" 
                    alt="{{ $reservation->scooter?->name ?? 'Trottinette' }}" 
                    style="width: 100%; height: 300px; border-radius: 12px; object-fit: contain; background: #f9f9f9; padding: 20px; margin-bottom: 20px;">
                
                <h2 style="color: #1f7550; font-size: 1.5rem; font-weight: 700; margin-bottom: 8px;">
                    {{ $reservation->scooter?->brand ?? 'Trottinette' }} {{ $reservation->scooter?->model ?? '' }}
                </h2>
                <p style="color: #4a5568; margin-bottom: 16px;">
                    📍 {{ $reservation->scooter?->location ?? 'Localisation non disponible' }}
                </p>
                
                <div style="background: #f5f5f5; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                    <p style="color: #999; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 8px;">Type</p>
                    <p style="color: #333; font-weight: 600;">{{ $reservation->scooter?->type ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Détails de la réservation -->
            <div>
                <div style="background: #f5f5f5; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                    <p style="color: #999; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 8px;">Statut</p>
                    <span style="
                        display: inline-block;
                        padding: 8px 16px;
                        border-radius: 20px;
                        font-weight: 600;
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
                        @endif
                    ">
                        @if ($reservation->status === 'pending')
                            ⏳ En attente
                        @elseif ($reservation->status === 'active')
                            ✓ En cours
                        @elseif ($reservation->status === 'completed')
                            ✓ Complétée
                        @elseif ($reservation->status === 'cancelled')
                            ✗ Annulée
                        @endif
                    </span>
                </div>

                <div style="background: #f5f5f5; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                    <p style="color: #999; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 8px;">Début</p>
                    <p style="color: #333; font-weight: 600; font-size: 1.1rem;">
                        {{ $reservation->start_time->format('d/m/Y') }}
                    </p>
                    <p style="color: #666; font-size: 0.9rem;">
                        À {{ $reservation->start_time->format('H:i') }}
                    </p>
                </div>

                <div style="background: #f5f5f5; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                    <p style="color: #999; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 8px;">Fin</p>
                    <p style="color: #333; font-weight: 600; font-size: 1.1rem;">
                        {{ $reservation->end_time->format('d/m/Y') }}
                    </p>
                    <p style="color: #666; font-size: 0.9rem;">
                        À {{ $reservation->end_time->format('H:i') }}
                    </p>
                </div>

                <div style="background: #f5f5f5; padding: 16px; border-radius: 8px;">
                    <p style="color: #999; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 8px;">Statut de paiement</p>
                    <span style="
                        display: inline-block;
                        padding: 8px 16px;
                        border-radius: 20px;
                        font-weight: 600;
                        @if ($reservation->payment_status === 'pending')
                            background: #fff3cd;
                            color: #856404;
                        @elseif ($reservation->payment_status === 'completed')
                            background: #dcfce7;
                            color: #166534;
                        @elseif ($reservation->payment_status === 'failed')
                            background: #fed7aa;
                            color: #92400e;
                        @elseif ($reservation->payment_status === 'refunded')
                            background: #e7e7ff;
                            color: #3f3fcc;
                        @endif
                    ">
                        @if ($reservation->payment_status === 'pending')
                            ⏳ En attente
                        @elseif ($reservation->payment_status === 'completed')
                            ✓ Payé
                        @elseif ($reservation->payment_status === 'failed')
                            ✗ Échoué
                        @elseif ($reservation->payment_status === 'refunded')
                            ↩️  Remboursé
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Prix et détails financiers -->
    <div class="card" style="padding: 32px; margin-bottom: 24px;">
        <h2 style="color: #1f7550; font-size: 1.5rem; font-weight: 700; margin-bottom: 20px;">
            💰 Détails du prix
        </h2>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
            <div style="background: #f5f5f5; padding: 16px; border-radius: 8px;">
                <p style="color: #999; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 8px;">Prix par heure</p>
                <p style="color: #1f7550; font-size: 1.5rem; font-weight: 700;">
                    {{ number_format($reservation->scooter?->price_hour ?? 0, 2) }} €
                </p>
            </div>

            <div style="background: #f5f5f5; padding: 16px; border-radius: 8px;">
                <p style="color: #999; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 8px;">Prix par jour</p>
                <p style="color: #1f7550; font-size: 1.5rem; font-weight: 700;">
                    {{ number_format($reservation->scooter?->price_day ?? 0, 2) }} €
                </p>
            </div>

            @if ($reservation->delay_minutes > 0)
                <div style="background: #fff3cd; padding: 16px; border-radius: 8px;">
                    <p style="color: #856404; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 8px;">Retard</p>
                    <p style="color: #856404; font-size: 1.5rem; font-weight: 700;">
                        {{ $reservation->delay_minutes }} min
                    </p>
                </div>

                <div style="background: #fff3cd; padding: 16px; border-radius: 8px;">
                    <p style="color: #856404; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 8px;">Frais de retard</p>
                    <p style="color: #856404; font-size: 1.5rem; font-weight: 700;">
                        {{ number_format($reservation->delay_fee, 2) }} €
                    </p>
                </div>
            @endif
        </div>

        <div style="border-top: 2px solid #ddd; margin-top: 24px; padding-top: 24px; text-align: right;">
            <p style="color: #999; font-size: 0.9rem; margin-bottom: 8px;">Montant total</p>
            <p style="color: #1f7550; font-size: 2.5rem; font-weight: 800;">
                {{ number_format($reservation->total_price, 2) }} €
            </p>
        </div>
    </div>

    <!-- Actions -->
    <div class="card" style="padding: 32px;">
        <h2 style="color: #1f7550; font-size: 1.5rem; font-weight: 700; margin-bottom: 20px;">
            Actions
        </h2>

        @if ($reservation->status !== 'cancelled' && $reservation->status !== 'completed')
            <form action="{{ route('reservations.cancel', $reservation) }}" method="POST" style="display: inline; margin-right: 12px;">
                @csrf
                <button type="submit" style="
                    background: #dc3545;
                    color: white;
                    padding: 12px 24px;
                    border-radius: 8px;
                    border: none;
                    cursor: pointer;
                    font-weight: 600;
                    font-size: 1rem;
                    transition: background 0.3s;
                " onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation?');">
                    ✗ Annuler la réservation
                </button>
            </form>
        @endif

        @if ($reservation->payment_status === 'pending' && $reservation->status !== 'cancelled')
            <a href="{{ route('reservations.payment', $reservation) }}" style="
                display: inline-block;
                background: #1f7550;
                color: white;
                padding: 12px 24px;
                border-radius: 8px;
                text-decoration: none;
                font-weight: 600;
                font-size: 1rem;
                transition: background 0.3s;
            ">
                💳 Procéder au paiement
            </a>
        @endif

        <a href="{{ route('reservations.index') }}" style="
            display: inline-block;
            background: #6c757d;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: background 0.3s;
            margin-left: 12px;
        ">
            ← Retour à mes réservations
        </a>
    </div>
</div>
@endsection
