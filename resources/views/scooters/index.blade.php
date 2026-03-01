@extends('layouts.app')

@section('title', 'Nos Trottinettes - VoltRide')

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 60px;">
    <!-- Page Header -->
    <div class="section-header" style="margin-bottom: 40px;">
        <div class="section-tag">Notre flotte</div>
        <h1 class="section-title">Trouvez votre trottinette idéale</h1>
        <p class="section-subtitle">Parcourez notre sélection de trottinettes électriques premium, toujours chargées et prêtes à rouler.</p>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 32px;">
        <div class="card-body">
            <form method="GET" action="{{ route('scooters.index') }}" style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end;">
                <div style="flex: 1; min-width: 200px;">
                    <label class="form-label">Rechercher</label>
                    <input type="text" name="search" class="form-input" placeholder="Nom, marque, modèle..." value="{{ request('search') }}">
                </div>
                <div style="flex: 1; min-width: 150px;">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-input">
                        <option value="">Tous</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Disponible</option>
                        <option value="rented" {{ request('status') === 'rented' ? 'selected' : '' }}>En location</option>
                    </select>
                </div>
                <div style="flex: 1; min-width: 150px;">
                    <label class="form-label">Trier par</label>
                    <select name="sort" class="form-input">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Plus récents</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                    </select>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-search"></i> Filtrer</button>
                    <a href="{{ route('scooters.index') }}" class="btn btn-secondary">↺ Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Scooters Grid -->
    @if($scooters->count() > 0)
        <div class="grid grid-auto" style="margin-bottom: 40px;">
            @foreach($scooters as $scooter)
                <div class="card animate-fade-in">
                    @if($scooter->images->count() > 0)
                        <img src="{{ asset('storage/' . $scooter->images->first()->image_path) }}" alt="{{ $scooter->name }}" class="card-image">
                    @else
                        <div class="card-image" style="display: flex; align-items: center; justify-content: center; font-size: 4rem; opacity: 0.3;"><i class="fa-solid fa-motorcycle"></i></div>
                    @endif
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                            <div>
                                <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 4px;">{{ $scooter->name }}</h3>
                                <p style="color: var(--gray); font-size: 0.9rem;"><i class="fa-solid fa-map-marker-alt"></i> {{ $scooter->location ?? 'Paris' }}</p>
                            </div>
                            <span class="badge {{ $scooter->status === 'available' ? 'badge-success' : 'badge-warning' }}">
                                {{ $scooter->status === 'available' ? 'Disponible' : 'En location' }}
                            </span>
                        </div>

                        <div style="display: flex; gap: 16px; margin-bottom: 16px; color: var(--gray); font-size: 0.85rem;">
                            <span><i class="fa-solid fa-bolt"></i> {{ $scooter->max_speed ?? 25 }} km/h</span>
                            <span><i class="fa-solid fa-battery-full"></i> {{ $scooter->battery_level ?? 100 }}%</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <span class="price">{{ number_format($scooter->price_hour, 2) }} $</span>
                                <span style="color: var(--gray); font-size: 0.85rem;">/heure</span>
                            </div>
                            <a href="{{ route('scooters.show', $scooter) }}" class="btn btn-primary btn-sm">Voir →</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($scooters->hasPages())
            <div style="display: flex; justify-content: center;">
                {{ $scooters->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fa-solid fa-motorcycle"></i></div>
            <h3 style="margin-bottom: 8px;">Aucune trottinette trouvée</h3>
            <p>Essayez de modifier vos filtres ou revenez plus tard.</p>
            <a href="{{ route('scooters.index') }}" class="btn btn-primary" style="margin-top: 20px;">Voir toutes les trottinettes</a>
        </div>
    @endif
</div>
@endsection
