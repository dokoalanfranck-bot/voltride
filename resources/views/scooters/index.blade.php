@extends('layouts.app')

@section('title', 'Parcourir - VoltRide')

@section('content')
@include('components.responsive-styles')

<style>
    @media (max-width: 767px) {
        .filter-grid {
            grid-template-columns: 1fr !important;
        }
        .scooter-grid {
            grid-template-columns: 1fr !important;
        }
    }
    
    @media (min-width: 768px) and (max-width: 1024px) {
        .scooter-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
    
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        opacity: 0;
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.12);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card:nth-child(1) { animation-delay: 0.05s; }
    .card:nth-child(2) { animation-delay: 0.1s; }
    .card:nth-child(3) { animation-delay: 0.15s; }
    .card:nth-child(4) { animation-delay: 0.2s; }
    .card:nth-child(5) { animation-delay: 0.25s; }
    .card:nth-child(6) { animation-delay: 0.3s; }

    .badge-available {
        background: #10b981;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .badge-rented {
        background: #f59e0b;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    }
</style>

<div style="max-width: 1200px; margin: 0 auto; padding: 1rem 1.5rem; overflow-x: hidden;">
    <!-- Header -->
    <div style="text-align: center; margin-bottom: clamp(40px, 8vw, 60px);">
        <h1 style="font-size: clamp(1.8rem, 6vw, 2.5rem); font-weight: 800; color: #0a9b3a; margin-bottom: 20px;">Nos Trottinettes</h1>
        <p style="color: #4a5568; font-size: clamp(1rem, 2vw, 1.125rem); max-width: 600px; margin: 0 auto; line-height: 1.6;">
            Découvrez notre large gamme de trottinettes électriques modernes et bien entretenues. Légères, performantes, et écologiques!
        </p>
    </div>

    <!-- Filters -->
    <div style="background: white; padding: clamp(16px, 4vw, 24px); border-radius: 12px; margin-bottom: 40px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        <p style="color: #0a9b3a; font-weight: 700; margin-bottom: 16px; font-size: clamp(1rem, 2vw, 1.1rem);">🔍 Filtrer et Trier</p>
        <form method="GET" action="{{ route('scooters.index') }}" class="filter-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; align-items: flex-end;">
            
            <div>
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: clamp(0.9rem, 2vw, 1rem);">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: clamp(0.9rem, 2vw, 1rem);">Prix min (€/h)</label>
                <input type="number" name="price_hour_min" value="{{ request('price_hour_min') }}" placeholder="0" step="0.01" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: clamp(0.9rem, 2vw, 1rem);">Prix max (€/h)</label>
                <input type="number" name="price_hour_max" value="{{ request('price_hour_max') }}" placeholder="100" step="0.01" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: clamp(0.9rem, 2vw, 1rem);">Vitesse min (km/h)</label>
                <input type="number" name="min_speed" value="{{ request('min_speed') }}" placeholder="20" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: clamp(0.9rem, 2vw, 1rem);">Batterie min (%)</label>
                <input type="number" name="min_battery" value="{{ request('min_battery') }}" placeholder="50" min="0" max="100" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 6px; font-size: clamp(0.9rem, 2vw, 1rem);">Trier par</label>
                <select name="sort" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem; box-sizing: border-box;">
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nom (A-Z)</option>
                    <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Prix: moins cher</option>
                    <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Prix: plus cher</option>
                    <option value="speed" {{ request('sort') === 'speed' ? 'selected' : '' }}>Vitesse (rapide)</option>
                    <option value="battery" {{ request('sort') === 'battery' ? 'selected' : '' }}>Batterie (complète)</option>
                </select>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" style="background: linear-gradient(135deg, #47F55B 0%, #07d65d 100%); color: #0f172a; padding: 10px 16px; border-radius: 8px; border: none; cursor: pointer; font-weight: 700; flex: 1; font-size: clamp(0.9rem, 2vw, 1rem);">
                    🔍 Filtrer
                </button>
                <a href="{{ route('scooters.index') }}" style="background: #0a9b3a; color: white; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-weight: 700; text-align: center; font-size: clamp(0.9rem, 2vw, 1rem);">
                    ↺ Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Scooters Grid -->
    @if($scooters->count() > 0)
        <div class="scooter-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 24px;">
            @foreach($scooters as $scooter)
            <div class="card">
                <!-- Image -->
                <div style="position: relative; width: 100%; height: clamp(150px, 40vw, 200px); background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);">
                    <img src="{{ $scooter->images->first()?->getUrl() ?? 'https://via.placeholder.com/400x250?text=No+Image' }}" alt="{{ $scooter->name }}" loading="lazy" style="width: 100%; height: 100%; object-fit: contain; padding: 10px; background: #f9f9f9;">
                    <div style="position: absolute; top: 10px; right: 10px;">
                        @if($scooter->isAvailable())
                            <span class="badge-available">✓ Disponible</span>
                        @else
                            <span class="badge-rented">⏳ Louée</span>
                        @endif
                    </div>
                </div>
                
                <!-- Content -->
                <div style="padding: clamp(16px, 4vw, 20px);">
                    <h3 style="font-size: clamp(1.1rem, 3vw, 1.25rem); font-weight: 700; color: #0a9b3a; margin: 0 0 8px 0;">{{ $scooter->name }}</h3>
                    
                    <p style="color: #4a5568; font-size: clamp(0.85rem, 2vw, 0.95rem); margin-bottom: 16px; line-height: 1.5;">
                        {{ Str::limit($scooter->description, 80) }}
                    </p>

                    <!-- Specs -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px; font-size: clamp(0.8rem, 2vw, 0.9rem); background: #f8f9fa; padding: 12px; border-radius: 8px;">
                        <div>
                            <p style="color: #999; margin: 0 0 4px 0;">⚡ Vitesse max</p>
                            <p style="font-weight: 700; color: #1f7550; margin: 0; font-size: clamp(1rem, 3vw, 1.1rem);">{{ $scooter->max_speed }} km/h</p>
                        </div>
                        <div>
                            <p style="color: #999; margin: 0 0 4px 0;">🔋 Batterie</p>
                            <p style="font-weight: 700; color: #1f7550; margin: 0; font-size: clamp(1rem, 3vw, 1.1rem);">{{ $scooter->battery_level }}%</p>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div style="display: flex; gap: 8px; margin-bottom: 16px; background: linear-gradient(135deg, rgba(31,117,80,0.05) 0%, rgba(45,155,111,0.05) 100%); padding: 12px; border-radius: 8px; border-left: 4px solid #1f7550;">
                        <div style="flex: 1;">
                            <p style="color: #999; font-size: clamp(0.75rem, 2vw, 0.85rem); margin: 0 0 4px 0;">À l'heure</p>
                            <p style="font-size: clamp(1.1rem, 3vw, 1.3rem); font-weight: 700; color: #1f7550; margin: 0;">{{ number_format($scooter->price_hour, 2) }}€</p>
                        </div>
                        <div style="flex: 1;">
                            <p style="color: #999; font-size: clamp(0.75rem, 2vw, 0.85rem); margin: 0 0 4px 0;">À la minute</p>
                            <p style="font-size: clamp(1.1rem, 3vw, 1.3rem); font-weight: 700; color: #1f7550; margin: 0;">{{ number_format($scooter->price_minute, 2) }}€</p>
                        </div>
                    </div>

                    <!-- Rating -->
                    @if($scooter->reviews->count() > 0)
                        <div style="margin-bottom: 16px; padding: 8px; background: #fffbf0; border-radius: 8px; text-align: center; font-size: clamp(0.85rem, 2vw, 0.95rem);">
                            ⭐ {{ number_format($scooter->getAverageRating(), 1) }} / 5 
                            <span style="color: #999;">({{ $scooter->reviews->count() }})</span>
                        </div>
                    @else
                        <div style="margin-bottom: 16px; padding: 8px; background: #f0f0f0; border-radius: 8px; text-align: center; color: #999; font-size: clamp(0.8rem, 2vw, 0.9rem);">
                            Pas d'avis encore
                        </div>
                    @endif

                    <!-- Buttons -->
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('scooters.show', $scooter->id) }}" style="flex: 1; text-align: center; padding: 12px; background: #f0f0f0; color: #1f7550; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s; font-size: clamp(0.9rem, 2vw, 1rem);">
                            📋 Détails
                        </a>
                        <a href="{{ route('reservations.create', $scooter->id) }}" style="flex: 1; text-align: center; padding: 12px; background: #1f7550; color: white; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s; font-size: clamp(0.9rem, 2vw, 1rem);">
                            🚀 Réserver
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: clamp(40px, 8vw, 60px); background: #f9f9f9; border-radius: 12px;">
            <p style="color: #999; font-size: clamp(1rem, 2vw, 1.1rem); margin: 0;">Aucune trottinette ne correspond à vos critères. Essayez de réinitialiser les filtres.</p>
        </div>
    @endif
</div>
@endsection
