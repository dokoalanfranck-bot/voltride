@extends('layouts.app')

@section('title', 'Parcourir - ScooterRent')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <!-- En-tête -->
    <div style="text-align: center; margin-bottom: 60px;">
        <h1 style="font-size: 2.5rem; font-weight: 800; color: #1f7550; margin-bottom: 20px;">Nos Trottinettes Disponibles</h1>
        <p style="color: #4a5568; font-size: 1.125rem; max-width: 600px; margin: 0 auto;">
            Découvrez notre large gamme de trottinettes électriques modernes et bien entretenues. Sélectionnez celle qui vous convient et commencez votre aventure urbaine en toute sécurité et confort.
        </p>
    </div>

    <!-- Filtres (optionnel) -->
    <div style="background: white; padding: 20px; border-radius: 12px; margin-bottom: 40px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        <p style="color: #1f7550; font-weight: 700; margin-bottom: 16px;">🔍 Filtrer et Trier</p>
        <form method="GET" action="{{ route('scooters.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; align-items: flex-end;">
            
            <!-- Recherche -->
            <div>
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px;">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom ou description..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
            </div>

            <!-- Prix/heure min -->
            <div>
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px;">Prix min (€/h)</label>
                <input type="number" name="price_hour_min" value="{{ request('price_hour_min') }}" placeholder="0" step="0.01" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
            </div>

            <!-- Prix/heure max -->
            <div>
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px;">Prix max (€/h)</label>
                <input type="number" name="price_hour_max" value="{{ request('price_hour_max') }}" placeholder="100" step="0.01" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
            </div>

            <!-- Vitesse min -->
            <div>
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px;">Vitesse min (km/h)</label>
                <input type="number" name="min_speed" value="{{ request('min_speed') }}" placeholder="20" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
            </div>

            <!-- Batterie min -->
            <div>
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px;">Batterie min (%)</label>
                <input type="number" name="min_battery" value="{{ request('min_battery') }}" placeholder="50" min="0" max="100" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
            </div>

            <!-- Tri -->
            <div>
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px;">Trier par</label>
                <select name="sort" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nom (A-Z)</option>
                    <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Prix: moins cher</option>
                    <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Prix: plus cher</option>
                    <option value="speed" {{ request('sort') === 'speed' ? 'selected' : '' }}>Vitesse (rapide)</option>
                    <option value="battery" {{ request('sort') === 'battery' ? 'selected' : '' }}>Batterie (complète)</option>
                </select>
            </div>

            <!-- Boutons -->
            <div style="display: flex; gap: 8px;">
                <button type="submit" style="background: #1f7550; color: white; padding: 10px 16px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; flex: 1;">
                    🔍 Filtrer
                </button>
                <a href="{{ route('scooters.index') }}" style="background: #6c757d; color: white; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center;">
                    ↺ Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Grille de scooters -->
    @if($scooters->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($scooters as $scooter)
            <div class="card">
                <!-- Image -->
                <div style="position: relative; width: 100%; height: 200px; background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%); overflow: hidden;">
                    <img src="{{ $scooter->images->first()?->getUrl() ?? 'https://via.placeholder.com/400x250?text=No+Image' }}" alt="{{ $scooter->name }}" class="card-image" style="width: 100%; height: 100%; object-fit: contain; padding: 10px; background: #f9f9f9;">
                    <div style="position: absolute; top: 10px; right: 10px;">
                        @if($scooter->isAvailable())
                            <span class="badge-status badge-available">✓ Disponible</span>
                        @else
                            <span class="badge-status badge-rented">⏳ Louée</span>
                        @endif
                    </div>
                </div>
                
                <!-- Contenu -->
                <div style="padding: 20px;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #1f7550; margin-bottom: 8px;">{{ $scooter->name }}</h3>
                    
                    <p style="color: #4a5568; font-size: 0.95rem; margin-bottom: 16px; line-height: 1.5;">
                        {{ $scooter->description }}
                    </p>

                    <!-- Spécifications -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px; font-size: 0.9rem; background: #f8f9fa; padding: 12px; border-radius: 8px;">
                        <div>
                            <p style="color: #999;">⚡ Vitesse max</p>
                            <p style="font-weight: 700; color: #1f7550; font-size: 1.1rem;">{{ $scooter->max_speed }} km/h</p>
                        </div>
                        <div>
                            <p style="color: #999;">🔋 Batterie</p>
                            <p style="font-weight: 700; color: #1f7550; font-size: 1.1rem;">{{ $scooter->battery_level }}%</p>
                        </div>
                    </div>

                    <!-- Tarifs -->
                    <div style="display: flex; gap: 8px; margin-bottom: 16px; background: linear-gradient(135deg, rgba(31,117,80,0.05) 0%, rgba(45,155,111,0.05) 100%); padding: 12px; border-radius: 8px; border-left: 4px solid #1f7550;">
                        <div style="flex: 1;">
                            <p style="color: #999; font-size: 0.85rem;">À l'heure</p>
                            <p style="font-size: 1.3rem; font-weight: 700; color: #1f7550;">{{ number_format($scooter->price_hour, 2) }}€</p>
                        </div>
                        <div style="flex: 1;">
                            <p style="color: #999; font-size: 0.85rem;">À la journée (24h)</p>
                            <p style="font-size: 1.3rem; font-weight: 700; color: #1f7550;">{{ number_format($scooter->price_day, 2) }}€</p>
                        </div>
                    </div>

                    <!-- Évaluation -->
                    @if($scooter->reviews->count() > 0)
                        <div style="margin-bottom: 16px; padding: 8px; background: #fffbf0; border-radius: 8px;">
                            <div class="rating" style="font-size: 1.1rem; margin: 0; text-align: center;">
                                ⭐ {{ number_format($scooter->getAverageRating(), 1) }} / 5 
                                <span style="color: #999; font-size: 0.9rem;">({{ $scooter->reviews->count() }} avis)</span>
                            </div>
                        </div>
                    @else
                        <div style="margin-bottom: 16px; padding: 8px; background: #f0f0f0; border-radius: 8px; text-align: center; color: #999; font-size: 0.9rem;">
                            Pas d'avis encore - Soyez le premier !
                        </div>
                    @endif

                    <!-- Boutons d'action -->
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('scooters.show', $scooter->id) }}" style="flex: 1;" class="btn-secondary" style="width: 100%; text-align: center;">
                            📋 Détails
                        </a>
                        @auth
                            <a href="{{ route('reservations.create', $scooter->id) }}" style="flex: 1;" class="btn-primary" style="width: 100%; text-align: center;">
                                🚀 Réserver
                            </a>
                        @else
                            <a href="{{ route('login') }}" style="flex: 1;" class="btn-primary" style="width: 100%; text-align: center;">
                                🚀 Réserver
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    @else
        <div style="background: white; padding: 60px 20px; border-radius: 12px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 40px;">
            <div style="font-size: 3rem; margin-bottom: 20px;">🔍</div>
            <h3 style="font-size: 1.5rem; font-weight: 700; color: #1f7550; margin-bottom: 12px;">Aucune trottinette ne correspond</h3>
            <p style="color: #4a5568; margin-bottom: 20px;">Nous n'avons trouvé aucune trottinette correspondant à vos critères de filtrage.</p>
            <p style="color: #999; margin-bottom: 24px;">Essayez d'ajuster vos filtres ou explorer toutes nos trottinettes disponibles.</p>
            <a href="{{ route('scooters.index') }}" style="background: #1f7550; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block;">
                Voir tous les modèles
            </a>
        </div>
    @endif

    <!-- Pagination -->
    @if($scooters->hasPages())
        <div style="margin-top: 40px; display: flex; justify-content: center;">
            {{ $scooters->links() }}
        </div>
    @endif

    <!-- Section informative -->
    <div style="margin-top: 80px; padding: 40px; background: linear-gradient(135deg, #f0fdf7 0%, #f0f9ff 100%); border-radius: 12px; border-left: 4px solid #1f7550;">
        <h2 style="color: #1f7550; font-size: 1.75rem; font-weight: 700; margin-bottom: 20px;">💡 Pourquoi louer avec ScooterRent ?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 style="color: #1f7550; font-weight: 700; margin-bottom: 8px;">🛴 Trottinettes Modernes</h3>
                <p style="color: #4a5568; line-height: 1.6;">Nos trottinettes sont régulièrement entretenues et mises à jour. Chaque modèle est testé avant chaque location pour garantir votre sécurité.</p>
            </div>
            <div>
                <h3 style="color: #1f7550; font-weight: 700; margin-bottom: 8px;">💚 Écologique</h3>
                <p style="color: #4a5568; line-height: 1.6;">Zéro émission de CO₂. Participez à la mobilité durable et contribuez à un avenir urbain plus vert et plus respirable.</p>
            </div>
            <div>
                <h3 style="color: #1f7550; font-weight: 700; margin-bottom: 8px;">🔒 Sécurité Garantie</h3>
                <p style="color: #4a5568; line-height: 1.6;">Assurance incluse dans chaque location. Système de sécurité avancé et support client disponible 24/7.</p>
            </div>
        </div>
    </div>
</div>
@endsection
