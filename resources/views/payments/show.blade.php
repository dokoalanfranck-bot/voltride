@extends('layouts.app')

@section('title', 'Paiement - voltride')

@section('content')
<style>
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .card {
        animation: slideIn 0.6s ease-out;
    }
    .card:first-of-type {
        animation-delay: 0.1s;
        opacity: 0;
        animation-fill-mode: forwards;
    }
    .card:last-of-type {
        animation-delay: 0.2s;
        opacity: 0;
        animation-fill-mode: forwards;
    }
</style>
<div class="max-w-4xl mx-auto px-4 py-12">
    <!-- En-tête -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <div>
            <h1 style="color: #1f7550; font-size: 2.5rem; font-weight: 700; margin-bottom: 8px;">
                💳 Paiement de la réservation
            </h1>
            <p style="color: #4a5568; font-size: 1rem;">
                Réservation #{{ $reservation->id }}
            </p>
        </div>
        <a href="{{ route('reservations.show', $reservation) }}" style="
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

    <!-- Contenu principal -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
        <!-- Résumé de la réservation -->
        <div class="card" style="padding: 32px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h2 style="color: #1f7550; font-size: 1.5rem; font-weight: 700; margin-bottom: 24px;">
                📋 Résumé de la réservation
            </h2>

            <!-- Image trottinette -->
            <img 
                src="{{ $reservation->scooter?->images->first()?->getUrl() ?? 'https://via.placeholder.com/400x300?text=Trottinette' }}" 
                alt="{{ $reservation->scooter?->name ?? 'Trottinette' }}" 
                style="width: 100%; height: 200px; border-radius: 8px; object-fit: contain; background: #f9f9f9; padding: 15px; margin-bottom: 20px;">

            <!-- Infos trottinette -->
            <div style="margin-bottom: 20px;">
                <p style="color: #999; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 4px;">Trottinette</p>
                <p style="color: #333; font-size: 1.2rem; font-weight: 700;">
                    {{ $reservation->scooter?->brand ?? 'Trottinette' }} {{ $reservation->scooter?->model ?? '' }}
                </p>
            </div>

            <!-- Dates -->
            <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e2e8f0;">
                <p style="color: #999; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 8px;">Période</p>
                <p style="color: #333; font-weight: 600; margin-bottom: 4px;">
                    Du {{ $reservation->start_time->format('d/m/Y') }} à {{ $reservation->start_time->format('H:i') }} 
                    <br>
                    Au {{ $reservation->end_time->format('d/m/Y') }} à {{ $reservation->end_time->format('H:i') }}
                </p>
            </div>

            <!-- Tarification -->
            <div style="margin-bottom: 20px;">
                <p style="color: #999; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 12px; font-weight: 600;">Détail du prix</p>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <p style="color: #666;">Tarif horaire</p>
                    <p style="color: #333; font-weight: 600;">{{ number_format($reservation->scooter?->price_hour ?? 0, 2) }} € / h</p>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <p style="color: #666;">Tarif journalier</p>
                    <p style="color: #333; font-weight: 600;">{{ number_format($reservation->scooter?->price_day ?? 0, 2) }} € / jour</p>
                </div>

                @if ($reservation->delay_minutes > 0)
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; padding: 8px; background: #fff3cd; border-radius: 6px;">
                        <p style="color: #856404;">Frais de retard ({{ $reservation->delay_minutes }} min)</p>
                        <p style="color: #856404; font-weight: 600;">{{ number_format($reservation->delay_fee, 2) }} €</p>
                    </div>
                @endif
            </div>

            <!-- Total -->
            <div style="background: linear-gradient(135deg, #1f7550 0%, #2d9b6f 100%); color: white; padding: 20px; border-radius: 8px; text-align: center;">
                <p style="color: rgba(255,255,255,0.8); font-size: 0.9rem; margin-bottom: 8px;">MONTANT TOTAL À PAYER</p>
                <p style="font-size: 2.5rem; font-weight: 800;">{{ number_format($reservation->total_price, 2) }} €</p>
            </div>
        </div>

        <!-- Formulaire de paiement -->
        <div class="card" style="padding: 32px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h2 style="color: #1f7550; font-size: 1.5rem; font-weight: 700; margin-bottom: 24px;">
                💰 Informations de paiement
            </h2>

            <form id="payment-form" method="POST" action="{{ route('payments.store', $reservation) }}" style="display: none;">
                @csrf
                <input type="hidden" name="stripeToken" id="stripeToken">
            </form>

            <!-- Stripe Payment Element -->
            <div id="payment-element" style="margin-bottom: 24px; min-height: 300px; padding: 20px; background: #f5f5f5; border-radius: 8px; border: 1px solid #ddd;">
                <p style="color: #666; text-align: center;">
                    ⚠️ Les détails de paiement Stripe apparaîtront ici avec une clé de test valide
                </p>
            </div>

            <!-- Bouton de paiement -->
            <button type="button" id="payment-button" onclick="submitPayment()" style="
                width: 100%;
                background: linear-gradient(135deg, #1f7550 0%, #2d9b6f 100%);
                color: white;
                padding: 16px;
                border-radius: 8px;
                border: none;
                font-weight: 700;
                font-size: 1.1rem;
                cursor: pointer;
                transition: opacity 0.3s;
                margin-bottom: 16px;
            ">
                🔒 Payer {{ number_format($reservation->total_price, 2) }} €
            </button>

            <!-- Lien de retour -->
            <a href="{{ route('reservations.show', $reservation) }}" style="
                display: block;
                text-align: center;
                color: #1f7550;
                text-decoration: none;
                font-weight: 600;
                padding: 12px;
            ">
                ← Annuler et retourner
            </a>

            <!-- Infos de sécurité -->
            <div style="background: #f5f5f5; padding: 16px; border-radius: 8px; margin-top: 24px;">
                <p style="color: #999; font-size: 0.85rem; margin-bottom: 8px;">
                    🔒 <strong>Sécurisé par Stripe</strong>
                </p>
                <p style="color: #666; font-size: 0.9rem;">
                    Vos informations de paiement sont traitées de manière sécurisée par Stripe, un leader en matière de paiements en ligne. Nous ne stockons jamais les détails de votre carte.
                </p>
            </div>

            <!-- Infos test -->
            @if (app()->isLocal())
                <div style="background: #e7e7ff; padding: 16px; border-radius: 8px; margin-top: 16px; border: 1px solid #b3b3ff;">
                    <p style="color: #3f3fcc; font-size: 0.85rem; margin-bottom: 8px;">
                        🧪 <strong>Mode Test Stripe</strong>
                    </p>
                    <p style="color: #3f3fcc; font-size: 0.9rem; margin-bottom: 8px;">
                        Utilisez les numéros de carte de test Stripe:
                    </p>
                    <ul style="color: #3f3fcc; font-size: 0.9rem; margin: 0; padding-left: 20px;">
                        <li>Succès: 4242 4242 4242 4242</li>
                        <li>Déclinaison: 4000 0000 0000 0002</li>
                        <li>Expiration: 12/34 | CVC: 567</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Mock payment function for demonstration
function submitPayment() {
    const button = document.getElementById('payment-button');
    button.disabled = true;
    button.textContent = 'Traitement en cours...';

    // In a real implementation, you would:
    // 1. Initialize Stripe with the public key
    // 2. Create a payment method
    // 3. Confirm the payment
    // 4. Submit the form

    // For now, we'll show a demo message
    setTimeout(() => {
        alert('Démonstration: Dans un environnement réel, cliquez sur "Payer" créerait une transaction Stripe.\n\nAssurez-vous de configurer les clés Stripe valides dans votre fichier .env');
        button.disabled = false;
        button.textContent = '🔒 Payer {{ number_format($reservation->total_price, 2) }} €';
    }, 2000);
}
</script>

@endsection
