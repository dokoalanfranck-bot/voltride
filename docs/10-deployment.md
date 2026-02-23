# 🚀 Guide de Déploiement VoltRide

## 📋 Checklist Pré-Déploiement

### ✅ Code Quality

- [ ] Tous les fichiers de test supprimés
- [ ] Documentation organisée dans `/docs`
- [ ] Pas de `dd()`, `dump()`, `var_dump()` dans le code
- [ ] Commentaires inutiles supprimés
- [ ] Code formatté et indenté correctement
- [ ] Variables d'environnement sensibles dans `.env` uniquement

### ✅ Sécurité

- [ ] `APP_DEBUG=false` en production
- [ ] `APP_ENV=production`
- [ ] CSRF protection active sur toutes les forms
- [ ] Validation sur tous les inputs
- [ ] SQL injection protection (Eloquent ORM)
- [ ] XSS protection (Blade `{{ }}` escaping)
- [ ] Rate limiting configuré
- [ ] Passwords hashés (bcrypt/argon2)

### ✅ Performance

- [ ] Config cache: `php artisan config:cache`
- [ ] Route cache: `php artisan route:cache`
- [ ] View cache: `php artisan view:cache`
- [ ] Assets compilés: `npm run build`
- [ ] Images optimisées
- [ ] Eager loading pour éviter N+1 queries
- [ ] Database indexes optimisés

### ✅ Base de Données

- [ ] Migrations testées
- [ ] Seeders fonctionnels (si nécessaire)
- [ ] Backups configurés
- [ ] Relations Eloquent optimisées

### ✅ Email

- [ ] Configuration SMTP production
- [ ] Templates emails testés
- [ ] Queue configurée (recommandé)
- [ ] Email FROM correct

### ✅ Frontend

- [ ] Responsive design testé (mobile, tablet, desktop)
- [ ] Compatibilité navigateurs (Chrome, Firefox, Safari, Edge)
- [ ] Accessibilité WCAG AA
- [ ] Images lazy loading
- [ ] Polices optimisées

---

## 🛠️ Configuration Production

### 1. Fichier `.env.production`

Créer un `.env` pour production:

```env
# Application
APP_NAME=VoltRide
APP_ENV=production
APP_KEY=base64:VOTRE_CLE_GENEREE
APP_DEBUG=false
APP_URL=https://votredomaine.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=voltride_production
DB_USERNAME=voltride_user
DB_PASSWORD=mot-de-passe-securise

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=votre-api-key-sendgrid
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@votredomaine.com
MAIL_FROM_NAME="${APP_NAME}"

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis (si utilisé)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Stripe (paiements)
STRIPE_KEY=pk_live_VOTRE_CLE_PUBLIQUE
STRIPE_SECRET=sk_live_VOTRE_CLE_SECRETE

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error
```

### 2. Générer Clé Application

```bash
php artisan key:generate
```

⚠️ **Important**: Ne jamais partager `APP_KEY`!

---

## 📦 Déploiement Étape par Étape

### Option 1: Serveur VPS (DigitalOcean, Linode, etc.)

#### Étape 1: Prérequis Serveur

```bash
# Mettre à jour système
sudo apt update && sudo apt upgrade -y

# Installer dépendances
sudo apt install -y nginx mysql-server php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip composer git unzip
```

#### Étape 2: Configurer MySQL

```bash
sudo mysql_secure_installation

# Créer database et utilisateur
sudo mysql -u root -p
```

```sql
CREATE DATABASE voltride_production;
CREATE USER 'voltride_user'@'localhost' IDENTIFIED BY 'mot-de-passe-securise';
GRANT ALL PRIVILEGES ON voltride_production.* TO 'voltride_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Étape 3: Cloner le Projet

```bash
cd /var/www
sudo git clone https://github.com/votre-compte/voltride.git
cd voltride
```

#### Étape 4: Installation Laravel

```bash
# Installer dépendances
composer install --optimize-autoloader --no-dev

# Copier .env
cp .env.production .env

# Éditer .env avec vos valeurs
nano .env

# Générer clé
php artisan key:generate

# Permissions
sudo chown -R www-data:www-data /var/www/voltride
sudo chmod -R 755 /var/www/voltride
sudo chmod -R 775 /var/www/voltride/storage
sudo chmod -R 775 /var/www/voltride/bootstrap/cache

# Migrations
php artisan migrate --force

# Seeders (si nécessaire)
php artisan db:seed --force

# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Étape 5: Configurer Nginx

```bash
sudo nano /etc/nginx/sites-available/voltride
```

```nginx
server {
    listen 80;
    server_name votredomaine.com www.votredomaine.com;
    root /var/www/voltride/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Activer site
sudo ln -s /etc/nginx/sites-available/voltride /etc/nginx/sites-enabled/

# Tester config
sudo nginx -t

# Redémarrer Nginx
sudo systemctl restart nginx
```

#### Étape 6: SSL avec Let's Encrypt

```bash
# Installer Certbot
sudo apt install -y certbot python3-certbot-nginx

# Obtenir certificat SSL
sudo certbot --nginx -d votredomaine.com -d www.votredomaine.com

# Renouvellement auto configuré automatiquement
```

#### Étape 7: Build Assets Frontend

```bash
# Installer Node.js
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Build assets
cd /var/www/voltride
npm install
npm run build
```

#### Étape 8: Configurer Queue Worker (Optionnel mais Recommandé)

```bash
# Créer service Supervisor
sudo apt install -y supervisor

sudo nano /etc/supervisor/conf.d/voltride-worker.conf
```

```ini
[program:voltride-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/voltride/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/voltride/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# Activer
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start voltride-worker:*
```

#### Étape 9: Configurer Cron (Tasks Schedulées)

```bash
sudo crontab -e
```

Ajouter:
```
* * * * * cd /var/www/voltride && php artisan schedule:run >> /dev/null 2>&1
```

---

### Option 2: Hébergement Mutualisé (cPanel)

#### Étape 1: Préparer Fichiers Localement

```bash
# Build assets
npm run build

# Créer archive
zip -r voltride.zip . -x "node_modules/*" ".git/*" "storage/logs/*"
```

#### Étape 2: Upload via cPanel

1. Accéder cPanel
2. File Manager → `public_html`
3. Upload `voltride.zip`
4. Extraire archive
5. Déplacer contenu `public/` vers `public_html`
6. Déplacer reste vers dossier parent ou `voltride/`

#### Étape 3: Configurer `.env`

Via File Manager:
1. Copier `.env.example` → `.env`
2. Éditer `.env` avec credentials

#### Étape 4: Composer Install

Via Terminal SSH (si disponible):
```bash
composer install --optimize-autoloader --no-dev
```

Ou via PHP Selector dans cPanel

#### Étape 5: Migrations

```bash
php artisan migrate --force
```

---

### Option 3: Laravel Forge (Recommandé pour débutants)

[Laravel Forge](https://forge.laravel.com) automatise tout le processus.

**Avantages**:
- Configuration automatique serveur
- Déploiement Git automatique
- SSL gratuit
- Queues et Cron configurés
- Interface simple

**Prix**: ~15$/mois

---

## 🔒 Sécurité Production

### Headers de Sécurité

Ajouter dans Nginx:

```nginx
add_header X-Frame-Options "SAMEORIGIN";
add_header X-Content-Type-Options "nosniff";
add_header X-XSS-Protection "1; mode=block";
add_header Referrer-Policy "strict-origin-when-cross-origin";
add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline';";
```

### Rate Limiting

Dans `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'web' => [
        // ...
        \Illuminate\Routing\Middleware\ThrottleRequests::class.':60,1',
    ],
];
```

### Protéger Admin Routes

Dans `routes/web.php`:

```php
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Routes admin
});
```

---

## 📊 Monitoring

### Logs

```bash
# Surveiller logs
tail -f /var/www/voltride/storage/logs/laravel.log
```

### Uptime Monitoring

Services recommandés:
- [UptimeRobot](https://uptimerobot.com) (Gratuit)
- [Pingdom](https://pingdom.com)
- [StatusCake](https://statuscake.com)

### Error Tracking

Intégrer [Sentry](https://sentry.io):

```bash
composer require sentry/sentry-laravel
```

```env
SENTRY_LARAVEL_DSN=votre-dsn-sentry
```

---

## 🔄 Mises à Jour

### Déploiement Nouvelles Versions

```bash
cd /var/www/voltride

# Sauvegarder .env
cp .env .env.backup

# Pull dernières modifications
git pull origin main

# Installer dépendances
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Migrations
php artisan migrate --force

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Recréer cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Redémarrer services
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
sudo supervisorctl restart voltride-worker:*
```

---

## 🔙 Backup

### Script Backup Automatique

Créer `backup.sh`:

```bash
#!/bin/bash

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/voltride"

# Backup Database
mysqldump -u voltride_user -p'password' voltride_production > $BACKUP_DIR/db_$DATE.sql

# Backup Files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/voltride

# Garder seulement 7 derniers backups
find $BACKUP_DIR -type f -mtime +7 -delete

echo "Backup completed: $DATE"
```

```bash
chmod +x backup.sh

# Ajouter au cron (tous les jours à 2h)
crontab -e
```

```
0 2 * * * /path/to/backup.sh
```

---

## 🐛 Troubleshooting

### Erreur 500

1. Vérifier logs: `storage/logs/laravel.log`
2. Vérifier permissions: `sudo chmod -R 775 storage bootstrap/cache`
3. Vérifier `.env` configuration
4. Clear cache: `php artisan config:clear`

### Emails non envoyés

1. Vérifier `.env` SMTP config
2. Tester: `php artisan tinker` → `Mail::raw('Test', fn($m) => $m->to('test@example.com'))`
3. Vérifier logs email

### CSS/JS non chargés

1. Vérifier `APP_URL` dans `.env`
2. Rebuild assets: `npm run build`
3. Clear cache: `php artisan view:clear`

---

## ✅ Validation Finale

### Tests Manuels

- [ ] Page d'accueil charge correctement
- [ ] Inscription/Connexion fonctionnent
- [ ] Liste trottinettes s'affiche
- [ ] Création réservation OK
- [ ] Emails reçus (client + admin)
- [ ] Panel admin accessible
- [ ] Responsive sur mobile
- [ ] SSL actif (https://)
- [ ] Pas d'erreurs console navigateur

### Tests Performance

```bash
# PageSpeed Insights
# https://pagespeed.web.dev/

# GTmetrix
# https://gtmetrix.com/
```

**Objectifs**:
- Score PageSpeed > 90
- Temps chargement < 2s
- First Contentful Paint < 1.5s

---

## 📚 Ressources

- [Laravel Deployment Docs](https://laravel.com/docs/deployment)
- [DigitalOcean Tutorials](https://www.digitalocean.com/community/tags/laravel)
- [Laravel Forge](https://forge.laravel.com)
- [Cloudflare](https://cloudflare.com) (CDN gratuit)

---

**Version**: 1.0.0  
**Dernière MAJ**: Février 2026  
**Status**: ✅ Production Ready  

🎉 **Félicitations! VoltRide est prêt pour le déploiement!**
