@extends('layouts.app')

@section('title', $scooter->name . ' - VoltRide')

@section('content')
@include('components.responsive-styles')

<style>
    @media (max-width: 1024px) {
        .show-grid {
            grid-template-columns: 1fr !important;
        }
        .sidebar {
            position: static !important;
            top: auto !important;
        }
    }
    
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.12);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
        animation: fadeIn 0.6s ease-out forwards;
    }

    .thumbnail {
        transition: all 0.3s ease;
    }

    .thumbnail:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(31, 117, 80, 0.3);
    }

    .reserve-btn {
        transition: all 0.3s ease;
    }

    .reserve-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(71, 245, 91, 0.4);
    }
</style>

<div style="max-width: 1200px; margin: 0 auto; padding: 1rem 1.5rem; overflow-x: hidden;">
    <div class="show-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;">
        <!-- Left: Images & Content -->
        <div>
            <!-- Main Image -->
            <div class="fade-in" style="width: 100%; height: clamp(250px, 60vw, 400px); border-radius: 12px; overflow: hidden; background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%); margin-bottom: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                <img id="mainImage" src="{{ $scooter->images->first()?->getUrl() ?? 'https://via.placeholder.com/800x400?text=No+Image' }}" alt="{{ $scooter->name }}" loading="lazy" style="width: 100%; height: 100%; object-fit: contain; background: #f9f9f9; padding: 20px; transition: opacity 0.3s;">
            </div>

            <!-- Thumbnail Gallery -->
            <div class="fade-in" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr)); gap: 8px; margin-bottom: 40px; animation-delay: 0.1s;">
                @forelse($scooter->images->take(6) as $image)
                    <img src="{{ $image->getUrl() }}" alt="{{ $image->alt_text }}" onclick="document.getElementById('mainImage').src=this.src" loading="lazy" class="thumbnail" style="width: 100%; aspect-ratio: 1; border-radius: 8px; cursor: pointer; border: 2px solid #e2e8f0; object-fit: contain; padding: 4px; background: #f9f9f9;" onmouseover="this.style.borderColor='#07d65d'" onmouseout="this.style.borderColor='#e2e8f0'">
                @empty
                    @for($i = 1; $i <= 4; $i++)
                        <img src="https://via.placeholder.com/150x120?text=Image+{{ $i }}" style="width: 100%; aspect-ratio: 1; border-radius: 8px; border: 2px solid #e2e8f0; object-fit: contain;">
                    @endfor
                @endforelse
            </div>

            <!-- Specs Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px; margin-bottom: 40px;">
                <div class="card" style="padding: 20px; text-align: center;">
                    <p style="color: #999; font-size: 0.9rem; margin: 0 0 8px 0;">⚡ Vitesse max</p>
                    <p style="font-size: clamp(1.3rem, 4vw, 1.8rem); font-weight: 700; color: #1f7550; margin: 0;">{{ $scooter->max_speed }}</p>
                    <p style="color: #999; font-size: 0.85rem; margin: 4px 0 0 0;">km/h</p>
                </div>
                <div class="card" style="padding: 20px; text-align: center;">
                    <p style="color: #999; font-size: 0.9rem; margin: 0 0 8px 0;">🔋 Batterie</p>
                    <p style="font-size: clamp(1.3rem, 4vw, 1.8rem); font-weight: 700; color: #1f7550; margin: 0;">{{ $scooter->battery_level }}</p>
                    <p style="color: #999; font-size: 0.85rem; margin: 4px 0 0 0;">%</p>
                </div>
                <div class="card" style="padding: 20px; text-align: center;">
                    <p style="color: #999; font-size: 0.9rem; margin: 0 0 8px 0;">📏 Distance</p>
                    <p style="font-size: clamp(1.3rem, 4vw, 1.8rem); font-weight: 700; color: #1f7550; margin: 0;">{{ $scooter->range_km ?? 'N/A' }}</p>
                    <p style="color: #999; font-size: 0.85rem; margin: 4px 0 0 0;">km</p>
                </div>
            </div>

            <!-- Description -->
            <div style="margin-bottom: 40px;">
                <h2 style="color: #0a9b3a; font-size: clamp(1.5rem, 5vw, 1.75rem); font-weight: 700; margin-bottom: 16px;">À propos</h2>
                <p style="color: #4a5568; line-height: 1.8; font-size: clamp(0.95rem, 2vw, 1.1rem); margin-bottom: 16px;">
                    {{ $scooter->description }}
                </p>
                <p style="color: #4a5568; line-height: 1.8; font-size: clamp(0.95rem, 2vw, 1rem);">
                    <strong>{{ $scooter->name }}</strong> offre une combinaison optimale de performance et de confort. Elle est équipée d'un système de freinage moderne, de pneus anti-crevaison et d'un affichage numérique. Parfaite pour les trajets urbains quotidiens.
                </p>
            </div>

            <!-- Reviews -->
            <div style="margin-bottom: 40px; padding: clamp(16px, 4vw, 24px); background: #f8f9fa; border-radius: 12px;">
                <h3 style="color: #0a9b3a; font-size: clamp(1.2rem, 4vw, 1.5rem); font-weight: 700; margin-bottom: 24px;">⭐ Avis ({{ $scooter->reviews->count() }})</h3>
                
                @if($scooter->reviews->count() > 0)
                    @php $avgRating = $scooter->getAverageRating(); @endphp
                    <div style="margin-bottom: 20px; padding: 16px; background: white; border-radius: 8px; text-align: center;">
                        <p style="font-size: clamp(1.5rem, 4vw, 2rem); font-weight: 700; color: #1f7550; margin: 0;">{{ number_format($avgRating, 1) }} / 5</p>
                        <p style="color: #999; font-size: clamp(0.9rem, 2vw, 1rem); margin: 8px 0 0 0;">Basé sur {{ $scooter->reviews->count() }} avis</p>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach($scooter->reviews->take(5) as $review)
                            <div style="background: white; padding: 16px; border-radius: 8px; border-left: 4px solid #1f7550;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                                    <div>
                                        <p style="font-weight: 700; color: #1f7550; margin: 0 0 4px 0; font-size: clamp(0.95rem, 2vw, 1rem);">{{ $review->user?->name ?? 'Anonyme' }}</p>
                                        <p style="font-size: 0.85rem; color: #999; margin: 0;">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div style="text-align: right;">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)⭐@else☆@endif
                                        @endfor
                                    </div>
                                </div>
                                <p style="color: #4a5568; line-height: 1.6; margin: 0; font-size: clamp(0.9rem, 2vw, 1rem);">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color: #999; text-align: center; padding: 20px; margin: 0;">Aucun avis pour le moment. Soyez le premier après votre location!</p>
                @endif
            </div>
        </div>

        <!-- Right: Sidebar -->
        <div class="sidebar" style="position: sticky; top: 100px; height: fit-content;">
            <!-- Status -->
            <div class="card" style="padding: clamp(16px, 4vw, 24px); margin-bottom: 20px;">
                <div style="margin-bottom: 20px;">
                    @if($scooter->isAvailable())
                        <span style="background: #10b981; color: white; padding: 8px 14px; border-radius: 20px; font-weight: 600; font-size: clamp(0.85rem, 2vw, 0.95rem);">✓ Disponible</span>
                    @else
                        <span style="background: #f59e0b; color: white; padding: 8px 14px; border-radius: 20px; font-weight: 600; font-size: clamp(0.85rem, 2vw, 0.95rem);">⏳ Louée</span>
                    @endif
                </div>

                <!-- Pricing -->
                <div style="background: linear-gradient(135deg, #1f7550 0%, #155d3b 100%); color: white; padding: clamp(16px, 4vw, 20px); border-radius: 12px; margin-bottom: 20px;">
                    <p style="opacity: 0.9; margin-bottom: 12px; font-size: clamp(0.85rem, 2vw, 0.95rem);">TARIFS</p>
                    <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                        <div style="flex: 1;">
                            <p style="opacity: 0.8; font-size: clamp(0.8rem, 2vw, 0.85rem); margin: 0 0 4px 0;">À l'heure</p>
                            <p style="font-size: clamp(1.3rem, 3vw, 1.75rem); font-weight: 700; margin: 0;">{{ number_format($scooter->price_hour, 2) }}€</p>
                        </div>
                        <div style="flex: 1;">
                            <p style="opacity: 0.8; font-size: clamp(0.8rem, 2vw, 0.85rem); margin: 0 0 4px 0;">À la minute</p>
                            <p style="font-size: clamp(1.3rem, 3vw, 1.75rem); font-weight: 700; margin: 0;">{{ number_format($scooter->price_minute, 2) }}€</p>
                        </div>
                    </div>
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.2); font-size: clamp(0.75rem, 2vw, 0.9rem); opacity: 0.9;">
                        ℹ️ Seuls les touristes peuvent louer pour 2 heures
                    </div>
                </div>

                <!-- Reserve Button -->
                <a href="{{ route('reservations.create', $scooter->id) }}" class="reserve-btn" style="display: block; width: 100%; padding: clamp(12px, 3vw, 16px); background: linear-gradient(135deg, #47F55B 0%, #07d65d 100%); color: #0f172a; text-align: center; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: clamp(1rem, 2vw, 1.1rem); cursor: pointer; border: none; box-sizing: border-box; box-shadow: 0 4px 12px rgba(71, 245, 91, 0.3);">
                    🚀 Réserver maintenant
                </a>
            </div>

            <!-- Info Card -->
            <div class="card" style="padding: clamp(16px, 4vw, 20px);">
                <p style="color: #0a9b3a; font-weight: 700; margin-bottom: 16px; font-size: clamp(1rem, 2vw, 1.1rem);">✅ Ce qui est inclus</p>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 12px; color: #4a5568; font-size: clamp(0.9rem, 2vw, 1rem);">✓ Assurance incluse</li>
                    <li style="margin-bottom: 12px; color: #4a5568; font-size: clamp(0.9rem, 2vw, 1rem);">✓ dépot d'une pièce d'identité</li>
                    <li style="margin-bottom: 12px; color: #4a5568; font-size: clamp(0.9rem, 2vw, 1rem);">✓ Paiement sur place</li>
                    <li style="color: #4a5568; font-size: clamp(0.9rem, 2vw, 1rem);">✓ Casque fourni</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
