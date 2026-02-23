<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Réservation - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --green-primary: #1f7550;
            --green-light: #2d9b6f;
            --green-dark: #155d3b;
        }
        body {
            background-color: #f5f5f5;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .bg-white {
            animation: slideIn 0.6s ease-out;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav style="background: linear-gradient(135deg, var(--green-primary) 0%, var(--green-dark) 100%); box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <div style="color: white; font-size: 1.5rem; font-weight: 800;">
                    🛴 ScooterRent
                </div>
                <div style="display: flex; gap: 20px;">
                    <a href="{{ route('admin.dashboard') }}" style="color: white; text-decoration: none; font-weight: 600;">Tableau de bord</a>
                    <a href="{{ route('admin.scooters.index') }}" style="color: white; text-decoration: none; font-weight: 600;">Trottinettes</a>
                    <a href="{{ route('admin.reservations.index') }}" style="color: white; text-decoration: none; font-weight: 600;">Réservations</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" style="color: white; background: none; border: none; cursor: pointer; font-weight: 600;">Déconnexion</button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto px-4 py-8">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
                <h1 style="font-size: 2rem; font-weight: 800; color: var(--green-primary);">Réservation #{{ $reservation->id }}</h1>
                <a href="{{ route('admin.reservations.index') }}" style="
                    background: var(--green-primary);
                    color: white;
                    padding: 10px 20px;
                    border-radius: 6px;
                    text-decoration: none;
                    font-weight: 600;
                ">← Retour</a>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Reservation Details -->
            <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 32px; margin-bottom: 24px;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--green-primary); margin-bottom: 20px;">Informations de la Réservation</h2>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
                    <div>
                        <p style="font-size: 0.85rem; color: #666; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Client</p>
                        <p style="font-size: 1.1rem; font-weight: 600; color: #333;">{{ $reservation->guest_name ?? $reservation->user?->name ?? 'N/A' }}</p>
                        <p style="font-size: 0.9rem; color: #666;">{{ $reservation->user?->email ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p style="font-size: 0.85rem; color: #666; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Trottinette</p>
                        <p style="font-size: 1.1rem; font-weight: 600; color: #333;">{{ $reservation->scooter?->brand ?? 'N/A' }} {{ $reservation->scooter?->model ?? '' }}</p>
                        <p style="font-size: 0.9rem; color: #666;">No. {{ $reservation->scooter?->id ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p style="font-size: 0.85rem; color: #666; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Début de réservation</p>
                        <p style="font-size: 1.1rem; font-weight: 600; color: #333;">{{ $reservation->start_time->format('d/m/Y') }}</p>
                        <p style="font-size: 0.9rem; color: #666;">{{ $reservation->start_time->format('H:i') }}</p>
                    </div>

                    <div>
                        <p style="font-size: 0.85rem; color: #666; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Fin de réservation</p>
                        <p style="font-size: 1.1rem; font-weight: 600; color: #333;">{{ $reservation->end_time->format('d/m/Y') }}</p>
                        <p style="font-size: 0.9rem; color: #666;">{{ $reservation->end_time->format('H:i') }}</p>
                    </div>

                    <div>
                        <p style="font-size: 0.85rem; color: #666; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Statut de la réservation</p>
                        <span style="
                            display: inline-block;
                            padding: 8px 16px;
                            border-radius: 20px;
                            font-weight: 600;
                            @if ($reservation->status === 'pending')
                                background: #fff3cd;
                                color: #856404;
                            @elseif ($reservation->status === 'active')
                                background: #d1ecf1;
                                color: #0c5460;
                            @elseif ($reservation->status === 'completed')
                                background: #d4edda;
                                color: #155724;
                            @elseif ($reservation->status === 'cancelled')
                                background: #f8d7da;
                                color: #721c24;
                            @endif
                        ">
                            {{ ucfirst($reservation->status) }}
                        </span>
                    </div>

                    <div>
                        <p style="font-size: 0.85rem; color: #666; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Prix total</p>
                        <p style="font-size: 1.5rem; font-weight: 800; color: var(--green-primary);">{{ $reservation->total_price ? number_format($reservation->total_price, 2) . ' €' : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            @if ($payment)
                <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 32px; margin-bottom: 24px;">
                    <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--green-primary); margin-bottom: 20px;">Détails du Paiement</h2>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
                        <div>
                            <p style="font-size: 0.85rem; color: #666; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Statut du paiement</p>
                            <span style="
                                display: inline-block;
                                padding: 8px 16px;
                                border-radius: 20px;
                                font-weight: 600;
                                @if ($payment->status === 'pending')
                                    background: #fff3cd;
                                    color: #856404;
                                @elseif ($payment->status === 'completed')
                                    background: #d4edda;
                                    color: #155724;
                                @elseif ($payment->status === 'failed')
                                    background: #f8d7da;
                                    color: #721c24;
                                @elseif ($payment->status === 'refunded')
                                    background: #e7e7ff;
                                    color: #3f3fcc;
                                @endif
                            ">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>

                        <div>
                            <p style="font-size: 0.85rem; color: #666; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Montant payé</p>
                            <p style="font-size: 1.5rem; font-weight: 800; color: var(--green-primary);">{{ number_format($payment->amount, 2) }} €</p>
                        </div>

                        <div>
                            <p style="font-size: 0.85rem; color: #666; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Méthode de paiement</p>
                            <p style="font-size: 1.1rem; font-weight: 600; color: #333;">{{ ucfirst($payment->payment_method ?? 'N/A') }}</p>
                        </div>

                        <div>
                            <p style="font-size: 0.85rem; color: #666; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Date du paiement</p>
                            <p style="font-size: 1.1rem; font-weight: 600; color: #333;">{{ $payment->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if ($payment->status === 'completed')
                        <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                            <form method="POST" action="{{ route('admin.payments.refund', $payment) }}" style="display: inline;">
                                @csrf
                                <button type="submit" style="
                                    background: #dc3545;
                                    color: white;
                                    padding: 10px 20px;
                                    border-radius: 6px;
                                    border: none;
                                    cursor: pointer;
                                    font-weight: 600;
                                    transition: background 0.3s;
                                " onclick="return confirm('Êtes-vous sûr de vouloir rembourser?');">
                                    Rembourser
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Actions -->
            <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 32px;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--green-primary); margin-bottom: 20px;">Actions</h2>

                @if ($reservation->status !== 'completed' && $reservation->status !== 'cancelled')
                    <form method="POST" action="{{ route('admin.reservations.complete', $reservation) }}" style="display: inline;">
                        @csrf
                        <button type="submit" style="
                            background: var(--green-primary);
                            color: white;
                            padding: 10px 20px;
                            border-radius: 6px;
                            border: none;
                            cursor: pointer;
                            font-weight: 600;
                            margin-right: 12px;
                            transition: background 0.3s;
                        ">
                            Marquer comme complétée
                        </button>
                    </form>
                @endif

                <a href="{{ route('admin.reservations.index') }}" style="
                    display: inline-block;
                    background: #6c757d;
                    color: white;
                    padding: 10px 20px;
                    border-radius: 6px;
                    text-decoration: none;
                    font-weight: 600;
                    transition: background 0.3s;
                ">
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>
</body>
</html>
