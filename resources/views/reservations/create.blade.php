@extends('layouts.app')

@section('title', 'Nouvelle réservation - voltride')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    <!-- En-tête -->
    <div style="margin-bottom: 40px;">
        <h1 style="color: #1f7550; font-size: 2rem; font-weight: 700; margin-bottom: 8px;">
            🚀 Créer une réservation
        </h1>
        <p style="color: #4a5568; font-size: 1.1rem;">
            Réserver la trottinette <strong>{{ $scooter->name }}</strong> en quelques étapes simples
        </p>
    </div>

    <!-- Card du scooter résumé -->
    <div class="card" style="padding: 20px; margin-bottom: 30px; display: grid; grid-template-columns: auto 1fr; gap: 16px; align-items: center; border-left: 4px solid #1f7550;">
        <img src="{{ $scooter->images->first()?->getUrl() ?? 'https://via.placeholder.com/100x100?text=No+Image' }}" alt="{{ $scooter->name }}" style="width: 100px; height: 100px; border-radius: 8px; object-fit: contain; background: #f9f9f9; padding: 5px;">
        <div>
            <h3 style="color: #1f7550; font-weight: 700; font-size: 1.25rem; margin-bottom: 4px;">{{ $scooter->name }}</h3>
            <p style="color: #4a5568; margin-bottom: 8px;">{{ Str::limit($scooter->description, 100) }}</p>
            <p style="color: #2d9b6f; font-weight: 700;">
                {{ number_format($scooter->price_hour, 2) }}€/h • 
                {{ number_format($scooter->price_day, 2) }}€/jour • 
                Batterie {{ $scooter->battery_level }}%
            </p>
        </div>
    </div>

    <!-- Formulaire de réservation -->
    <form action="{{ route('reservations.store') }}" method="POST" class="card" style="padding: 32px;">
        @csrf
        
        <input type="hidden" name="scooter_id" value="{{ $scooter->id }}">

        <!-- Section: Type de location -->
        <div style="margin-bottom: 32px;">
            <h3 style="color: #1f7550; font-weight: 700; font-size: 1.25rem; margin-bottom: 16px;">📅 Durée de location</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
                <label style="border: 2px solid #e2e8f0; padding: 16px; border-radius: 8px; cursor: pointer; transition: all 0.3s;">
                    <input type="radio" name="rental_type" value="hourly" selected style="margin-right: 8px;">
                    <strong style="color: #1f7550;">⏱️ À l'heure</strong>
                    <p style="color: #999; font-size: 0.9rem; margin-top: 4px;">Parfait pour les trajets courts</p>
                </label>
                <label style="border: 2px solid #e2e8f0; padding: 16px; border-radius: 8px; cursor: pointer; transition: all 0.3s;">
                    <input type="radio" name="rental_type" value="daily" style="margin-right: 8px;">
                    <strong style="color: #1f7550;">📆 À la journée</strong>
                    <p style="color: #999; font-size: 0.9rem; margin-top: 4px;">Économisez jusqu'à 50%</p>
                </label>
            </div>

            @error('rental_type')
                <p style="color: #e53e3e; font-size: 0.9rem;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Section: Dates/Heures -->
        <div style="margin-bottom: 32px;">
            <h3 style="color: #1f7550; font-weight: 700; font-size: 1.25rem; margin-bottom: 16px;">🕐 Horaires</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div>
                    <label for="start_date" style="display: block; color: #1f7550; font-weight: 600; margin-bottom: 8px;">
                        Date de début
                    </label>
                    <input type="date" id="start_date" name="start_date" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;" min="{{ date('Y-m-d') }}" value="{{ old('start_date') }}">
                    @error('start_date')
                        <p style="color: #e53e3e; font-size: 0.9rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="start_time" style="display: block; color: #1f7550; font-weight: 600; margin-bottom: 8px;">
                        Heure de début
                    </label>
                    <input type="time" id="start_time" name="start_time" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;" value="{{ old('start_time', '09:00') }}">
                    @error('start_time')
                        <p style="color: #e53e3e; font-size: 0.9rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <label for="end_date" style="display: block; color: #1f7550; font-weight: 600; margin-bottom: 8px;">
                        Date de fin
                    </label>
                    <input type="date" id="end_date" name="end_date" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;" min="{{ date('Y-m-d') }}" value="{{ old('end_date') }}">
                    @error('end_date')
                        <p style="color: #e53e3e; font-size: 0.9rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_time" style="display: block; color: #1f7550; font-weight: 600; margin-bottom: 8px;">
                        Heure de fin
                    </label>
                    <input type="time" id="end_time" name="end_time" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;" value="{{ old('end_time', '17:00') }}">
                    @error('end_time')
                        <p style="color: #e53e3e; font-size: 0.9rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section: Promo Code optionnel -->
        <div style="margin-bottom: 32px;">
            <h3 style="color: #1f7550; font-weight: 700; font-size: 1.25rem; margin-bottom: 16px;">🎁 Code promo (optionnel)</h3>
            
            <div style="position: relative;">
                <input type="text" name="promo_code" placeholder="Entrez votre code promo si vous en avez un" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;" value="{{ old('promo_code') }}">
                @error('promo_code')
                    <p style="color: #e53e3e; font-size: 0.9rem; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>
            <p style="color: #999; font-size: 0.9rem; margin-top: 8px;">
                💡 Besoin d'un code ? Parrainez des amis et gagnez des réductions !
            </p>
        </div>

        <!-- Section: Informations supplémentaires -->
        <div style="margin-bottom: 32px;">
            <h3 style="color: #1f7550; font-weight: 700; font-size: 1.25rem; margin-bottom: 16px;">📋 Informations supplémentaires</h3>
            
            <div style="margin-bottom: 16px;">
                <label for="notes" style="display: block; color: #1f7550; font-weight: 600; margin-bottom: 8px;">
                    Notes ou demandes particulières
                </label>
                <textarea id="notes" name="notes" rows="4" placeholder="Avez-vous des demandes particulières ? Écrivez-les ici..." style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-family: inherit; resize: vertical;">{{ old('notes') }}</textarea>
            </div>
            @error('notes')
                <p style="color: #e53e3e; font-size: 0.9rem;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Section: Conditions d'utilisation -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 32px;">
            <h4 style="color: #1f7550; font-weight: 700; margin-bottom: 12px;">✓ Conditions d'utilisation</h4>
            <ul style="list-style: none; padding: 0; color: #4a5568; font-size: 0.95rem;">
                <li style="padding: 6px 0; display: flex; gap: 8px;">
                    <span>🔒</span>
                    <span>Les données de la trottinette seront transmises à votre téléphone</span>
                </li>
                <li style="padding: 6px 0; display: flex; gap: 8px;">
                    <span>⚖️</span>
                    <span>Vous êtes responsable de la trottinette pendant la location</span>
                </li>
                <li style="padding: 6px 0; display: flex; gap: 8px;">
                    <span>💳</span>
                    <span>Une caution de 50€ sera demandée lors du retrait</span>
                </li>
                <li style="padding: 6px 0; display: flex; gap: 8px;">
                    <span>🛡️</span>
                    <span>Usage personnel uniquement - Assurance incluse</span>
                </li>
            </ul>
        </div>

        <!-- Section: Résumé du prix -->
        <div style="background: linear-gradient(135deg, #1f7550 0%, #155d3b 100%); color: white; padding: 24px; border-radius: 12px; margin-bottom: 32px;">
            <div style="display: grid; grid-template-columns: 1fr auto; gap: 16px; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.2);">
                <span>Coût de location :</span>
                <span id="rental-cost" style="font-weight: 700;">À calculer...</span>
            </div>
            <div style="display: grid; grid-template-columns: 1fr auto; gap: 16px; margin-bottom: 16px;">
                <span id="discount-label" style="display: none;">Réduction :</span>
                <span id="discount-amount" style="display: none; font-weight: 700; color: #4ade80;"></span>
            </div>
            <div style="display: grid; grid-template-columns: 1fr auto; gap: 16px; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.2);">
                <span style="font-size: 1.25rem; font-weight: 700;">TOTAL TTC :</span>
                <span id="total-price" style="font-size: 1.5rem; font-weight: 700;">À calculer...</span>
            </div>
        </div>

        <!-- Bouton soumettre -->
        <button type="submit" class="btn-primary" style="width: 100%; padding: 16px; font-size: 1.1rem; cursor: pointer;">
            ✓ Confirmer la réservation
        </button>

        <p style="text-align: center; color: #999; font-size: 0.9rem; margin-top: 16px;">
            Vous serez redirigé vers le paiement après confirmation
        </p>
    </form>

    <!-- Informations utiles -->
    <div style="margin-top: 40px; padding: 24px; background: #f0fdf4; border-left: 4px solid #1f7550; border-radius: 8px;">
        <h4 style="color: #1f7550; font-weight: 700; margin-bottom: 12px;">💡 Conseils avant de louer</h4>
        <ul style="list-style-position: inside; color: #4a5568; line-height: 1.8;">
            <li>Vérifiez que la batterie est suffisamment chargée pour votre trajet</li>
            <li>Portez un casque de sécurité (fourni sur demande)</li>
            <li>Lisez les conditions d'utilisation avant de retirer la trottinette</li>
            <li>Prenez des photos de l'état de la trottinette avant de partir</li>
            <li>Rapportez la trottinette dans le meilleur état possible</li>
        </ul>
    </div>
</div>

<script>
    // Calcul automatique du prix
    function calculatePrice() {
        const startDate = document.getElementById('start_date').value;
        const startTime = document.getElementById('start_time').value;
        const endDate = document.getElementById('end_date').value;
        const endTime = document.getElementById('end_time').value;
        const rentalType = document.querySelector('input[name="rental_type"]:checked').value;

        if (!startDate || !startTime || !endDate || !endTime) return;

        const start = new Date(startDate + ' ' + startTime);
        const end = new Date(endDate + ' ' + endTime);
        const diffMs = end - start;
        const diffHours = diffMs / (1000 * 60 * 60);

        let totalCost = 0;
        if (rentalType === 'hourly') {
            totalCost = diffHours * {{ $scooter->price_hour }};
        } else {
            const days = Math.ceil(diffHours / 24);
            totalCost = days * {{ $scooter->price_day }};
        }

        document.getElementById('rental-cost').textContent = totalCost.toFixed(2) + '€';
        document.getElementById('total-price').textContent = totalCost.toFixed(2) + '€';
    }

    document.getElementById('start_date').addEventListener('change', calculatePrice);
    document.getElementById('start_time').addEventListener('change', calculatePrice);
    document.getElementById('end_date').addEventListener('change', calculatePrice);
    document.getElementById('end_time').addEventListener('change', calculatePrice);
    document.querySelectorAll('input[name="rental_type"]').forEach(r => r.addEventListener('change', calculatePrice));
</script>
@endsection
