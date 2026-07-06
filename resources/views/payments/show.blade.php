{{-- Stripe removed — payments are handled cash-on-site. --}}
@extends('layouts.app')
@section('title', 'Paiement — VoltRide')
@section('content')
<div class="section">
    <div class="container container-xs" style="text-align:center;padding:60px 24px;">
        <div style="font-size:48px;color:var(--primary);margin-bottom:16px;">
            <i class="fa-solid fa-money-bill-wave"></i>
        </div>
        <h1 style="font-size:24px;font-weight:800;margin-bottom:10px;">Paiement sur place</h1>
        <p style="color:var(--txt2);font-size:15px;line-height:1.7;margin-bottom:28px;">
            Le paiement s'effectue directement sur place, en espèces, avant le départ de la trottinette.<br>
            Une vérification d'identité sera réalisée par notre équipe.
        </p>
        <a href="{{ route('reservations.index') }}" class="btn btn-primary">
            <i class="fa-solid fa-calendar-check"></i> Mes réservations
        </a>
    </div>
</div>
@endsection
