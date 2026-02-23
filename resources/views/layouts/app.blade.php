<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ScooterRent - Louez vos trottinettes électriques')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            /* Primary Colors */
            --primary-gradient: linear-gradient(135deg, #47F55B 0%, #07d65d 100%);
            --primary-bright: #47F55B;
            --primary-dark: #07d65d;
            --primary-darker: #0a9b3a;
            --primary-light: #f0fdf4;
            
            /* Text Colors */
            --text-primary: #0f172a;
            --text-secondary: #4a5568;
            --text-tertiary: #999;
            
            /* Status Colors */
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #dc2626;
            --info: #3b82f6;
            
            /* Shadows */
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 8px 16px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 12px 24px rgba(7, 214, 93, 0.25);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: white;
            color: var(--text-primary);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.6;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: var(--text-primary);
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(7, 214, 93, 0.3);
        }

        .btn-secondary {
            background: white;
            color: var(--primary-darker);
            border: 2px solid var(--primary-darker);
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary:hover {
            background: var(--primary-light);
            color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-image {
            width: 100%;
            height: 200px;
            object-fit: contain;
            border-radius: 12px 12px 0 0;
        }

        .badge-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-available {
            background: #d1fae5;
            color: var(--primary-darker);
        }

        .badge-rented {
            background: #fed7aa;
            color: #92400e;
        }

        /* Navigation */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 16px rgba(7, 214, 93, 0.25);
        }

        .navbar-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 64px;
        }

        .navbar-logo {
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: white;
        }

        .navbar-logo-box {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .navbar-menu-desktop {
            display: none;
            gap: 32px;
            align-items: center;
        }

        .navbar-menu-desktop a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .navbar-menu-desktop a:hover {
            opacity: 0.8;
        }

        .navbar-user {
            display: none;
            align-items: center;
            gap: 12px;
        }

        .user-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .user-badge-admin {
            background: rgba(255, 215, 0, 0.3);
            color: #ffd700;
            margin-left: 8px;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
        }

        .navbar-toggle {
            display: flex;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 1.5rem;
        }

        .navbar-menu-mobile {
            display: none;
            position: absolute;
            top: 64px;
            left: 0;
            right: 0;
            background: var(--primary-gradient);
            padding: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-menu-mobile.active {
            display: block;
        }

        .navbar-menu-mobile a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }

        .navbar-menu-mobile a:last-child {
            border-bottom: none;
        }

        .navbar-menu-mobile a:hover {
            padding-left: 8px;
        }

        .navbar-mobile-user {
            padding: 12px 0;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        @media (min-width: 768px) {
            .navbar-menu-desktop {
                display: flex;
            }

            .navbar-user {
                display: flex;
            }

            .navbar-toggle {
                display: none;
            }

            .navbar-menu-mobile {
                display: none !important;
            }
        }

        /* Footer */
        .footer {
            background: var(--primary-gradient);
            color: white;
            padding: 48px 16px 24px;
            margin-top: 80px;
        }

        .footer-container {
            max-width: 1280px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 32px;
            margin-bottom: 40px;
            padding-bottom: 40px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-section h4 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            padding: 6px 0;
        }

        .footer-section ul li a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }

        .footer-section ul li a:hover {
            color: white;
        }

        .footer-bottom {
            text-align: center;
            padding: 16px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
        }

        @media (max-width: 767px) {
            .navbar-container {
                height: 56px;
            }

            body {
                font-size: 0.95rem;
            }
        }

        /* Responsive Utilities */
        @media (max-width: 767px) {
            .responsive-grid-2 { grid-template-columns: 1fr !important; }
            .responsive-grid-4 { grid-template-columns: repeat(2, 1fr) !important; }
            .responsive-grid-auto { grid-template-columns: 1fr !important; }
            .responsive-flex-reverse { flex-direction: column-reverse !important; }
            .sidebar-sticky { position: static !important; top: auto !important; }
        }

        @media (max-width: 479px) {
            .responsive-grid-4 { grid-template-columns: 1fr !important; }
        }

        /* Font Scaling */
        h1 { font-size: clamp(1.5rem, 5vw, 2.5rem); }
        h2 { font-size: clamp(1.3rem, 4vw, 2rem); }
        h3 { font-size: clamp(1.1rem, 3vw, 1.5rem); }
    </style>

</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <!-- Logo -->
            <a href="{{ route('welcome') }}" class="navbar-logo">
                <img src="{{ asset('logos/logo.jpeg') }}" alt="VoltRide Logo" style="width: 60px; height: 60px; object-fit: contain; display: flex; align-items: center;">
                <span style="margin-left: 8px;">VoltRide</span>
            </a>

            <!-- Desktop Menu -->
            <div class="navbar-menu-desktop">
                <a href="{{ route('scooters.index') }}">🛴 Parcourir</a>
                @auth
                    <a href="{{ route('reservations.index') }}">📅 Mes réservations</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}">📊 Tableau de bord</a>
                    @endif
                @endauth
            </div>

            <!-- Desktop User Status -->
            <div class="navbar-user">
                @auth
                    <div class="user-badge">
                        👤 {{ auth()->user()->name }}
                        @if(auth()->user()->isAdmin())
                            <span class="user-badge-admin">ADMIN</span>
                        @endif
                    </div>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-secondary" style="padding: 6px 16px; font-size: 0.9rem;">
                            ➡️ Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-primary">Se connecter</a>
                @endauth
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="navbar-toggle" onclick="toggleMobileMenu()">☰</button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="navbar-menu-mobile">
            <a href="{{ route('scooters.index') }}">🛴 Parcourir les trottinettes</a>
            @auth
                <a href="{{ route('reservations.index') }}">📅 Mes réservations</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">📊 Tableau de bord</a>
                @endif
                <div class="navbar-mobile-user">
                    👤 {{ Str::limit(auth()->user()->name, 10) }}
                    @if(auth()->user()->isAdmin())
                        <span class="user-badge-admin" style="margin-left: auto;">ADMIN</span>
                    @endif
                </div>
                <form action="{{ route('logout') }}" method="POST" style="display: block; width: 100%; margin-top: 12px;">
                    @csrf
                    <button type="submit" class="btn-secondary" style="width: 100%;">
                        ➡️ Déconnexion
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-primary" style="display: block; text-align: center; width: 100%;">Se connecter</a>
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-section">
                    <h4>À propos</h4>
                    <ul>
                        <li><a href="#">Nous connaître</a></li>
                        <li><a href="#">Notre mission</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Partenaires</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Service</h4>
                    <ul>
                        <li><a href="#">Comment louer</a></li>
                        <li><a href="#">Tarification</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Assistance</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Entreprise</h4>
                    <ul>
                        <li><a href="#">Emplois</a></li>
                        <li><a href="#">Presse</a></li>
                        <li><a href="#">Durabilité</a></li>
                        <li><a href="#">Investir</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <ul>
                        <li>📧 support@scooterrent.com</li>
                        <li>📱 +33 1 23 45 67 89</li>
                        <li>🏢 123 Avenue Verte, 75000 Paris</li>
                        <li>⏰ Lun-Ven: 9h-18h</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 ScooterRent. Tous droits réservés. | <a href="#" style="color: rgba(255,255,255,0.8);">Politique de confidentialité</a> | <a href="#" style="color: rgba(255,255,255,0.8);">Conditions d'utilisation</a></p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('active');
        }

        // Close menu when clicking on a link
        document.querySelectorAll('#mobileMenu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobileMenu').classList.remove('active');
            });
        });
    </script>
</body>
</html>
