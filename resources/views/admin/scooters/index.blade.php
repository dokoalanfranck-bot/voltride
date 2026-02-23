@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <style>
        :root {
            --green-primary: #0a9b3a;
            --primary-gradient: linear-gradient(135deg, #47F55B 0%, #07d65d 100%);
            --primary-light: #f0fdf4;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        tbody tr {
            animation: fadeIn 0.5s ease-out;
        }
        tbody tr:hover {
            background-color: #f0fdf4;
        }
        input:focus, select:focus {
            border-color: #07d65d !important;
            box-shadow: 0 0 0 3px rgba(7, 214, 93, 0.2);
            outline: none;
        }
    </style>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <h1 style="font-size: 2rem; font-weight: 800; color: var(--green-primary);">Gestion des Trottinettes</h1>
        <a href="{{ route('admin.scooters.create') }}" style="
            background: var(--primary-gradient);
            color: #0f172a;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            transform: translateY(0);
        " onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 24px rgba(7, 214, 93, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
            + Ajouter une trottinette
        </a>
    </div>

    <!-- Filters -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 24px;">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--green-primary); margin-bottom: 16px;">Filtres</h3>
        
        <form method="GET" action="{{ route('admin.scooters.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <!-- Search -->
            <div>
                <label style="display: block; font-weight: 600; color: var(--green-primary); margin-bottom: 6px;">Recherche (nom/marque/modèle)</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Chercher..." style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem;">
            </div>

            <!-- Availability Filter -->
            <div>
                <label style="display: block; font-weight: 600; color: var(--green-primary); margin-bottom: 6px;">Disponibilité</label>
                <select name="availability" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem;">
                    <option value="">Toutes</option>
                    <option value="available" {{ request('availability') === 'available' ? 'selected' : '' }}>Disponible</option>
                    <option value="unavailable" {{ request('availability') === 'unavailable' ? 'selected' : '' }}>Indisponible</option>
                </select>
            </div>

            <!-- Min Price -->
            <div>
                <label style="display: block; font-weight: 600; color: var(--green-primary); margin-bottom: 6px;">Prix min (€/h)</label>
                <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="0" step="0.01" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem;">
            </div>

            <!-- Max Price -->
            <div>
                <label style="display: block; font-weight: 600; color: var(--green-primary); margin-bottom: 6px;">Prix max (€/h)</label>
                <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="999" step="0.01" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem;">
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 8px; align-items: flex-end;">
                <button type="submit" style="background: var(--primary-gradient); color: #0f172a; padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; flex: 1; transition: all 0.3s; transform: translateY(0);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px rgba(7, 214, 93, 0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    🔍 Filtrer
                </button>
                <a href="{{ route('admin.scooters.index') }}" style="background: #6c757d; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 600; text-align: center;">
                    ↺ Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Scooters Table -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--primary-gradient); color: #0f172a;">
                        <th style="padding: 16px; text-align: left; font-weight: 600;">Nom</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600;">Marque</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600;">Statut</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600;">Prix/h</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600;">Prix/jour</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600;">Batterie</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scooters as $scooter)
                        <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.2s;">
                            <td style="padding: 16px; font-weight: 600; color: var(--green-primary);">{{ $scooter->name }}</td>
                            <td style="padding: 16px;">{{ $scooter->brand ?? 'N/A' }}</td>
                            <td style="padding: 16px;">
                                <span style="
                                    display: inline-block;
                                    padding: 6px 12px;
                                    border-radius: 20px;
                                    font-size: 0.85rem;
                                    font-weight: 600;
                                    @if ($scooter->is_available)
                                        background: #d1fae5;
                                        color: var(--green-primary);
                                    @else
                                        background: #fee2e2;
                                        color: #991b1b;
                                    @endif
                                ">
                                    {{ $scooter->is_available ? '✓ Disponible' : '✗ Indisponible' }}
                                </span>
                            </td>
                            <td style="padding: 16px;">{{ number_format($scooter->price_hour, 2) }} €</td>
                            <td style="padding: 16px;">{{ number_format($scooter->price_day, 2) }} €</td>
                            <td style="padding: 16px;">{{ $scooter->battery_level ?? 'N/A' }}%</td>
                            <td style="padding: 16px; display: flex; gap: 8px;">
                                <a href="{{ route('admin.scooters.edit', $scooter) }}" style="
                                    display: inline-block;
                                    background: var(--green-primary);
                                    color: white;
                                    padding: 6px 12px;
                                    border-radius: 4px;
                                    text-decoration: none;
                                    font-size: 0.85rem;
                                    font-weight: 600;
                                    transition: all 0.3s;
                                " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(10, 155, 58, 0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">Éditer</a>
                                <form method="POST" action="{{ route('admin.scooters.destroy', $scooter) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="
                                        background: #dc3545;
                                        color: white;
                                        padding: 6px 12px;
                                        border-radius: 4px;
                                        border: none;
                                        cursor: pointer;
                                        font-size: 0.85rem;
                                        font-weight: 600;
                                        transition: all 0.3s;
                                    " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(220, 53, 69, 0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';" onclick="return confirm('Êtes-vous sûr?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 32px; text-align: center; color: #999;">
                                Aucune trottinette trouvée. <a href="{{ route('admin.scooters.create') }}" style="color: var(--green-primary); font-weight: 600;">En créer une</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if ($scooters->hasPages())
        <div style="margin-top: 32px;">
            {{ $scooters->links() }}
        </div>
    @endif
</div>
@endsection
