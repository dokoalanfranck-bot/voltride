@extends('layouts.app')

@section('title', 'Gestion des Trottinettes - VoltRide Admin')

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 60px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; flex-wrap: wrap; gap: 16px;">
        <div>
            <a href="{{ route('admin.dashboard') }}" style="color: var(--gray); text-decoration: none; font-size: 0.9rem; display: inline-block; margin-bottom: 8px;">← Retour au dashboard</a>
            <h1 style="font-size: 2rem; font-weight: 800; letter-spacing: -1px;">
                🛴 Gestion des <span style="color: var(--primary);">Trottinettes</span>
            </h1>
        </div>
        <a href="{{ route('admin.scooters.create') }}" class="btn btn-primary">
            ➕ Nouvelle Trottinette
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 32px;">
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <p style="color: var(--gray); font-size: 0.85rem; margin-bottom: 8px;">Total</p>
                <p style="font-size: 2rem; font-weight: 800; color: var(--primary);">{{ $scooters->total() }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <p style="color: var(--gray); font-size: 0.85rem; margin-bottom: 8px;">Disponibles</p>
                <p style="font-size: 2rem; font-weight: 800; color: #22c55e;">{{ $scooters->where('status', 'available')->count() }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body" style="text-align: center;">
                <p style="color: var(--gray); font-size: 0.85rem; margin-bottom: 8px;">En location</p>
                <p style="font-size: 2rem; font-weight: 800; color: #f59e0b;">{{ $scooters->where('status', 'rented')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Scooters Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Trottinette</th>
                            <th>Caractéristiques</th>
                            <th>Statut</th>
                            <th>Prix/h</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($scooters as $scooter)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        @if($scooter->images->count() > 0)
                                            <img src="{{ asset('storage/' . $scooter->images->first()->image_path) }}" alt="{{ $scooter->name }}" style="width: 50px; height: 50px; border-radius: 8px; object-fit: contain; background: var(--dark-lighter);">
                                        @else
                                            <div style="width: 50px; height: 50px; border-radius: 8px; background: var(--dark-lighter); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; opacity: 0.5;">🛴</div>
                                        @endif
                                        <div>
                                            <p style="font-weight: 700; color: var(--primary);">{{ $scooter->name }}</p>
                                            <p style="color: var(--gray); font-size: 0.85rem;">📍 {{ $scooter->location ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p style="font-weight: 600;">⚡ {{ $scooter->max_speed ?? 25 }} km/h</p>
                                    <p style="color: var(--gray); font-size: 0.85rem;">🔋 {{ $scooter->battery_level ?? 100 }}%</p>
                                </td>
                                <td>
                                    @if($scooter->status === 'available')
                                        <span class="badge badge-success">✓ Disponible</span>
                                    @elseif($scooter->status === 'rented')
                                        <span class="badge badge-warning">📋 En location</span>
                                    @else
                                        <span class="badge badge-danger">🔧 Maintenance</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="price">{{ number_format($scooter->price_hour, 2) }} $</span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="{{ route('admin.scooters.edit', $scooter) }}" class="btn btn-secondary" style="padding: 8px 12px; font-size: 0.85rem;">
                                            ✏️ Modifier
                                        </a>
                                        <form action="{{ route('admin.scooters.destroy', $scooter) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette trottinette ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn" style="padding: 8px 12px; font-size: 0.85rem; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3);">
                                                🗑️ Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px;">
                                    <div style="font-size: 3rem; margin-bottom: 16px; opacity: 0.3;">🛴</div>
                                    <p style="color: var(--gray); margin-bottom: 16px;">Aucune trottinette trouvée</p>
                                    <a href="{{ route('admin.scooters.create') }}" class="btn btn-primary">
                                        ➕ Ajouter une trottinette
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($scooters->hasPages())
                <div style="margin-top: 24px; display: flex; justify-content: center;">
                    {{ $scooters->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .container > div:nth-child(3) {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
