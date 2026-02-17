@extends('layouts.app')

@section('title', $scooter->name . ' - ScooterRent')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Images et galerie -->
        <div class="lg:col-span-2">
            <!-- Image principale -->
            <div style="width: 100%; height: 400px; border-radius: 12px; overflow: hidden; background: #f0f0f0; margin-bottom: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                <img id="mainImage" src="{{ $scooter->images->first()?->getUrl() ?? 'https://via.placeholder.com/800x400?text=No+Image' }}" alt="{{ $scooter->name }}" style="width: 100%; height: 100%; object-fit: contain; background: #f9f9f9; padding: 20px;">
            </div>

            <!-- Galerie de miniatures -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;">
                @forelse($scooter->images->take(4) as $image)
                    <img src="{{ $image->getUrl() }}" alt="{{ $image->alt_text }}" onclick="document.getElementById('mainImage').src=this.src" style="width: 100%; height: 100px; border-radius: 8px; cursor: pointer; border: 2px solid #e2e8f0; transition: all 0.3s;" onmouseover="this.style.borderColor='#1f7550'" onmouseout="this.style.borderColor='#e2e8f0'">
                @empty
                    @for($i = 1; $i <= 4; $i++)
                        <img src="https://via.placeholder.com/150x120?text=Image+{{ $i }}" style="width: 100%; height: 100px; border-radius: 8px; border: 2px solid #e2e8f0;">
                    @endfor
                @endforelse
            </div>

            <!-- Description détaillée -->
            <div style="margin-top: 40px;">
                <h2 style="color: #1f7550; font-size: 1.75rem; font-weight: 700; margin-bottom: 16px;">À propos de cette trottinette</h2>
                <p style="color: #4a5568; line-height: 1.8; font-size: 1.1rem; margin-bottom: 16px;">
                    {{ $scooter->description }}
                </p>
                <p style="color: #4a5568; line-height: 1.8; margin-bottom: 16px;">
                    <strong>{{ $scooter->name }}</strong> est l'une de nos trottinettes les plus populaires et fiables. Elle offre un excellent rapport qualité-prix avec une autonomie impressionnante et une vitesse de pointe de {{ $scooter->max_speed }} km/h. 
                </p>
                <p style="color: #4a5568; line-height: 1.8; margin-bottom: 16px;">
                    Parfaite pour les trajets urbains quotidiens, cette trottinette combine performance et sécurité. Elle est équipée d'un système de freinage moderne, de pneus anti-crevaison et d'un affichage numérique de la batterie.
                </p>
                <p style="color: #4a5568; line-height: 1.8;">
                    Tous nos utilisateurs apprécient son confort de conduite et sa maniabilité. Vous pouvez parcourir facilement la ville et atteindre vos destinations plus rapidement qu'en transports en commun.
                </p>
            </div>

            <!-- Avis utilisateurs -->
            <div style="margin-top: 40px; padding: 24px; background: #f8f9fa; border-radius: 12px;">
                <h3 style="color: #1f7550; font-size: 1.5rem; font-weight: 700; margin-bottom: 24px;">
                    ⭐ Avis des utilisateurs ({{ $scooter->reviews->count() }})
                </h3>
                
                @if($scooter->reviews->count() > 0)
                    <div style="display: grid; gap: 16px;">
                        @foreach($scooter->reviews->take(5) as $review)
                            <div style="background: white; padding: 16px; border-radius: 8px; border-left: 4px solid #1f7550;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                                    <div>
                                        <p style="font-weight: 700; color: #1f7550; margin-bottom: 4px;">{{ $review->user?->name ?? 'Utilisateur anonyme' }}</p>
                                        <p style="font-size: 0.85rem; color: #999;">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div style="text-align: right;">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)⭐@else☆@endif
                                        @endfor
                                    </div>
                                </div>
                                <p style="color: #4a5568; line-height: 1.6;">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color: #999; text-align: center; padding: 20px;">Aucun avis pour le moment. Soyez le premier à laisser un avis après votre location !</p>
                @endif
            </div>
        </div>

        <!-- Panneaude réservation (sidebar) -->
        <div class="sticky" style="top: 100px; height: fit-content;">
            <!-- Status et prix -->
            <div class="card" style="padding: 24px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    @if($scooter->isAvailable())
                        <span class="badge-status badge-available">✓ Disponible maintenant</span>
                    @else
                        <span class="badge-status badge-rented">⏳ Actuellement louée</span>
                    @endif
                </div>

                <!-- Tarification -->
                <div style="background: linear-gradient(135deg, #1f7550 0%, #155d3b 100%); color: white; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
                    <p style="opacity: 0.9; margin-bottom: 8px;">TARIFS</p>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <p style="opacity: 0.8; font-size: 0.9rem;">À l'heure</p>
                            <p style="font-size: 1.75rem; font-weight: 700;">{{ number_format($scooter->price_hour, 2) }}€</p>
                        </div>
                        <div>
                            <p style="opacity: 0.8; font-size: 0.9rem;">À la journée</p>
                            <p style="font-size: 1.75rem; font-weight: 700;">{{ number_format($scooter->price_day, 2) }}€</p>
                        </div>
                    </div>
                </div>

                <!-- Spécifications techniques -->
                <div style="background: #f8f9fa; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
                    <h4 style="color: #1f7550; font-weight: 700; margin-bottom: 12px;">📊 Spécifications</h4>
                    <ul style="list-style: none; padding: 0; color: #4a5568; font-size: 0.95rem;">
                        <li style="padding: 6px 0; border-bottom: 1px solid #e2e8f0;"><strong>Vitesse max :</strong> {{ $scooter->max_speed }} km/h</li>
                        <li style="padding: 6px 0; border-bottom: 1px solid #e2e8f0;"><strong>Batterie :</strong> {{ $scooter->battery_level }}% chargée</li>
                        <li style="padding: 6px 0; border-bottom: 1px solid #e2e8f0;"><strong>Code QR :</strong> {{ $scooter->qr_code }}</li>
                        <li style="padding: 6px 0;"><strong>Localisation :</strong> {{ $scooter->location }}</li>
                    </ul>
                </div>

                <!-- Bouton de réservation -->
                @auth
                    <a href="{{ route('reservations.create', $scooter->id) }}" class="btn-primary" style="width: 100%; padding: 16px; text-align: center; font-size: 1.1rem; display: block;">
                        🚀 Réserver maintenant
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-primary" style="width: 100%; padding: 16px; text-align: center; font-size: 1.1rem; display: block;">
                        ➡️ Se connecter pour réserver
                    </a>
                @endauth

                <p style="text-align: center; color: #999; font-size: 0.9rem; margin-top: 12px;">
                    💡 Accès immédiat après paiement
                </p>
            </div>

            <!-- Avis général -->
            @if($scooter->reviews->count() > 0)
                <div class="card" style="padding: 24px; text-align: center;">
                    <div class="rating" style="font-size: 1.75rem; margin-bottom: 8px;">
                        ⭐ {{ number_format($scooter->getAverageRating(), 1) }} / 5
                    </div>
                    <p style="color: #999; font-size: 0.9rem;">Basé sur {{ $scooter->reviews->count() }} avis utilisateurs</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Section similaire -->
    <div style="margin-top: 80px; padding-top: 40px; border-top: 2px solid #e2e8f0;">
        <h2 style="color: #1f7550; font-size: 1.75rem; font-weight: 700; margin-bottom: 30px;">Trottinettes similaires</h2>
        <p style="color: #4a5568; margin-bottom: 20px;">Découvrez d'autres modèles populaires</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                $similar = \App\Models\Scooter::where('id', '!=', $scooter->id)->take(3)->get();
            @endphp
            @forelse($similar as $s)
                <div class="card">
                    <img src="{{ $s->images->first()?->getUrl() ?? 'https://via.placeholder.com/300x200?text=No+Image' }}" alt="{{ $s->name }}" class="card-image">
                    <div style="padding: 16px;">
                        <h4 style="color: #1f7550; font-weight: 700; margin-bottom: 8px;">{{ $s->name }}</h4>
                        <p style="color: #4a5568; font-size: 0.9rem; margin-bottom: 12px;">{{ Str::limit($s->description, 50) }}</p>
                        <div style="display: flex; gap: 8px;">
                            <span style="color: #1f7550; font-weight: 700;">{{ number_format($s->price_hour, 2) }}€/h</span>
                            <a href="{{ route('scooters.show', $s->id) }}" class="btn-secondary" style="flex: 1; text-align: center; padding: 6px;">Voir →</a>
                        </div>
                    </div>
                </div>
            @empty
                <p style="color: #999;">Pas d'autres modèles disponibles</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
