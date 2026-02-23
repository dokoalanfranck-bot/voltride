<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation Confirmée</title>
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
            max-width: 600px;
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
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .section {
            margin: 30px 0;
            padding: 20px;
            background: #f0fdf4;
            border-left: 4px solid #0a9b3a;
            border-radius: 8px;
        }
        .section h2 {
            color: #0a9b3a;
            margin-top: 0;
            font-size: 18px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid rgba(10, 155, 58, 0.2);
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label {
            color: #4a5568;
            font-weight: 600;
        }
        .value {
            color: #0f172a;
            font-weight: 700;
        }
        .highlight {
            color: #0a9b3a;
            font-weight: 700;
        }
        .scooter-info {
            background: white;
            border: 2px solid #e2e8f0;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .scooter-name {
            font-size: 20px;
            font-weight: 700;
            color: #0a9b3a;
            margin-bottom: 5px;
        }
        .address {
            color: #4a5568;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .battery {
            color: #10b981;
            font-weight: 600;
        }
        .price-section {
            background: linear-gradient(135deg, #47F55B 0%, #07d65d 100%);
            color: #0f172a;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .total-price {
            font-size: 36px;
            font-weight: 700;
            margin: 10px 0;
        }
        .info-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            color: #92400e;
        }
        .info-box strong {
            display: block;
            margin-bottom: 8px;
        }
        .footer {
            background: #f9fafb;
            padding: 30px 20px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #6b7280;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #0a9b3a;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            transition: background 0.3s;
        }
        .button:hover {
            background: #08823c;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>✅ Réservation Confirmée</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                <p>Bonjour <strong>{{ $clientName }}</strong>,</p>
                <p>Votre réservation a été créée avec succès! Retrouvez tous les détails ci-dessous.</p>
            </div>

            <!-- Réservation Details -->
            <div class="section">
                <h2>📋 Détails de votre réservation</h2>
                
                <div class="info-row">
                    <span class="label">Numéro de réservation</span>
                    <span class="value">#{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</span>
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
                    <span class="label">Durée</span>
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
                    <span class="value">
                        @if($reservation->status === 'pending')
                            <span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 12px;">En attente</span>
                        @elseif($reservation->status === 'confirmed')
                            <span style="background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 4px; font-size: 12px;">✓ Confirmée</span>
                        @endif
                    </span>
                </div>
            </div>

            <!-- Scooter Info -->
            <div class="section">
                <h2>⚡ Votre trottinette</h2>
                
                <div class="scooter-info">
                    <div class="scooter-name">{{ $scooter->name }}</div>
                    <div class="address">📍 {{ $scooter->location }}</div>
                    <div>
                        <span style="color: #4a5568;">Description: </span>{{ Str::limit($scooter->description, 100) }}
                    </div>
                    <div style="margin-top: 10px;">
                        <span class="battery">🔋 Batterie: {{ $scooter->battery_level }}%</span>
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="section">
                <h2>💰 Tarification</h2>
                
                <div class="info-row">
                    <span class="label">Prix à l'heure</span>
                    <span class="value">{{ number_format($scooter->price_hour, 2) }}€</span>
                </div>
                
                <div class="info-row">
                    <span class="label">Prix à la minute</span>
                    <span class="value">{{ number_format($scooter->price_minute, 2) }}€</span>
                </div>

                <div class="price-section">
                    <div style="font-size: 14px; color: rgba(15, 23, 42, 0.7);">Prix Total Estimé</div>
                    <div class="total-price">{{ number_format($reservation->total_price, 2) }}€</div>
                    <div style="font-size: 13px; color: rgba(15, 23, 42, 0.7);">Paiement sur place</div>
                </div>
            </div>

            <!-- Important Info -->
            <div class="info-box">
                <strong>💡 À savoir avant votre location</strong>
                <ul style="margin: 8px 0; padding-left: 20px;">
                    <li>Paiement sur place en espèces ou par carte</li>
                    <li>Identité et caution 50€ requises</li>
                    <li>Assurance incluse dans votre réservation</li>
                    <li>Vous êtes responsable de la trottinette</li>
                    <li>Veuillez respecter le code de la route</li>
                </ul>
            </div>

            <!-- CTA -->
            <center>
                <a href="{{ url('/reservations/' . $reservation->id) }}" class="button">
                    → Voir les détails complets
                </a>
            </center>

            <!-- Contact Info -->
            <div style="margin-top: 30px; padding: 15px; background: #f3f4f6; border-radius: 8px; text-align: center; font-size: 13px; color: #6b7280;">
                <p style="margin: 5px 0;"><strong>Besoin d'aide?</strong></p>
                <p style="margin: 5px 0;">📞 Téléphone: +33 1 23 45 67 89</p>
                <p style="margin: 0;">📧 Email: support@voltride.fr</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0;">© 2024 VoltRide - Location de trottinettes électriques</p>
            <p style="margin: 5px 0 0 0; font-size: 12px;">Email généré automatiquement, merci de ne pas y répondre</p>
        </div>
    </div>
</body>
</html>
