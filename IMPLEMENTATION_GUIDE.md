# 🛴 ScooterRent - Electric Scooter Rental Platform

Une plateforme complète de location de trottinettes électriques construite avec **Laravel 10**, **Tailwind CSS**, et **Stripe**.

## 📋 Cahier des Charges

### Objectif
Développer une plateforme web moderne de location de trottinettes électriques, responsive et performante pour :
- Les utilisateurs clients : consulter, réserver, et payer une trottinette
- L'administrateur : gérer la plateforme via un dashboard avancé

### Technologies Utilisées
- **Backend** : Laravel 10 (MVC + API RESTful)
- **Base de données** : MySQL 8+
- **Frontend** : Blade + Tailwind CSS
- **Authentification** : Laravel Breeze
- **Paiement** : Stripe API
- **Architecture** : Modèles Eloquent, Controllers, Services, Migrations

---

## 🗄️ Architecture de la Base de Données

### Tables Créées

1. **users** - Gestion des utilisateurs
   - ID, Name, Email, Password, Phone, Role (admin/client), is_active, Timestamps

2. **scooters** - Gestion des trottinettes
   - ID, Name, Description, Price/Hour, Price/Day, Battery Level
   - Status (available/rented/maintenance), Max Speed, QR Code, Location, is_active, Timestamps

3. **scooter_images** - Images des trottinettes
   - ID, scooter_id (FK), image_path, alt_text, order, Timestamps

4. **reservations** - Gestion des réservations
   - ID, user_id (FK), scooter_id (FK), start_time, end_time
   - total_price, status, payment_status, delay_minutes, delay_fee, Timestamps

5. **payments** - Gestion des paiements
   - ID, reservation_id (FK), stripe_payment_id, amount
   - status, stripe_response, Timestamps

6. **promos** - Codes promo
   - ID, code, description, discount_percent, discount_amount
   - max_uses, used_count, valid_from, valid_until, is_active, Timestamps

7. **reviews** - Avis utilisateurs
   - ID, user_id (FK), scooter_id (FK), rating (1-5), comment, Timestamps

8. **audit_logs** - Logs d'audit
   - ID, user_id (FK), action, model, model_id, changes, ip_address, Timestamps

---

## 🚀 Installation et Configuration

### Prérequis
- PHP 8.1+
- MySQL 8+
- Composer

### Étapes d'Installation

```bash
# 1. Cloner ou accéder au projet
cd scooter-rental

# 2. Installer les dépendances
composer install

# 3. Configurer l'environnement
cp .env.example .env

# 4. Générer la clé d'application
php artisan key:generate

# 5. Configurer la base de données dans .env
DB_DATABASE=scooter_rental
DB_USERNAME=root
DB_PASSWORD=

# 6. Ajouter les clés Stripe dans .env
STRIPE_PUBLIC_KEY=pk_test_your_key
STRIPE_SECRET_KEY=sk_test_your_key

# 7. Exécuter les migrations
php artisan migrate

# 8. Seeder la base de données
php artisan db:seed

# 9. Démarrer le serveur
php artisan serve
```

Le site sera accessible à `http://localhost:8000`

---

## 👥 Rôles et Accès

### Client
- Inscription et connexion
- Consulter le catalogue de trottinettes
- Réserver une trottinette
- Payer via Stripe
- Voir l'historique des réservations
- Laisser des avis et notes

### Admin
- Dashboard avec statistiques en temps réel
- CRUD complet des trottinettes
- Gestion des réservations
- Gestion des paiements
- Gestion des utilisateurs
- Logs d'audit

---

## 🎯 Fonctionnalités Implémentées

### ✅ Core Features
- [x] Authentification client/admin
- [x] Gestion du catalogue de scooters
- [x] Upload d'images multiples
- [x] Système de réservation
- [x] Calcul automatique des prix
- [x] Paiement Stripe intégré
- [x] Dashboard admin complet
- [x] Système de codes promo
- [x] Avis et notations (1-5 étoiles)
- [x] Logs d'audit
- [x] API RESTful

### 🔄 Routes Principales

#### Routes Publiques
- `GET /` - Accueil
- `GET /scooters` - Liste des trottinettes
- `GET /scooters/{id}` - Détails d'une trottinette

#### Routes Client (Authentifié)
- `GET /reservations` - Mes réservations
- `GET /scooters/{id}/reserve` - Créer une réservation
- `POST /reservations` - Enregistrer une réservation
- `GET /reservations/{id}` - Voir une réservation
- `POST /reservations/{id}/cancel` - Annuler une réservation
- `GET /reservations/{id}/payment` - Page de paiement
- `POST /payments` - Traiter le paiement

#### Routes Admin
- `GET /admin/dashboard` - Dashboard
- `GET /admin/scooters` - Gestion des trottinettes
- `GET /admin/scooters/create` - Créer une trottinette
- `POST /admin/scooters` - Enregistrer une trottinette
- `GET /admin/scooters/{id}/edit` - Éditer une trottinette
- `PUT /admin/scooters/{id}` - Mettre à jour une trottinette
- `DELETE /admin/scooters/{id}` - Supprimer une trottinette
- `GET /admin/reservations` - Gestion des réservations
- `GET /admin/reservations/{id}` - Voir une réservation
- `POST /admin/reservations/{id}/complete` - Marquer comme complétée

#### API Routes
- `GET /api/scooters` - Lister toutes les trottinettes (JSON)
- `GET /api/scooters/{id}` - Détails d'une trottinette (JSON)
- `POST /api/reservations/check-availability` - Vérifier disponibilité

---

## 💳 Intégration Stripe

### Configuration
1. Créer un compte sur [Stripe.com](https://stripe.com)
2. Récupérer vos clés d'API (test ou production)
3. Ajouter dans `.env` :
```
STRIPE_PUBLIC_KEY=pk_test_...
STRIPE_SECRET_KEY=sk_test_...
```

### Flux de Paiement
1. Client réserve une trottinette
2. Prix calculé automatiquement
3. Client confirmé vers page de paiement
4. Tokenization Stripe côté client
5. Traitement du paiement via API Stripe
6. Confirmation et création de la réservation
7. Email de confirmation

---

## 👨‍💼 Comptes de Test

### Admin
- Email: `admin@scooter.com`
- Password: `password123`

### Client (généré automatiquement)
- 10 utilisateurs clients créés via seeders
- Passwords: `password`

---

## 📊 Dashboard Admin - Métriques

- **Total de Trottinettes** - Nombre total et disponibles
- **Total de Réservations** - Nombre total et complétées
- **Revenu Total** - Tous les paiements complétés
- **Utilisateurs Actifs** - Clients enregistrés
- **Taux d'Occupation** - Pourcentage d'utilisation
- **Trottinettes les Plus Louées** - Top 5
- **Revenus Mensuels** - Tendances
- **Dernières Réservations** - Logs récents

---

## 🔒 Sécurité

- Protection CSRF sur tous les formulaires
- Hash des mots de passe avec bcrypt
- Middleware d'authentification
- Middleware d'autorisation (Admin)
- Validation côté serveur obligatoire
- Sanitisation des inputs
- Gestion des permissions par rôle
- Logs d'audit de toutes les actions
- HTTPS recommandé en production

---

## 📈 Améliorations Futures

- [ ] Système de QR Code pour démarrer la location
- [ ] Système de pénalité en cas de retard
- [ ] PWA (Progressive Web App)
- [ ] Notifications SMS
- [ ] Cache Redis
- [ ] File d'attente Laravel Queue
- [ ] Tests unitaires et d'intégration
- [ ] Système de localisation GPS
- [ ] Support multi-devises
- [ ] Dashboard analytics avancé
- [ ] Système de réclamations
- [ ] Maintenance programmée

---

## 📁 Structure des Fichiers

```
scooter-rental/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ScooterController.php
│   │   │   ├── ReservationController.php
│   │   │   ├── PaymentController.php
│   │   │   └── Admin/
│   │   │       ├── DashboardController.php
│   │   │       ├── AdminScooterController.php
│   │   │       └── AdminReservationController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Scooter.php
│       ├── ScooterImage.php
│       ├── Reservation.php
│       ├── Payment.php
│       ├── Promo.php
│       ├── Review.php
│       └── AuditLog.php
├── database/
│   ├── migrations/
│   │   ├── 2024_02_16_000001_create_users_table.php
│   │   ├── 2024_02_16_000002_create_scooters_table.php
│   │   ├── 2024_02_16_000003_create_scooter_images_table.php
│   │   ├── 2024_02_16_000004_create_reservations_table.php
│   │   ├── 2024_02_16_000005_create_payments_table.php
│   │   ├── 2024_02_16_000006_create_promos_table.php
│   │   ├── 2024_02_16_000007_create_reviews_table.php
│   │   └── 2024_02_16_000008_create_audit_logs_table.php
│   └── seeders/
│       ├── UserSeeder.php
│       ├── ScooterSeeder.php
│       ├── PromoSeeder.php
│       └── DatabaseSeeder.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── welcome.blade.php
│       ├── scooters/
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       ├── reservations/
│       │   ├── index.blade.php
│       │   └── create.blade.php
│       ├── payments/
│       │   └── show.blade.php
│       └── admin/
│           ├── dashboard.blade.php
│           └── scooters/
│               ├── index.blade.php
│               ├── create.blade.php
│               └── edit.blade.php
├── routes/
│   ├── web.php
│   ├── api.php
│   └── auth.php
└── config/
    ├── services.php
    └── database.php
```

---

## 🐛 Dépannage

### Erreur de migration
```bash
# Réinitialiser la base de données
php artisan migrate:reset
php artisan migrate
php artisan db:seed
```

### Permissions sur storage
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Clé d'application manquante
```bash
php artisan key:generate
```

---

## 📞 Support

Pour toute question ou problème, consultez la documentation Laravel officielle:
- https://laravel.com/docs/10.x
- https://stripe.com/docs

---

## 📄 Licence

Proprietary - Tous droits réservés © 2026 ScooterRent

---

**Projet complété** ✅ - Prêt pour le déploiement et les tests!
