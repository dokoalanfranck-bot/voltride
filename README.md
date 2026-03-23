# 🛴 VoltRide - Location de Trottinettes Électriques

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel 11">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php" alt="PHP 8.2">
  <img src="https://img.shields.io/badge/Status-Production%20Ready-success?style=for-the-badge" alt="Status">
</p>

## 📋 À Propos

VoltRide est une application web moderne de location de trottinettes électriques, offrant une expérience utilisateur premium avec un design responsive et une interface administrative complète.

### ✨ Fonctionnalités Principales

- 🛴 **Gestion Trottinettes**: CRUD complet avec upload d'images multiples
- 📅 **Système de Réservations**: Booking fluide avec calcul automatique des prix
- 💳 **Paiements Stripe**: Intégration sécurisée pour paiements en ligne
- 📧 **Emails Automatiques**: Notifications client et admin lors des réservations
- 👨‍💼 **Panel Admin**: Dashboard avec statistiques et gestion complète
- 🎨 **Design Premium**: Interface moderne avec gradient vert fluo vibrant
- 📱 **Responsive**: Optimisé mobile, tablette et desktop (WCAG AA+)
- 🔒 **Sécurité**: Authentification, autorisations (Policies), protection CSRF

---

## 🚀 Installation Rapide (5 min)

### Prérequis

- PHP 8.2+
- Composer 2.x
- MySQL 8.0+
- Node.js 20+
- NPM/Yarn

### Étapes

```bash
# 1. Cloner le projet
git clone https://github.com/votre-compte/voltride.git
cd voltride

# 2. Installer dépendances
composer install
npm install

# 3. Configuration
cp .env.example .env
php artisan key:generate

# 4. Configurer .env
# Éditer .env avec vos credentials MySQL et SMTP

# 5. Database
php artisan migrate --seed

# 6. Compiler assets
npm run build

# 7. Lancer serveur
php artisan serve
```

**Accès**: http://localhost:8000

**Compte admin par défaut**:
- Email: `admin@voltride.com`
- Password: `admin123`

---

## 📚 Documentation

Toute la documentation est disponible dans le dossier [`/docs`](./docs):

- **[Guide Complet](./docs/README.md)** - Documentation principale
- **[Système de Couleurs](./docs/03-color-system.md)** - Palette et variables CSS
- **[Système d'Emails](./docs/05-email-system.md)** - Configuration SMTP et templates
- **[Guide de Déploiement](./docs/10-deployment.md)** - Mise en production

---

## 🎨 Design

### Identité Visuelle

- **Gradient Principal**: `linear-gradient(135deg, #47F55B 0%, #07d65d 100%)`
- **Système CSS Variables**: 18 variables personnalisées
- **Responsive**: Breakpoints à 480px, 768px, 1024px
- **Accessibilité**: WCAG AA+ validé

### Technologies Frontend

- Blade Templates (Laravel)
- CSS3 avec Variables
- JavaScript Vanilla
- Vite (Build tool)

---

## 💻 Stack Technique

| Couche | Technologies |
|--------|-------------|
| **Backend** | Laravel 11, PHP 8.2 |
| **Database** | MySQL 8.0, Eloquent ORM |
| **Frontend** | Blade, CSS3, JavaScript |
| **Build** | Vite, NPM |
| **Email** | Laravel Mail, SMTP |
| **Paiement** | Stripe API |
| **Auth** | Laravel Breeze |
| **Storage** | Local/S3 (images) |

---

## 📂 Structure Projet

```
voltride/
├── app/
│   ├── Http/Controllers/     # Contrôleurs (Scooters, Reservations, Payments)
│   ├── Models/               # Models Eloquent (User, Scooter, Reservation)
│   ├── Policies/             # Authorization Policies
│   └── Mail/                 # Mailable Classes (emails)
├── resources/
│   ├── views/                # Templates Blade
│   │   ├── admin/            # Panel administrateur
│   │   ├── auth/             # Pages authentification
│   │   ├── emails/           # Templates emails
│   │   ├── scooters/         # Pages trottinettes
│   │   └── reservations/     # Pages réservations
│   ├── css/                  # Styles personnalisés
│   └── js/                   # JavaScript
├── routes/
│   ├── web.php               # Routes web
│   └── api.php               # Routes API
├── database/
│   ├── migrations/           # Migrations DB
│   └── seeders/              # Seeders
├── docs/                     # 📚 Documentation complète
└── public/                   # Assets publics
```

---

## 🔐 Sécurité

- ✅ **Authentification**: Laravel Breeze (2FA disponible)
- ✅ **Autorisations**: Policies sur Reservations & Payments
- ✅ **CSRF Protection**: Sur toutes les forms
- ✅ **SQL Injection**: Protection via Eloquent ORM
- ✅ **XSS Protection**: Blade escaping automatique `{{ }}`
- ✅ **Rate Limiting**: 60 requêtes/minute
- ✅ **Passwords**: Hashage bcrypt/argon2

---

## 🧪 Tests

```bash
# Tests unitaires
php artisan test

# Tests feature
php artisan test --testsuite=Feature

# Coverage
php artisan test --coverage
```

---

## 🚀 Déploiement

Voir le [Guide de Déploiement Complet](./docs/10-deployment.md)

### Checklist Production

- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] SSL/HTTPS configuré
- [ ] Cache activé (`config:cache`, `route:cache`)
- [ ] Assets compilés (`npm run build`)
- [ ] SMTP production configuré
- [ ] Backups database automatisés

---

## 📧 Configuration Emails

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

Voir [Documentation Emails](./docs/05-email-system.md) pour configuration complète.

---

## 💳 Configuration Stripe

```env
STRIPE_KEY=pk_test_VOTRE_CLE_PUBLIQUE
STRIPE_SECRET=sk_test_VOTRE_CLE_SECRETE
```

**Mode Production**: Remplacer `pk_test_` et `sk_test_` par clés live.

---

## 📊 Fonctionnalités Avancées

- **Audit Logs**: Traçabilité complète des actions admin
- **Multilingual**: Support français (extensible)
- **Google Maps**: Localisation des trottinettes (optionnel)
- **Analytics**: Statistiques détaillées dans le dashboard admin
- **Reviews**: Système d'avis clients sur les locations

---

## 🤝 Contribution

Les contributions sont les bienvenues!

1. Fork le projet
2. Créer une branche (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

---

## 📝 License

Ce projet est sous licence MIT. Voir [LICENSE](LICENSE) pour plus de détails.

---

## 📞 Support

- **Email**: support@voltride.com
- **Documentation**: [docs/README.md](./docs/README.md)
- **Issues**: [GitHub Issues](https://github.com/votre-compte/voltride/issues)

---

## 🎓 Crédits

- **Framework**: [Laravel](https://laravel.com)
- **Icons**: Font Awesome
- **Fonts**: Google Fonts (Inter, Poppins)

---


<p align="center">
  <strong>Version 1.0.0</strong> | Production Ready ✅
</p>

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
