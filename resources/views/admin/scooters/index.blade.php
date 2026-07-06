@extends('layouts.admin')

@section('title', 'Trottinettes')
@section('breadcrumb', 'Trottinettes')

@section('content')
<div class="page-header">
    <div>
        <div class="page-heading">Trottinettes</div>
        <div class="page-subtitle">{{ $scooters->total() }} trottinette{{ $scooters->total() > 1 ? 's' : '' }} au total</div>
    </div>
    <a href="{{ route('admin.scooters.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> <span class="btn-label">Ajouter</span>
    </a>
</div>

{{-- Filters --}}
<form method="GET">
    {{-- Mobile toggle --}}
    <button type="button" class="filters-toggle" onclick="toggleFilters(this)">
        <i class="fa-solid fa-sliders"></i>
        <span class="filters-toggle-label">Afficher les filtres</span>
        @if(request()->hasAny(['search','status','price_min','price_max']))
            <span class="badge badge-green" style="margin-left:auto;">Actifs</span>
        @endif
        <i class="fa-solid fa-chevron-down filters-toggle-icon" style="margin-left:auto;transition:transform .2s;"></i>
    </button>

    <div class="filters-body filters-bar">
        <input type="text" name="search" class="form-control search-input"
               placeholder="Rechercher..." value="{{ request('search') }}">
        <select name="status" class="form-control">
            <option value="">Tous les statuts</option>
            <option value="available"   {{ request('status')==='available'   ? 'selected' : '' }}>Disponible</option>
            <option value="rented"      {{ request('status')==='rented'      ? 'selected' : '' }}>En location</option>
            <option value="maintenance" {{ request('status')==='maintenance' ? 'selected' : '' }}>Maintenance</option>
        </select>
        <input type="number" name="price_min" class="form-control" placeholder="Prix min €" value="{{ request('price_min') }}" style="max-width:130px;">
        <input type="number" name="price_max" class="form-control" placeholder="Prix max €" value="{{ request('price_max') }}" style="max-width:130px;">
        <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Filtrer</button>
        @if(request()->hasAny(['search','status','price_min','price_max']))
            <a href="{{ route('admin.scooters.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-xmark"></i> Réinitialiser</a>
        @endif
    </div>
</form>

<div class="card">

    {{-- ── Desktop table ───────────────────────────── --}}
    <div class="table-wrap desktop-table">
        <table>
            <thead>
                <tr>
                    <th>Trottinette</th>
                    <th>Statut</th>
                    <th>Batterie</th>
                    <th>Prix/h</th>
                    <th>Vitesse max</th>
                    <th>Réservations</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($scooters as $scooter)
                @php
                    $sBadge = match($scooter->status) { 'available'=>'badge-green','rented'=>'badge-blue','maintenance'=>'badge-amber', default=>'badge-gray' };
                    $sLabel = match($scooter->status) { 'available'=>'Disponible','rented'=>'En location','maintenance'=>'Maintenance', default=>$scooter->status };
                @endphp
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            @if($scooter->images->first())
                                <img src="{{ route('storage.image', $scooter->images->first()->image_path) }}"
                                     alt="" style="width:40px;height:40px;object-fit:cover;border-radius:8px;flex-shrink:0;border:1px solid var(--border);">
                            @else
                                <div style="width:40px;height:40px;background:var(--surface2);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--txt3);flex-shrink:0;"><i class="fa-solid fa-motorcycle"></i></div>
                            @endif
                            <div>
                                <div class="td-main">{{ $scooter->name }}</div>
                                <div style="font-size:11px;color:var(--txt3);">{{ $scooter->location ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $sBadge }}"><span class="badge-dot"></span>{{ $sLabel }}</span>
                        @if(!$scooter->is_active)
                            <span class="badge badge-red" style="margin-top:3px;display:inline-flex;"><span class="badge-dot"></span>Inactif</span>
                        @endif
                    </td>
                    <td>
                        @if($scooter->battery_level !== null)
                        <div style="display:flex;align-items:center;gap:7px;">
                            <div style="width:50px;height:6px;background:var(--surface2);border-radius:3px;overflow:hidden;">
                                <div style="width:{{ $scooter->battery_level }}%;height:100%;background:{{ $scooter->battery_level > 50 ? '#00FF6A' : ($scooter->battery_level > 20 ? '#ffaa00' : '#ff4d4d') }};border-radius:3px;"></div>
                            </div>
                            <span style="font-size:12px;font-weight:600;">{{ $scooter->battery_level }}%</span>
                        </div>
                        @else <span style="color:var(--txt3);">—</span> @endif
                    </td>
                    <td class="td-main">{{ number_format($scooter->price_hour, 2, ',', ' ') }}€</td>
                    <td>{{ $scooter->max_speed ? $scooter->max_speed.' km/h' : '—' }}</td>
                    <td><span style="font-weight:600;">{{ $scooter->reservations_count }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.scooters.edit', $scooter) }}" class="btn btn-secondary btn-sm btn-icon" title="Modifier"><i class="fa-solid fa-pen"></i></a>
                            <form method="POST" action="{{ route('admin.scooters.destroy', $scooter) }}" onsubmit="return confirm('Supprimer {{ addslashes($scooter->name) }} ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Supprimer"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7"><div class="empty-state"><i class="fa-solid fa-motorcycle"></i><p>Aucune trottinette trouvée</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Mobile cards ────────────────────────────── --}}
    <div class="mobile-row-cards">
        @forelse($scooters as $scooter)
        @php
            $sBadge = match($scooter->status) { 'available'=>'badge-green','rented'=>'badge-blue','maintenance'=>'badge-amber', default=>'badge-gray' };
            $sLabel = match($scooter->status) { 'available'=>'Disponible','rented'=>'En location','maintenance'=>'Maintenance', default=>$scooter->status };
        @endphp
        <div class="mobile-row-card">
            <div class="mobile-row-card-top">
                <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">
                    @if($scooter->images->first())
                        <img src="{{ route('storage.image', $scooter->images->first()->image_path) }}"
                             alt="" style="width:48px;height:48px;object-fit:cover;border-radius:9px;flex-shrink:0;border:1px solid var(--border);">
                    @else
                        <div style="width:48px;height:48px;background:var(--surface2);border-radius:9px;display:flex;align-items:center;justify-content:center;color:var(--txt3);flex-shrink:0;font-size:18px;"><i class="fa-solid fa-motorcycle"></i></div>
                    @endif
                    <div style="min-width:0;">
                        <div class="mobile-row-card-title" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $scooter->name }}</div>
                        <div class="mobile-row-card-sub">{{ $scooter->location ?? 'Emplacement N/A' }}</div>
                    </div>
                </div>
                <span class="badge {{ $sBadge }}" style="flex-shrink:0;"><span class="badge-dot"></span>{{ $sLabel }}</span>
            </div>

            <div class="mobile-row-card-meta">
                {{-- Battery --}}
                @if($scooter->battery_level !== null)
                <div style="display:flex;align-items:center;gap:6px;">
                    <i class="fa-solid fa-battery-half" style="color:{{ $scooter->battery_level > 50 ? '#00FF6A' : ($scooter->battery_level > 20 ? '#ffaa00' : '#ff4d4d') }};font-size:13px;"></i>
                    <span style="font-size:12px;font-weight:600;color:{{ $scooter->battery_level > 50 ? '#00FF6A' : ($scooter->battery_level > 20 ? '#ffaa00' : '#ff4d4d') }};">{{ $scooter->battery_level }}%</span>
                </div>
                @endif
                {{-- Price --}}
                <span style="font-size:12px;color:var(--txt2);"><i class="fa-solid fa-euro-sign" style="font-size:10px;"></i> {{ number_format($scooter->price_hour, 2, ',', ' ') }}/h</span>
                {{-- Speed --}}
                @if($scooter->max_speed)
                <span style="font-size:12px;color:var(--txt2);"><i class="fa-solid fa-gauge-high" style="font-size:10px;"></i> {{ $scooter->max_speed }} km/h</span>
                @endif
                {{-- Reservations --}}
                <span style="font-size:12px;color:var(--txt2);"><i class="fa-solid fa-calendar-check" style="font-size:10px;"></i> {{ $scooter->reservations_count }} réserv.</span>
                @if(!$scooter->is_active)
                    <span class="badge badge-red" style="font-size:10px;padding:2px 7px;"><span class="badge-dot"></span>Inactif</span>
                @endif
            </div>

            <div class="mobile-row-card-actions">
                <a href="{{ route('admin.scooters.edit', $scooter) }}" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-pen"></i> Modifier
                </a>
                <form method="POST" action="{{ route('admin.scooters.destroy', $scooter) }}"
                      onsubmit="return confirm('Supprimer {{ addslashes($scooter->name) }} ?')"
                      style="flex:1;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" style="width:100%;justify-content:center;">
                        <i class="fa-solid fa-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-state"><i class="fa-solid fa-motorcycle"></i><p>Aucune trottinette trouvée</p></div>
        @endforelse
    </div>

    @if($scooters->hasPages())
    <div class="pagination-wrap">
        <span>{{ $scooters->firstItem() }}–{{ $scooters->lastItem() }} sur {{ $scooters->total() }}</span>
        {{ $scooters->withQueryString()->links() }}
    </div>
    @endif
</div>

@if(request()->hasAny(['search','status','price_min','price_max']))
<script>
    // Auto-open filters if active
    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.querySelector('.filters-toggle');
        if (btn && window.innerWidth < 768) toggleFilters(btn);
    });
</script>
@endif
@endsection
