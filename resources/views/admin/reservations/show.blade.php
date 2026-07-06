@extends('layouts.admin')

@section('title', 'Réservation #'.$reservation->id)
@section('breadcrumb', 'Réservation #'.$reservation->id)

@section('content')
<div class="page-header">
    <div>
        <div class="page-heading">Réservation <span style="color:var(--txt2);font-weight:400;font-size:16px;">#{{ $reservation->id }}</span></div>
        <div class="page-subtitle">Créée le {{ $reservation->created_at->format('d/m/Y à H:i') }}</div>
    </div>
    <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> <span class="btn-label">Retour</span>
    </a>
</div>

{{-- Mobile: status strip --}}
<div class="mobile-status-strip">
    @php
        $b = match($reservation->status){ 'pending'=>'badge-amber','active'=>'badge-blue','completed'=>'badge-green','cancelled'=>'badge-gray',default=>'badge-gray'};
        $l = match($reservation->status){ 'pending'=>'En attente','active'=>'En cours','completed'=>'Terminée','cancelled'=>'Annulée',default=>$reservation->status};
    @endphp
    <span class="badge {{ $b }}" style="font-size:13px;padding:6px 14px;"><span class="badge-dot"></span>{{ $l }}</span>
    <span style="font-size:22px;font-weight:900;color:var(--primary);">
        {{ $reservation->total_price ? number_format($reservation->total_price,2,',','.').'€' : '—' }}
    </span>
    @if($reservation->payment_status === 'completed')
        <span class="badge badge-green" style="font-size:11px;"><span class="badge-dot"></span>Réglé</span>
    @else
        <span class="badge badge-amber" style="font-size:11px;"><span class="badge-dot"></span>Non réglé</span>
    @endif
</div>

{{-- Mobile: quick actions --}}
@if($reservation->payment_status !== 'completed' && $reservation->status !== 'cancelled')
<div class="mobile-quick-actions">
    <form method="POST" action="{{ route('admin.reservations.validatePayment', $reservation) }}" style="flex:1;">
        @csrf
        <button type="submit" class="btn btn-success" style="width:100%;justify-content:center;min-height:48px;">
            <i class="fa-solid fa-circle-check"></i> Valider le paiement
        </button>
    </form>
    @if($reservation->status === 'active')
    <form method="POST" action="{{ route('admin.reservations.complete', $reservation) }}" style="flex:1;">
        @csrf
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;min-height:48px;">
            <i class="fa-solid fa-flag-checkered"></i> Terminer
        </button>
    </form>
    @endif
</div>
@endif

{{-- Main grid --}}
<div class="detail-grid">

    {{-- Left col --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Client --}}
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-user" style="color:var(--primary);margin-right:6px;"></i>Client</div></div>
            <div class="card-body">
                <div class="detail-info-grid">
                    <div class="detail-info-item">
                        <div class="detail-info-label">Nom</div>
                        <div class="detail-info-value">{{ $reservation->user?->name ?? $reservation->guest_name ?? '—' }}</div>
                    </div>
                    <div class="detail-info-item">
                        <div class="detail-info-label">Email</div>
                        <div class="detail-info-value" style="word-break:break-all;">{{ $reservation->user?->email ?? $reservation->guest_email ?? '—' }}</div>
                    </div>
                    <div class="detail-info-item">
                        <div class="detail-info-label">Téléphone</div>
                        <div class="detail-info-value">
                            @php $phone = $reservation->user?->phone ?? $reservation->guest_phone; @endphp
                            @if($phone)
                                <a href="tel:{{ $phone }}" style="color:var(--primary);text-decoration:none;">{{ $phone }}</a>
                            @else —
                            @endif
                        </div>
                    </div>
                    <div class="detail-info-item">
                        <div class="detail-info-label">Type</div>
                        <div class="detail-info-value">{{ $reservation->user_id ? 'Compte' : 'Invité' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Booking --}}
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa-solid fa-calendar" style="color:var(--primary);margin-right:6px;"></i>Réservation</div></div>
            <div class="card-body">
                <div class="detail-info-grid">
                    <div class="detail-info-item">
                        <div class="detail-info-label">Trottinette</div>
                        <div class="detail-info-value">{{ $reservation->scooter?->name ?? '—' }}</div>
                        @if($reservation->scooter?->location)
                            <div style="font-size:11px;color:var(--txt3);">{{ $reservation->scooter->location }}</div>
                        @endif
                    </div>
                    <div class="detail-info-item">
                        <div class="detail-info-label">Mode paiement</div>
                        <div class="detail-info-value">{{ $reservation->payment_method ?? 'Espèces' }}</div>
                    </div>
                    <div class="detail-info-item">
                        <div class="detail-info-label">Début</div>
                        <div class="detail-info-value">{{ $reservation->start_time?->format('d/m/Y') }}</div>
                        <div style="color:var(--txt2);font-size:13px;">{{ $reservation->start_time?->format('H:i') }}</div>
                    </div>
                    <div class="detail-info-item">
                        <div class="detail-info-label">Fin</div>
                        <div class="detail-info-value">{{ $reservation->end_time?->format('d/m/Y') }}</div>
                        <div style="color:var(--txt2);font-size:13px;">{{ $reservation->end_time?->format('H:i') }}</div>
                    </div>
                    <div class="detail-info-item">
                        <div class="detail-info-label">Durée</div>
                        @php
                            $dur = $reservation->start_time && $reservation->end_time
                                ? $reservation->start_time->diffInMinutes($reservation->end_time) : 0;
                        @endphp
                        <div class="detail-info-value">{{ intdiv($dur, 60) > 0 ? intdiv($dur,60).'h ' : '' }}{{ $dur % 60 }}min</div>
                    </div>
                    @if($reservation->delay_minutes)
                    <div class="detail-info-item">
                        <div class="detail-info-label">Retard</div>
                        <div class="detail-info-value" style="color:#ffaa00;">{{ $reservation->delay_minutes }} min</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Update status --}}
        @if(!in_array($reservation->status, ['completed', 'cancelled']))
        <div class="card">
            <div class="card-header"><div class="card-title">Modifier le statut</div></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.reservations.update', $reservation) }}" style="display:flex;gap:10px;align-items:flex-end;">
                    @csrf @method('PUT')
                    <div class="form-group" style="margin:0;flex:1;">
                        <label class="form-label">Nouveau statut</label>
                        <select name="status" class="form-control">
                            <option value="pending"   {{ $reservation->status==='pending'   ? 'selected':'' }}>En attente</option>
                            <option value="active"    {{ $reservation->status==='active'    ? 'selected':'' }}>En cours</option>
                            <option value="completed" {{ $reservation->status==='completed' ? 'selected':'' }}>Terminée</option>
                            <option value="cancelled" {{ $reservation->status==='cancelled' ? 'selected':'' }}>Annulée</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="flex-shrink:0;min-height:46px;">
                        <i class="fa-solid fa-check"></i> Appliquer
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>

    {{-- Right col --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Status & payment (desktop) --}}
        <div class="card desktop-only-card">
            <div class="card-header"><div class="card-title">Statut</div></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:16px;">
                <div>
                    <div class="detail-info-label" style="margin-bottom:6px;">Réservation</div>
                    <span class="badge {{ $b }}" style="font-size:13px;padding:5px 12px;"><span class="badge-dot"></span>{{ $l }}</span>
                </div>
                <div>
                    <div class="detail-info-label" style="margin-bottom:6px;">Paiement</div>
                    @if($reservation->payment_status === 'completed')
                        <span class="badge badge-green" style="font-size:13px;padding:5px 12px;"><span class="badge-dot"></span>Réglé</span>
                    @elseif($reservation->payment_status === 'refunded')
                        <span class="badge badge-purple" style="font-size:13px;padding:5px 12px;"><span class="badge-dot"></span>Remboursé</span>
                    @else
                        <span class="badge badge-amber" style="font-size:13px;padding:5px 12px;"><span class="badge-dot"></span>En attente</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Amount (desktop) --}}
        <div class="card desktop-only-card">
            <div class="card-header"><div class="card-title">Montant</div></div>
            <div class="card-body">
                <div style="font-size:32px;font-weight:900;letter-spacing:-1px;color:var(--primary);">
                    {{ $reservation->total_price ? number_format($reservation->total_price,2,',','.').'€' : '—' }}
                </div>
                @if($reservation->delay_fee)
                <div style="font-size:12px;color:#ffaa00;margin-top:4px;">
                    dont {{ number_format($reservation->delay_fee,2,',','') }}€ de pénalité retard
                </div>
                @endif

                @if($reservation->payment_status !== 'completed' && $reservation->status !== 'cancelled')
                <div style="margin-top:16px;">
                    <form method="POST" action="{{ route('admin.reservations.validatePayment', $reservation) }}">
                        @csrf
                        <button type="submit" class="btn btn-success" style="width:100%;justify-content:center;">
                            <i class="fa-solid fa-circle-check"></i> Valider le paiement (espèces)
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        {{-- Quick actions (desktop) --}}
        @if($reservation->status === 'active')
        <div class="card desktop-only-card">
            <div class="card-header"><div class="card-title">Actions rapides</div></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.reservations.complete', $reservation) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                        <i class="fa-solid fa-flag-checkered"></i> Marquer comme terminée
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>

<style>
.detail-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 16px;
    align-items: start;
}
.detail-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}
.detail-info-label {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .7px; color: var(--txt3); margin-bottom: 4px;
}
.detail-info-value { font-weight: 600; font-size: 14px; }

/* Mobile status strip & quick actions — hidden on desktop */
.mobile-status-strip { display: none; }
.mobile-quick-actions { display: none; }

@media (max-width: 767px) {
    .detail-grid { grid-template-columns: 1fr; }
    .desktop-only-card { display: none; }

    /* Show mobile strips */
    .mobile-status-strip {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 14px;
    }
    .mobile-status-strip span:nth-child(2) { margin-left: auto; }

    .mobile-quick-actions {
        display: flex;
        gap: 10px;
        margin-bottom: 14px;
    }

    .detail-info-grid { grid-template-columns: 1fr; gap: 10px; }
    .btn-label { display: none; }
}
</style>
@endsection
