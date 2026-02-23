# 📧 Système d'Emails VoltRide

## 📋 Vue d'ensemble

Le système d'emails VoltRide envoie automatiquement des notifications lors de chaque réservation:
- **Email client**: Confirmation de réservation avec détails
- **Email admin**: Alerte de nouvelle réservation avec info client

---

## 🎯 Fonctionnalités

### Emails Automatiques

| Événement | Destinataire | Template | Contenu |
|-----------|--------------|----------|---------|
| **Nouvelle réservation** | Client | `reservation-client.blade.php` | Confirmation, détails réservation, infos trottinette |
| **Nouvelle réservation** | Admin(s) | `reservation-admin.blade.php` | Alert, infos client, détails réservation, actions |

---

## 🛠️ Configuration

### 1. Configuration SMTP (.env)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@voltride.com
MAIL_FROM_NAME="VoltRide"
```

### Options SMTP Populaires

#### Gmail
```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
```
**Note**: Créer un [mot de passe d'application](https://support.google.com/accounts/answer/185833)

#### SendGrid
```env
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=votre-api-key-sendgrid
```

#### Mailgun
```env
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@votre-domaine.mailgun.org
MAIL_PASSWORD=votre-password-mailgun
```

#### Mailtrap (Testing)
```env
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre-username-mailtrap
MAIL_PASSWORD=votre-password-mailtrap
```

### 2. Test Configuration

```bash
php artisan tinker
```

```php
Mail::raw('Test email', function($message) {
    $message->to('votre-email@gmail.com')->subject('Test VoltRide');
});
```

Si vous recevez l'email → Configuration OK ✅

---

## 📁 Structure des Fichiers

```
app/
├── Mail/
│   ├── ReservationConfirmationClient.php    # Mailable client
│   └── ReservationNotificationAdmin.php     # Mailable admin

app/Http/Controllers/
└── ReservationController.php                # Envoi emails (store method)

resources/views/emails/
├── reservation-client.blade.php             # Template email client
└── reservation-admin.blade.php              # Template email admin
```

---

## 💻 Code

### 1. Mailable Client

**Fichier**: `app/Mail/ReservationConfirmationClient.php`

```php
<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationConfirmationClient extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre réservation VoltRide',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-client',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
```

### 2. Mailable Admin

**Fichier**: `app/Mail/ReservationNotificationAdmin.php`

```php
<?php

namespace App\Mail;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationNotificationAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $client;

    public function __construct(Reservation $reservation, User $client)
    {
        $this->reservation = $reservation;
        $this->client = $client;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle réservation VoltRide',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-admin',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
```

### 3. Controller (Envoi Emails)

**Fichier**: `app/Http/Controllers/ReservationController.php`

```php
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmationClient;
use App\Mail\ReservationNotificationAdmin;
use App\Models\User;

public function store(Request $request)
{
    // Validation...
    
    // Création réservation
    $reservation = Reservation::create([
        'user_id' => auth()->id(),
        'scooter_id' => $request->scooter_id,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'total_price' => $totalPrice,
        'status' => 'pending',
    ]);

    // Charger les relations pour emails
    $reservation->load(['scooter', 'user']);

    try {
        // 1. Email au client
        Mail::to($reservation->user->email)
            ->send(new ReservationConfirmationClient($reservation));

        // 2. Email aux admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)
                ->send(new ReservationNotificationAdmin($reservation, $reservation->user));
        }
    } catch (\Exception $e) {
        \Log::error('Erreur envoi email: ' . $e->getMessage());
        // Continuer même si email échoue
    }

    return redirect()->route('reservations.show', $reservation)
        ->with('success', 'Réservation créée avec succès!');
}
```

---

## 🎨 Templates Emails

### Template Client

**Fichier**: `resources/views/emails/reservation-client.blade.php`

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation Réservation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0fdf4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #47F55B 0%, #07d65d 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            color: white;
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 40px 30px;
        }
        .info-box {
            background: #f0fdf4;
            border-left: 4px solid #07d65d;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
        }
        .label {
            font-weight: 600;
            color: #0f172a;
        }
        .value {
            color: #4a5568;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #47F55B 0%, #07d65d 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
        }
        .footer {
            background: #0f172a;
            color: white;
            text-align: center;
            padding: 30px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>✅ Réservation Confirmée!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Bonjour <strong>{{ $reservation->user->name }}</strong>,</p>
            
            <p>Votre réservation a bien été enregistrée. Voici les détails:</p>

            <!-- Infos Réservation -->
            <div class="info-box">
                <h3 style="margin-top:0; color:#07d65d;">📋 Détails de la Réservation</h3>
                <div class="info-row">
                    <span class="label">Numéro:</span>
                    <span class="value">#{{ $reservation->id }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Date de début:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Date de fin:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Prix total:</span>
                    <span class="value"><strong>{{ number_format($reservation->total_price, 2) }} €</strong></span>
                </div>
            </div>

            <!-- Infos Trottinette -->
            <div class="info-box">
                <h3 style="margin-top:0; color:#07d65d;">🛴 Votre Trottinette</h3>
                <div class="info-row">
                    <span class="label">Modèle:</span>
                    <span class="value">{{ $reservation->scooter->model }}</span>
                </div>
                @if($reservation->scooter->battery_level)
                <div class="info-row">
                    <span class="label">Batterie:</span>
                    <span class="value">{{ $reservation->scooter->battery_level }}%</span>
                </div>
                @endif
            </div>

            <p>Nous vous attendons avec impatience! 🎉</p>

            <center>
                <a href="{{ route('reservations.show', $reservation->id) }}" class="button">
                    Voir ma réservation
                </a>
            </center>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>VoltRide</strong> - Location de trottinettes électriques</p>
            <p style="margin:5px 0; opacity:0.8;">
                Des questions? Contactez-nous: <a href="mailto:support@voltride.com" style="color:#47F55B;">support@voltride.com</a>
            </p>
        </div>
    </div>
</body>
</html>
```

### Template Admin

**Fichier**: `resources/views/emails/reservation-admin.blade.php`

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Réservation</title>
    <style>
        /* Styles similaires... */
        .alert {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🔔 Nouvelle Réservation</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="alert">
                ⚠️ <strong>Action requise:</strong> Une nouvelle réservation vient d'être enregistrée.
            </div>

            <!-- Infos Client -->
            <div class="info-box">
                <h3>👤 Informations Client</h3>
                <div class="info-row">
                    <span class="label">Nom:</span>
                    <span class="value">{{ $client->name }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Email:</span>
                    <span class="value">{{ $client->email }}</span>
                </div>
                @if($client->phone)
                <div class="info-row">
                    <span class="label">Téléphone:</span>
                    <span class="value">{{ $client->phone }}</span>
                </div>
                @endif
            </div>

            <!-- Infos Réservation -->
            <div class="info-box">
                <h3>📋 Détails Réservation</h3>
                <div class="info-row">
                    <span class="label">N° Réservation:</span>
                    <span class="value">#{{ $reservation->id }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Trottinette:</span>
                    <span class="value">{{ $reservation->scooter->model }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Période:</span>
                    <span class="value">
                        {{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m/Y') }} 
                        → 
                        {{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m/Y') }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">Montant:</span>
                    <span class="value"><strong>{{ number_format($reservation->total_price, 2) }} €</strong></span>
                </div>
                <div class="info-row">
                    <span class="label">Statut:</span>
                    <span class="value">
                        <span style="background:#ffc107; color:#000; padding:4px 8px; border-radius:4px;">
                            {{ ucfirst($reservation->status) }}
                        </span>
                    </span>
                </div>
            </div>

            <!-- Actions -->
            <h3>✅ Actions à effectuer:</h3>
            <ul>
                <li>Vérifier la disponibilité de la trottinette</li>
                <li>Valider la réservation</li>
                <li>Préparer le véhicule pour le {{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m/Y') }}</li>
            </ul>

            <center>
                <a href="{{ route('admin.reservations.show', $reservation->id) }}" class="button">
                    Gérer la réservation
                </a>
            </center>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>VoltRide Admin Panel</strong></p>
            <p style="opacity:0.8;">Cet email est automatique, ne pas répondre.</p>
        </div>
    </div>
</body>
</html>
```

---

## 🧪 Tests

### Test Manuel

1. Créer une réservation depuis l'interface
2. Vérifier réception emails (client + admin)
3. Valider contenu et formatage

### Test Programmatique

```bash
php artisan tinker
```

```php
$reservation = \App\Models\Reservation::first();
$reservation->load(['scooter', 'user']);

// Test email client
Mail::to('test@example.com')->send(new \App\Mail\ReservationConfirmationClient($reservation));

// Test email admin
Mail::to('admin@example.com')->send(new \App\Mail\ReservationNotificationAdmin($reservation, $reservation->user));
```

### Utiliser Mailtrap (Recommandé)

[Mailtrap.io](https://mailtrap.io) permet de tester emails sans envoyer à de vrais destinataires.

```env
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre-username
MAIL_PASSWORD=votre-password
```

---

## 🚀 Production

### Checklist Déploiement

- [ ] Configurer SMTP production (Gmail, SendGrid, etc.)
- [ ] Tester envoi email sur serveur production
- [ ] Vérifier `.env` production (pas Mailtrap!)
- [ ] Configurer domaine email (`MAIL_FROM_ADDRESS`)
- [ ] Tester avec emails réels
- [ ] Activer logs email (`config/mail.php`)
- [ ] Monitorer taux d'échec

### Optimisations

#### 1. Utiliser Queues (Recommandé)

**Modifier Mailables**:
```php
class ReservationConfirmationClient extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    // ...
}
```

**Configurer Queue**:
```env
QUEUE_CONNECTION=database
```

**Créer table jobs**:
```bash
php artisan queue:table
php artisan migrate
```

**Lancer worker**:
```bash
php artisan queue:work
```

#### 2. Rate Limiting

Éviter spam en limitant envois:

```php
use Illuminate\Support\Facades\RateLimiter;

RateLimiter::attempt(
    'send-email:' . auth()->id(),
    5, // Max 5 emails
    function() use ($reservation) {
        Mail::to($reservation->user->email)
            ->send(new ReservationConfirmationClient($reservation));
    },
    60 // Par minute
);
```

---

## 🐛 Troubleshooting

### Emails non reçus

**Vérifier**:
1. Configuration SMTP (`.env`)
2. Logs Laravel (`storage/logs/laravel.log`)
3. Spam folder
4. Credentials SMTP valides

**Commande debug**:
```bash
php artisan config:clear
php artisan cache:clear
```

### Erreur "Connection refused"

**Cause**: Port SMTP bloqué ou hôte invalide

**Solution**:
- Vérifier `MAIL_HOST` et `MAIL_PORT`
- Tester avec Mailtrap d'abord
- Vérifier firewall serveur

### Erreur "Authentication failed"

**Cause**: Identifiants SMTP incorrects

**Solution**:
- Regénérer mot de passe app (Gmail)
- Vérifier `MAIL_USERNAME` et `MAIL_PASSWORD`

---

## 📊 Statistiques Email

### Tracker les Envois

```php
// Dans ReservationController
\Log::info('Email envoyé', [
    'reservation_id' => $reservation->id,
    'recipient' => $reservation->user->email,
    'type' => 'confirmation_client'
]);
```

### Créer Table Logs (Optional)

```bash
php artisan make:migration create_email_logs_table
```

```php
Schema::create('email_logs', function (Blueprint $table) {
    $table->id();
    $table->string('type'); // confirmation_client, notification_admin
    $table->string('recipient');
    $table->foreignId('reservation_id')->constrained();
    $table->boolean('sent')->default(false);
    $table->timestamp('sent_at')->nullable();
    $table->timestamps();
});
```

---

## 📚 Ressources

- [Laravel Mail Docs](https://laravel.com/docs/mail)
- [Mailtrap](https://mailtrap.io)
- [SendGrid](https://sendgrid.com)
- [Mailgun](https://mailgun.com)

---

**Version**: 1.0.0  
**Dernière MAJ**: Février 2026  
**Status**: ✅ Production Ready
