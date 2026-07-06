@extends('layouts.admin')

@section('title', 'Réservations')
@section('breadcrumb', 'Réservations')

@section('content')
<div class="page-header">
    <div>
        <div class="page-heading">Réservations</div>
        <div class="page-subtitle">{{ $reservations->total() }} réservation{{ $reservations->total() > 1 ? 's' : '' }}</div>
    </div>
</div>

<form method="GET">
    {{-- Mobile toggle --}}
    <button type="button" class="filters-toggle" onclick="toggleFilters(this)">
        <i class="fa-solid fa-sliders"></i>
        <span class="filters-toggle-label">Afficher les filtres</span>
        @if(request()->hasAny(['search','status','payment_status','date_from','date_to']))
            <span class="badge badge-green" style="margin-left:auto;">Actifs</span>
        @endif
        <i class="fa-solid fa-chevron-down filters-toggle-icon" style="margin-left:auto;transition:transform .2s;"></i>
    </button>

    <div class="filters-body filters-bar">
        <input type="text" name="search" class="form-control search-input"
               placeholder="Nom ou email..." value="{{ request('search') }}">
        <select name="status" class="form-control">
            <option value="">Tous les statuts</option>
            <option value="pending"   {{ request('status')==='pending'   ? 'selected':'' }}>En attente</option>
            <option value="active"    {{ request('status')==='active'    ? 'selected':'' }}>En cours</option>
            <option value="completed" {{ request('status')==='completed' ? 'selected':'' }}>Terminées</option>
            <option value="cancelled" {{ request('status')==='cancelled' ? 'selected':'' }}>Annulées</option>
        </select>
        <select name="payment_status" class="form-control">
            <option value="">Paiement — tous</option>
            <option value="pending"   {{ request('payment_status')==='pending'   ? 'selected':'' }}>En attente</option>
            <option value="completed" {{ request('payment_status')==='completed' ? 'selected':'' }}>Réglé</option>
        </select>
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" style="max-width:160px;">
        <input type="date" name="date_to"   class="form-control" value="{{ request('date_to') }}"   style="max-width:160px;">
        <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Filtrer</button>
        @if(request()->hasAny(['search','status','payment_status','date_from','date_to']))
            <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-xmark"></i> Réinitialiser</a>
        @endif
    </div>
</form>

<div class="card">

    {{-- ── Desktop table ───────────────────────────── --}}
    <div class="table-wrap desktop-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Trottinette</th>
                    <th>Période</th>
                    <th>Statut</th>
                    <th>Paiement</th>
                    <th>Montant</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $r)
                @php
                    $b = match($r->status){ 'pending'=>'badge-amber','active'=>'badge-blue','completed'=>'badge-green','cancelled'=>'badge-gray',default=>'badge-gray'};
                    $l = match($r->status){ 'pending'=>'En attente','active'=>'En cours','completed'=>'Terminée','cancelled'=>'Annulée',default=>$r->status};
                @endphp
                <tr>
                    <td><span style="font-family:monospace;font-size:11px;color:var(--txt3);">#{{ $r->id }}</span></td>
                    <td>
                        <div class="td-main">{{ $r->user?->name ?? $r->guest_name ?? 'Invité' }}</div>
                        <div style="font-size:11px;color:var(--txt3);">{{ $r->user?->email ?? $r->guest_email ?? '' }}</div>
                    </td>
                    <td class="td-main">{{ $r->scooter?->name ?? '—' }}</td>
                    <td>
                        <div style="font-size:12px;">{{ $r->start_time?->format('d/m/Y H:i') }}</div>
                        <div style="font-size:11px;color:var(--txt3);">→ {{ $r->end_time?->format('H:i') }}</div>
                    </td>
                    <td><span class="badge {{ $b }}"><span class="badge-dot"></span>{{ $l }}</span></td>
                    <td>
                        @if($r->payment_status === 'completed')
                            <span class="badge badge-green"><span class="badge-dot"></span>Réglé</span>
                        @elseif($r->payment_status === 'refunded')
                            <span class="badge badge-purple"><span class="badge-dot"></span>Remboursé</span>
                        @else
                            <span class="badge badge-amber"><span class="badge-dot"></span>En attente</span>
                        @endif
                    </td>
                    <td style="font-weight:600;">{{ $r->total_price ? number_format($r->total_price,2,',',' ').'€' : '—' }}</td>
                    <td>
                        <a href="{{ route('admin.reservations.show', $r) }}" class="btn btn-secondary btn-sm">
                            <i class="fa-solid fa-eye"></i> Voir
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state"><i class="fa-regular fa-calendar-xmark"></i><p>Aucune réservation trouvée</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Mobile cards ────────────────────────────── --}}
    <div class="mobile-row-cards">
        @forelse($reservations as $r)
        @php
            $b = match($r->status){ 'pending'=>'badge-amber','active'=>'badge-blue','completed'=>'badge-green','cancelled'=>'badge-gray',default=>'badge-gray'};
            $l = match($r->status){ 'pending'=>'En attente','active'=>'En cours','completed'=>'Terminée','cancelled'=>'Annulée',default=>$r->status};
        @endphp
        <div class="mobile-row-card" style="cursor:pointer;" onclick="location.href='{{ route('admin.reservations.show', $r) }}'">
            <div class="mobile-row-card-top">
                <div style="flex:1;min-width:0;">
                    <div class="mobile-row-card-title">
                        {{ $r->user?->name ?? $r->guest_name ?? 'Invité' }}
                    </div>
                    <div class="mobile-row-card-sub">
                        {{ $r->scooter?->name ?? 'Trottinette N/A' }}
                        &nbsp;·&nbsp;
                        <span style="font-family:monospace;font-size:10px;">#{{ $r->id }}</span>
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;flex-shrink:0;">
                    <span class="badge {{ $b }}" style="font-size:10px;"><span class="badge-dot"></span>{{ $l }}</span>
                    <span style="font-size:14px;font-weight:800;color:var(--primary);">
                        {{ $r->total_price ? number_format($r->total_price,2,',','.').'€' : '—' }}
                    </span>
                </div>
            </div>

            <div class="mobile-row-card-meta">
                {{-- Dates --}}
                @if($r->start_time)
                <span style="font-size:12px;color:var(--txt2);">
                    <i class="fa-solid fa-calendar" style="font-size:10px;"></i>
                    {{ $r->start_time->format('d/m H:i') }}
                    @if($r->end_time) → {{ $r->end_time->format('H:i') }} @endif
                </span>
                @endif
                {{-- Payment --}}
                @if($r->payment_status === 'completed')
                    <span class="badge badge-green" style="font-size:10px;padding:2px 7px;"><span class="badge-dot"></span>Réglé</span>
                @else
                    <span class="badge badge-amber" style="font-size:10px;padding:2px 7px;"><span class="badge-dot"></span>Non réglé</span>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state"><i class="fa-regular fa-calendar-xmark"></i><p>Aucune réservation trouvée</p></div>
        @endforelse
    </div>

    @if($reservations->hasPages())
    <div class="pagination-wrap">
        <span>{{ $reservations->firstItem() }}–{{ $reservations->lastItem() }} sur {{ $reservations->total() }}</span>
        {{ $reservations->withQueryString()->links() }}
    </div>
    @endif
</div>

@if(request()->hasAny(['search','status','payment_status','date_from','date_to']))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.querySelector('.filters-toggle');
        if (btn && window.innerWidth < 768) toggleFilters(btn);
    });
</script>
@endif
@endsection
