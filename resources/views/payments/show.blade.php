@extends('layouts.app')

@section('title', 'Paiement - VoltRide')

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 60px; max-width: 1000px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; flex-wrap: wrap; gap: 16px;">
        <div>
            <a href="{{ route('reservations.show', $reservation) }}" style="color: var(--gray); text-decoration: none; font-size: 0.9rem; display: inline-block; margin-bottom: 12px;">
                ← Retour à la réservation
            </a>
            <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 8px; letter-spacing: -1px;">
                <i class="fa-solid fa-credit-card"></i> <span style="color: var(--primary);">Paiement</span>
            </h1>
            <p style="color: var(--gray);">Réservation #{{ $reservation->id }}</p>
        </div>
        <span class="badge badge-warning"><i class="fa-solid fa-hourglass-half"></i> En attente de paiement</span>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 24px;">
            @foreach ($errors->all() as $error)
                <p style="margin: 4px 0;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Main Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
        <!-- Reservation Summary -->
        <div class="card">
            <div class="card-body">
                <h2 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 24px;"><i class="fa-solid fa-file-alt"></i> Résumé de la réservation</h2>

                <!-- Scooter Image -->
                @if($reservation->scooter?->images->count() > 0)
                    <img src="{{ asset('storage/' . $reservation->scooter->images->first()->image_path) }}" alt="{{ $reservation->scooter->name }}" style="width: 100%; height: 180px; border-radius: 12px; object-fit: contain; background: var(--dark-lighter); margin-bottom: 20px;">
                @else
                    <div style="width: 100%; height: 180px; border-radius: 12px; background: var(--dark-lighter); display: flex; align-items: center; justify-content: center; font-size: 4rem; opacity: 0.3; margin-bottom: 20px;"><i class="fa-solid fa-motorcycle"></i></div>
                @endif

                <!-- Scooter Info -->
                <div style="margin-bottom: 20px;">
                    <p style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 4px;">Trottinette</p>
                    <p style="font-size: 1.2rem; font-weight: 700; color: var(--primary);">
                        {{ $reservation->scooter?->name ?? 'Trottinette' }}
                    </p>
                </div>

                <!-- Dates -->
                <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <p style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 8px;">Période</p>
                    <p style="font-weight: 600;">
                        Du {{ $reservation->start_time->format('d/m/Y H:i') }}<br>
                        Au {{ $reservation->end_time->format('d/m/Y H:i') }}
                    </p>
                </div>

                <!-- Pricing -->
                <div style="margin-bottom: 20px;">
                    <p style="color: var(--gray); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 12px;">Détail du prix</p>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: var(--gray);">Tarif horaire</span>
                        <span style="font-weight: 600;">{{ number_format($reservation->scooter?->price_hour ?? 0, 2) }} $ / h</span>
                    </div>

                    @php
                        $totalMinutes = $reservation->start_time->diffInMinutes($reservation->end_time);
                        $hours = intval($totalMinutes / 60);
                        $minutes = $totalMinutes % 60;
                    @endphp
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: var(--gray);">Durée</span>
                        <span style="font-weight: 600;">{{ $hours > 0 ? $hours . 'h ' : '' }}{{ $minutes > 0 ? $minutes . 'min' : '' }}</span>
                    </div>

                    @if (isset($reservation->delay_minutes) && $reservation->delay_minutes > 0)
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px; padding: 12px; background: rgba(245, 158, 11, 0.2); border-radius: 8px; margin-top: 12px;">
                            <span style="color: #f59e0b;">Frais de retard ({{ $reservation->delay_minutes }} min)</span>
                            <span style="color: #f59e0b; font-weight: 600;">{{ number_format($reservation->delay_fee ?? 0, 2) }} $</span>
                        </div>
                    @endif
                </div>

                <!-- Total -->
                <div style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: var(--dark); padding: 24px; border-radius: 12px; text-align: center;">
                    <p style="font-size: 0.9rem; margin-bottom: 8px; opacity: 0.8;">MONTANT TOTAL À PAYER</p>
                    <p style="font-size: 2.5rem; font-weight: 800;">{{ number_format($reservation->total_price, 2) }} $</p>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="card">
            <div class="card-body">
                <h2 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 24px;"><i class="fa-solid fa-euro-sign"></i> Informations de paiement</h2>

                <form id="payment-form" method="POST" action="{{ route('payments.store', $reservation) }}" style="display: none;">
                    @csrf
                    <input type="hidden" name="stripeToken" id="stripeToken">
                </form>

                <!-- Payment Method Selection -->
                <div style="margin-bottom: 24px;">
                    <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 16px;">Choisissez votre méthode de paiement</p>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px;">
                        <label class="card" style="padding: 20px; cursor: pointer; text-align: center; transition: all 0.3s; border: 2px solid var(--primary);" id="card-option">
                            <input type="radio" name="payment_method" value="card" checked style="display: none;">
                            <div style="font-size: 2rem; margin-bottom: 8px;"><i class="fa-solid fa-credit-card"></i></div>
                            <div style="font-weight: 700; color: var(--primary);">Carte</div>
                        </label>
                        <label class="card" style="padding: 20px; cursor: pointer; text-align: center; transition: all 0.3s; border: 2px solid transparent;" id="cash-option">
                            <input type="radio" name="payment_method" value="cash" style="display: none;">
                            <div style="font-size: 2rem; margin-bottom: 8px;"><i class="fa-solid fa-money-bill-wave"></i></div>
                            <div style="font-weight: 700;">Espèces</div>
                        </label>
                    </div>
                </div>

                <!-- Stripe Payment Element -->
                <div id="payment-element" style="margin-bottom: 24px; min-height: 200px; padding: 24px; background: var(--dark-lighter); border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);">
                    <div style="text-align: center; color: var(--gray);">
                        <p style="font-size: 0.9rem;"><i class="fa-solid fa-exclamation-triangle"></i> L'élément de paiement Stripe apparaîtra ici</p>
                        <p style="font-size: 0.8rem; margin-top: 8px;">Entrez les détails de votre carte pour procéder au paiement</p>
                    </div>
                </div>

                <!-- Pay Button -->
                <button type="button" id="payment-button" onclick="submitPayment()" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center; margin-bottom: 16px;">
                    <i class="fa-solid fa-lock"></i> Payer {{ number_format($reservation->total_price, 2) }} $
                </button>

                <!-- Security Info -->
                <div style="text-align: center; padding: 16px; background: var(--dark-lighter); border-radius: 8px;">
                    <p style="color: var(--gray); font-size: 0.85rem; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <span><i class="fa-solid fa-shield-alt"></i></span> Paiement sécurisé par Stripe
                    </p>
                </div>

                <!-- Alternative: Pay on site -->
                <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.1);">
                    <p style="color: var(--gray); font-size: 0.9rem; text-align: center; margin-bottom: 16px;">
                        Ou payez directement sur place
                    </p>
                    <form action="{{ route('reservations.show', $reservation) }}" method="GET">
                        <button type="submit" class="btn btn-secondary" style="width: 100%;">
                            <i class="fa-solid fa-money-bill-wave"></i> Payer sur place
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .container > div:last-child {
            grid-template-columns: 1fr !important;
        }
    }
    
    #card-option:has(input:checked),
    #cash-option:has(input:checked) {
        border-color: var(--primary) !important;
        background: rgba(0, 255, 106, 0.1) !important;
    }
</style>

<script>
    function submitPayment() {
        // Placeholder for Stripe payment logic
        const form = document.getElementById('payment-form');
        const button = document.getElementById('payment-button');
        
        button.disabled = true;
        button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Traitement en cours...';
        
        // In a real implementation, this would integrate with Stripe
        setTimeout(() => {
            alert('Le paiement Stripe nécessite une configuration complète. Veuillez contacter l\'administrateur.');
            button.disabled = false;
            button.innerHTML = '<i class="fa-solid fa-lock"></i> Payer {{ number_format($reservation->total_price, 2) }} $';
        }, 1000);
    }
    
    // Payment method selection
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const paymentElement = document.getElementById('payment-element');
            if (this.value === 'cash') {
                paymentElement.style.display = 'none';
            } else {
                paymentElement.style.display = 'block';
            }
        });
    });
</script>
@endsection
