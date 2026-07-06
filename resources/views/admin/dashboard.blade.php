@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('breadcrumb', 'Tableau de bord')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div class="page-heading">Tableau de <span class="accent">bord</span></div>
        <div class="page-subtitle">Vue d'ensemble — {{ now()->format('d/m/Y') }}</div>
    </div>
    <a href="{{ route('admin.scooters.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Ajouter une trottinette
    </a>
</div>

{{-- KPI Cards --}}
<div class="stat-grid">
    <div class="stat-card c-green">
        <div class="stat-icon"><i class="fa-solid fa-euro-sign"></i></div>
        <div class="stat-label">Revenus totaux</div>
        <div class="stat-value">{{ number_format($totalRevenue, 0, ',', ' ') }}€</div>
        <div class="stat-meta">Ce mois : {{ number_format($monthlyRevenue, 0, ',', ' ') }}€</div>
    </div>
    <div class="stat-card c-blue">
        <div class="stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
        <div class="stat-label">Réservations</div>
        <div class="stat-value">{{ $totalReservations }}</div>
        <div class="stat-meta">{{ $activeReservations }} en cours · {{ $pendingReservations }} en attente</div>
    </div>
    <div class="stat-card c-amber">
        <div class="stat-icon"><i class="fa-solid fa-motorcycle"></i></div>
        <div class="stat-label">Trottinettes</div>
        <div class="stat-value">{{ $totalScooters }}</div>
        <div class="stat-meta">{{ $activeScooters }} disponibles</div>
    </div>
    <div class="stat-card c-red">
        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
        <div class="stat-label">Clients</div>
        <div class="stat-value">{{ $totalUsers }}</div>
        <div class="stat-meta">Comptes enregistrés</div>
    </div>
</div>

{{-- Charts --}}
<div class="dash-charts-grid" style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:24px;">
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Revenus mensuels</div>
                <div class="card-subtitle">6 derniers mois</div>
            </div>
        </div>
        <div class="card-body">
            <canvas id="revenueChart" height="110"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Statut des réservations</div>
                <div class="card-subtitle">Répartition globale</div>
            </div>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;align-items:center;gap:20px;">
            <canvas id="statusChart" width="150" height="150" style="max-width:150px;"></canvas>
            <div style="width:100%;display:flex;flex-direction:column;gap:8px;">
                @php
                    $sLabels = [
                        'pending'   => ['En attente', '#ffaa00'],
                        'active'    => ['En cours',   '#00aaff'],
                        'completed' => ['Terminées',  '#00FF6A'],
                        'cancelled' => ['Annulées',   '#555555'],
                    ];
                @endphp
                @foreach($sLabels as $key => [$label, $color])
                    @if(($statusCounts[$key] ?? 0) > 0)
                    <div style="display:flex;align-items:center;justify-content:space-between;font-size:12px;">
                        <div style="display:flex;align-items:center;gap:7px;">
                            <span style="width:8px;height:8px;border-radius:50%;background:{{ $color }};flex-shrink:0;display:inline-block;"></span>
                            <span style="color:var(--txt2);">{{ $label }}</span>
                        </div>
                        <span style="font-weight:700;color:var(--txt);">{{ $statusCounts[$key] }}</span>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Tables --}}
<div class="dash-tables-grid" style="display:grid;grid-template-columns:3fr 2fr;gap:20px;">

    <div class="card">
        <div class="card-header">
            <div class="card-title">Réservations récentes</div>
            <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary btn-sm">Voir tout</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Trottinette</th>
                        <th>Statut</th>
                        <th>Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentReservations as $r)
                    <tr style="cursor:pointer;" onclick="location.href='{{ route('admin.reservations.show', $r) }}'">
                        <td><span style="font-family:monospace;font-size:11px;color:var(--txt3);">#{{ $r->id }}</span></td>
                        <td class="td-main">{{ $r->user?->name ?? $r->guest_name ?? 'Invité' }}</td>
                        <td>{{ $r->scooter?->name ?? '—' }}</td>
                        <td>
                            @php
                                $b = match($r->status) {
                                    'pending'   => 'badge-amber',
                                    'active'    => 'badge-blue',
                                    'completed' => 'badge-green',
                                    'cancelled' => 'badge-gray',
                                    default     => 'badge-gray',
                                };
                                $l = match($r->status) {
                                    'pending'   => 'En attente',
                                    'active'    => 'En cours',
                                    'completed' => 'Terminée',
                                    'cancelled' => 'Annulée',
                                    default     => $r->status,
                                };
                            @endphp
                            <span class="badge {{ $b }}"><span class="badge-dot"></span>{{ $l }}</span>
                        </td>
                        <td style="font-weight:600;">{{ $r->total_price ? number_format($r->total_price, 2, ',', ' ').'€' : '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state" style="padding:30px 20px;"><i class="fa-regular fa-calendar-xmark"></i><p>Aucune réservation</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">Top trottinettes</div>
            <a href="{{ route('admin.scooters.index') }}" class="btn btn-secondary btn-sm">Gérer</a>
        </div>
        <div style="padding:0;">
            @forelse($topScooters as $i => $s)
            <div style="display:flex;align-items:center;gap:12px;padding:14px 20px;{{ !$loop->last ? 'border-bottom:1px solid var(--border);' : '' }}">
                <div style="width:26px;height:26px;background:var(--surface2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:{{ $i===0?'#f59e0b':($i===1?'#94a3b8':($i===2?'#b45309':'var(--txt3)')) }};flex-shrink:0;">{{ $i+1 }}</div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $s->name }}</div>
                    <div style="font-size:11px;color:var(--txt3);">{{ $s->location ?? 'Non défini' }}</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-weight:700;font-size:16px;color:var(--primary);">{{ $s->reservations_count }}</div>
                    <div style="font-size:10px;color:var(--txt3);">réserv.</div>
                </div>
            </div>
            @empty
            <div class="empty-state"><i class="fa-solid fa-motorcycle"></i><p>Aucune donnée</p></div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@section('scripts')
<style>
@media (max-width: 767px) {
    .dash-charts-grid { grid-template-columns: 1fr !important; }
    .dash-tables-grid  { grid-template-columns: 1fr !important; }
    /* Compact donut on mobile */
    #statusChart { max-width: 120px !important; }
}
</style>
<script>
Chart.defaults.color = '#666666';

new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: @json($monthLabels),
        datasets: [{
            data: @json($revenueByMonth),
            backgroundColor: 'rgba(0, 255, 106, 0.12)',
            borderColor: '#00FF6A',
            borderWidth: 2,
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ' ' + ctx.raw.toFixed(2) + ' €' } }
        },
        scales: {
            x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#666', font: { size: 11 } } },
            y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#666', font: { size: 11 }, callback: v => v + '€' } }
        }
    }
});

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['En attente','En cours','Terminées','Annulées'],
        datasets: [{
            data: [
                {{ $statusCounts['pending']   ?? 0 }},
                {{ $statusCounts['active']    ?? 0 }},
                {{ $statusCounts['completed'] ?? 0 }},
                {{ $statusCounts['cancelled'] ?? 0 }}
            ],
            backgroundColor: ['#ffaa00','#00aaff','#00FF6A','#333333'],
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: {
        cutout: '72%',
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ' ' + ctx.label + ': ' + ctx.raw } }
        }
    }
});
</script>
@endsection
