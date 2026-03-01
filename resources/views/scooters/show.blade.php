@extends('layouts.app')

@section('title', $scooter->name . ' - VoltRide')

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 60px;">
    <!-- Breadcrumb -->
    <div style="margin-bottom: 24px;">
        <a href="{{ route('scooters.index') }}" style="color: var(--gray); text-decoration: none; font-size: 0.9rem;">
            ← Retour aux trottinettes
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 48px;">
        <!-- Left: Image Gallery -->
        <div>
            <div class="card" style="overflow: hidden; margin-bottom: 16px;">
                @if($scooter->images->count() > 0)
                    <img src="{{ asset('storage/' . $scooter->images->first()->image_path) }}" alt="{{ $scooter->name }}" style="width: 100%; height: 400px; object-fit: contain; background: var(--dark-lighter);">
                @else
                    <div style="width: 100%; height: 400px; display: flex; align-items: center; justify-content: center; font-size: 8rem; opacity: 0.3; background: var(--dark-lighter);">🛴</div>
                @endif
            </div>

            @if($scooter->images->count() > 1)
                <div style="display: flex; gap: 12px; overflow-x: auto;">
                    @foreach($scooter->images as $image)
                        <div class="card" style="flex-shrink: 0; width: 80px; height: 80px; overflow: hidden; cursor: pointer;">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $scooter->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Details -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                <span class="badge {{ $scooter->status === 'available' ? 'badge-success' : 'badge-warning' }}">
                    {{ $scooter->status === 'available' ? '✓ Disponible' : '⏳ En location' }}
                </span>
            </div>

            <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 8px; letter-spacing: -1px;">{{ $scooter->name }}</h1>
            <p style="color: var(--gray); font-size: 1.1rem; margin-bottom: 24px;">📍 {{ $scooter->location ?? 'Paris, France' }}</p>

            <!-- Price -->
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-body" style="display: flex; align-items: center; gap: 24px;">
                    <div>
                        <p style="color: var(--gray); font-size: 0.85rem; margin-bottom: 4px;">Tarif horaire</p>
                        <span class="price price-lg">{{ number_format($scooter->price_hour, 2) }} $</span>
                    </div>
                    <div style="width: 1px; height: 40px; background: rgba(255,255,255,0.1);"></div>
                    <div>
                        <p style="color: var(--gray); font-size: 0.85rem; margin-bottom: 4px;">Tarif journalier</p>
                        <span class="price">{{ number_format($scooter->price_day ?? $scooter->price_hour * 8, 2) }} $</span>
                    </div>
                </div>
            </div>

            <!-- Specs -->
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-body">
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 16px;">Caractéristiques</h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span style="font-size: 1.5rem;">⚡</span>
                            <div>
                                <p style="color: var(--gray); font-size: 0.8rem;">Vitesse max</p>
                                <p style="font-weight: 600;">{{ $scooter->max_speed }} km/h</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span style="font-size: 1.5rem;">🔋</span>
                            <div>
                                <p style="color: var(--gray); font-size: 0.8rem;">Batterie</p>
                                <p style="font-weight: 600;">{{ $scooter->battery_level ?? 100 }}%</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span style="font-size: 1.5rem;">⚖️</span>
                            <div>
                                <p style="color: var(--gray); font-size: 0.8rem;">Poids</p>
                                <p style="font-weight: 600;">{{ $scooter->weight ?? '15' }} kg</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span style="font-size: 1.5rem;">🛞</span>
                            <div>
                                <p style="color: var(--gray); font-size: 0.8rem;">Roues</p>
                                <p style="font-weight: 600;">{{ $scooter->wheel_size ?? '10' }} pouces</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($scooter->description)
                <div class="card" style="margin-bottom: 24px;">
                    <div class="card-body">
                        <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 12px;">Description</h3>
                        <p style="color: var(--gray); line-height: 1.7;">{{ $scooter->description }}</p>
                    </div>
                </div>
            @endif

            <!-- CTA -->
            @if($scooter->status === 'available')
                <a href="{{ route('reservations.create', $scooter) }}" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center;">
                    🚀 Réserver maintenant
                </a>
            @else
                <button class="btn btn-secondary btn-lg" style="width: 100%; opacity: 0.6; cursor: not-allowed;" disabled>
                    ⏳ Actuellement en location
                </button>
            @endif
        </div>
    </div>
</div>

@section('styles')
<style>
    @media (max-width: 768px) {
        .container > div:first-of-type + div {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
@endsection
