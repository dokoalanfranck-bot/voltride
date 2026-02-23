<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Réservation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #0f172a;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #47F55B 0%, #07d65d 100%);
            color: #0f172a;
            padding: 40px 20px;
            text-align: center;
            border-bottom: 4px solid #0a9b3a;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 14px;
            color: rgba(15, 23, 42, 0.8);
        }
        .content {
            padding: 40px 30px;
        }
        .alert-box {
            background: #fee2e2;
            border-left: 4px solid #dc2626;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            color: #7f1d1d;
        }
        .alert-box strong {
            display: block;
            margin-bottom: 5px;
        }
        .section {
            margin: 25px 0;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
        }
        .section h2 {
            color: #1f2937;
            margin-top: 0;
            font-size: 18px;
            font-weight: 700;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 12px 0;
            padding: 8px 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label {
            color: #6b7280;
            font-weight: 600;
            flex-basis: 40%;
        }
        .value {
            color: #0f172a;
            font-weight: 700;
            text-align: right;
            flex-basis: 60%;
        }
        .highlight {
            color: #0a9b3a;
            font-weight: 700;
        }
        .scooter-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border: 2px solid #0a9b3a;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .scooter-name {
            font-size: 22px;
            font-weight: 700;
            color: #0a9b3a;
            margin-bottom: 8px;
        }
        .client-card {
            background: #f0f9ff;
            border: 2px solid #0284c7;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .client-name {
            font-size: 18px;
            font-weight: 700;
            color: #0284c7;
            margin-bottom: 12px;
        }
        .contact-item {
            margin: 8px 0;
            padding: 8px 0;
            border-bottom: 1px solid rgba(2, 132, 199, 0.2);
        }
        .contact-item:last-child {
            border-bottom: none;
        }
        .contact-label {
            color: #6b7280;
            font-size: 13px;
            font-weight: 600;
        }
        .contact-value {
            color: #0f172a;
            font-weight: 600;
            word-break: break-all;
        }
        .price-highlight {
            background: linear-gradient(135deg, #47F55B 0%, #07d65d 100%);
            color: #0f172a;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
            text-align: center;
            font-weight: 700;
        }
        .total-price {
            font-size: 32px;
            margin: 10px 0;
        }
        .price-label {
            font-size: 13px;
            color: rgba(15, 23, 42, 0.7);
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #0a9b3a;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px auto;
            transition: background 0.3s;
            text-align: center;
        }
        .button:hover {
            background: #08823c;
        }
        .footer {
            background: #f9fafb;
            padding: 30px 20px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            font-size: 13px;
            color: #6b7280;
        }
        .status-badge {
            display: inline-block;
            background: #fef3c7;
            color: #92400e;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }
        .action-needed {
            background: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .action-needed strong {
            color: #7f1d1d;
            display: block;
            margin-bottom: 8px;
        }
        .action-needed ul {
            margin: 8px 0;
            padding-left: 20px;
            color: #7f1d1d;
        }
        .action-needed li {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🚨 Nouvelle Réservation</h1>
            <p>Une nouvelle location est en attente de validation</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Alert -->
            <div class="alert-box">
                <strong>⚠️ À traiter en priorité</strong>
                Une nouvelle réservation requiert votre attention. Vérifiez les détails et confirmez si nécessaire.
            </div>

            <!-- Réservation Basics -->
            <div class="section">
                <h2>📋 Informations de Réservation</h2>
                
                <div class="info-row">
                    <span class="label">Numéro</span>
                    <span class="value highlight">#{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">Date</span>
                    <span class="value">{{ $reservation->start_time->format('d/m/Y') }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">Heure de démarrage</span>
                    <span class="value">{{ $reservation->start_time->format('H:i') }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">Heure de fin</span>
                    <span class="value">{{ $reservation->end_time->format('H:i') }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">Durée totale</span>
                    <span class="value">
                        @php
                            $minutes = $reservation->start_time->diffInMinutes($reservation->end_time);
                            $hours = intval($minutes / 60);
                            $mins = $minutes % 60;
                        @endphp
                        @if($hours > 0){{ $hours }}h @endif{{ $mins }}min
                    </span>
                </div>

                <div class="info-row">
                    <span class="label">Statut</span>
                    <span class="value"><span class="status-badge">En attente</span></span>
                </div>
            </div>

            <!-- Scooter Info -->
            <div class="scooter-card">
                <h2 style="color: #0a9b3a; margin-top: 0; font-size: 18px;">⚡ Trottinette</h2>
                <div class="scooter-name">{{ $scooter->name }}</div>
                
                <div style="margin-top: 12px;">
                    <div class="info-row">
                        <span class="label">Localisation</span>
                        <span class="value">{{ $scooter->location }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Batterie</span>
                        <span class="value" style="color: #10b981;">{{ $scooter->battery_level }}%</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Disponibilité</span>
                        <span class="value" style="color: #10b981;">✓ Disponible</span>
                    </div>
                </div>
            </div>

            <!-- Client Info -->
            <div class="client-card">
                <h2 style="color: #0284c7; margin-top: 0; font-size: 18px;">👤 Client</h2>
                <div class="client-name">{{ $clientName }}</div>
                
                <div class="contact-item">
                    <div class="contact-label">📧 Email</div>
                    <div class="contact-value">{{ $clientEmail }}</div>
                </div>
                
                <div class="contact-item">
                    <div class="contact-label">📱 Téléphone</div>
                    <div class="contact-value">{{ $clientPhone }}</div>
                </div>

                @if($user)
                <div class="contact-item">
                    <div class="contact-label">👤 Type client</div>
                    <div class="contact-value">Utilisateur enregistré</div>
                </div>
                @else
                <div class="contact-item">
                    <div class="contact-label">👤 Type client</div>
                    <div class="contact-value">Client occasionnel</div>
                </div>
                @endif
            </div>

            <!-- Pricing -->
            <div style="margin: 25px 0;">
                <h2 style="color: #1f2937; font-size: 18px; font-weight: 700;">💰 Détails de Tarification</h2>
                
                <div style="background: #f9fafb; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                    <div class="info-row">
                        <span class="label">Prix/heure</span>
                        <span class="value">{{ number_format($scooter->price_hour, 2) }}€</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Prix/minute</span>
                        <span class="value">{{ number_format($scooter->price_minute, 2) }}€</span>
                    </div>
                </div>

                <div class="price-highlight">
                    <div class="price-label">Montant Total Estimé</div>
                        <div class="total-price">{{ number_format($reservation->total_price, 2) }}$</div>
                    <div class="price-label">Paiement sur place</div>
                </div>
            </div>

            <!-- Actions Required -->
            <div class="action-needed">
                <strong>✓ Actions à effectuer</strong>
                <ul style="font-size: 14px;">
                    <li>Vérifier les informations du client</li>
                    <li>Confirmer que la trottinette est disponible</li>
                    <li>Vérifier que le paiement sera effectué sur place</li>
                    <li>Marquer comme confirmée quand le client arrive</li>
                </ul>
            </div>

            <!-- CTA -->
            <center>
                <a href="{{ config('app.url') }}/admin/reservations" class="button">
                    → Gérer les réservations
                </a>
            </center>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 10px 0;"><strong>VoltRide - Panel Admin</strong></p>
            <p style="margin: 0 0 5px 0;">Cette notification a été générée automatiquement</p>
            <p style="margin: 0; font-size: 12px;">© 2024 VoltRide - Location de trottinettes électriques</p>
        </div>
    </div>
</body>
</html>
