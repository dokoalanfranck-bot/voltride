<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VoltRide - Location de Trottinettes Électriques')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #00FF6A;
            --primary-dark: #00CC55;
            --primary-darker: #00994D;
            --dark: #0a0a0a;
            --dark-light: #1a1a1a;
            --dark-lighter: #2a2a2a;
            --gray: #888;
            --gray-light: #aaa;
            --white: #ffffff;
            --danger: #ff4d4d;
            --warning: #ffaa00;
            --success: #00FF6A;
            --info: #00aaff;
            
            /* Theme-specific (dark by default) */
            --bg-primary: #0a0a0a;
            --bg-secondary: #1a1a1a;
            --bg-tertiary: #2a2a2a;
            --text-primary: #ffffff;
            --text-secondary: #aaa;
            --text-muted: #888;
            --border-color: rgba(255,255,255,0.05);
            --border-hover: rgba(0, 255, 106, 0.2);
            --card-shadow: rgba(0, 0, 0, 0.3);
            --navbar-bg: rgba(10, 10, 10, 0.95);
        }
        
        /* Light Theme */
        [data-theme="light"] {
            --primary: #00CC55;
            --primary-dark: #00994D;
            --primary-darker: #007A3D;
            --bg-primary: #f5f5f7;
            --bg-secondary: #ffffff;
            --bg-tertiary: #e5e5e7;
            --text-primary: #1a1a1a;
            --text-secondary: #444;
            --text-muted: #666;
            --border-color: rgba(0,0,0,0.08);
            --border-hover: rgba(0, 204, 85, 0.3);
            --card-shadow: rgba(0, 0, 0, 0.08);
            --navbar-bg: rgba(255, 255, 255, 0.95);
            --gray: #666;
            --gray-light: #444;
            --white: #1a1a1a;
            --dark: #f5f5f7;
            --dark-light: #ffffff;
            --dark-lighter: #e5e5e7;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            transition: background 0.3s, color 0.3s;
        }

        /* Navbar */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 16px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--navbar-bg);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            transition: background 0.3s;
        }

        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 800;
            font-size: 1.4rem;
        }

        .navbar-logo img {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            object-fit: cover;
        }

        .navbar-menu {
            display: flex;
            gap: 28px;
            align-items: center;
        }

        .navbar-menu a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .navbar-menu a:hover {
            color: var(--text-primary);
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-tertiary);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
        
        /* Theme Toggle */
        .theme-toggle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .theme-toggle:hover {
            background: var(--primary);
            color: var(--bg-primary);
            transform: rotate(180deg);
        }

        .admin-badge {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--dark);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-left: 8px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--dark);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 255, 106, 0.3);
        }

        .btn-secondary {
            background: var(--dark-lighter);
            color: var(--white);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .btn-secondary:hover {
            background: var(--dark-light);
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-danger {
            background: var(--danger);
            color: var(--white);
        }

        .btn-danger:hover {
            background: #ff3333;
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            color: var(--white);
            border: 2px solid rgba(255,255,255,0.2);
        }

        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.85rem;
        }

        .btn-lg {
            padding: 16px 32px;
            font-size: 1.1rem;
        }

        /* Cards */
        .card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            border-color: var(--border-hover);
            transform: translateY(-4px);
            box-shadow: 0 20px 40px var(--card-shadow);
        }

        .card-body {
            padding: 24px;
        }

        .card-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: var(--dark-lighter);
        }

        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--gray-light);
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 14px 18px;
            background: var(--dark-lighter);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            color: var(--white);
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 255, 106, 0.1);
        }

        .form-input::placeholder {
            color: var(--gray);
        }

        select.form-input {
            cursor: pointer;
        }

        textarea.form-input {
            resize: vertical;
            min-height: 120px;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-success {
            background: rgba(0, 255, 106, 0.15);
            color: var(--primary);
        }

        .badge-warning {
            background: rgba(255, 170, 0, 0.15);
            color: var(--warning);
        }

        .badge-danger {
            background: rgba(255, 77, 77, 0.15);
            color: var(--danger);
        }

        .badge-info {
            background: rgba(0, 170, 255, 0.15);
            color: var(--info);
        }

        .badge-gray {
            background: rgba(136, 136, 136, 0.15);
            color: var(--gray);
        }

        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: rgba(0, 255, 106, 0.1);
            border: 1px solid rgba(0, 255, 106, 0.3);
            color: var(--primary);
        }

        .alert-danger {
            background: rgba(255, 77, 77, 0.1);
            border: 1px solid rgba(255, 77, 77, 0.3);
            color: var(--danger);
        }

        .alert-warning {
            background: rgba(255, 170, 0, 0.1);
            border: 1px solid rgba(255, 170, 0, 0.3);
            color: var(--warning);
        }

        .alert-info {
            background: rgba(0, 170, 255, 0.1);
            border: 1px solid rgba(0, 170, 255, 0.3);
            color: var(--info);
        }

        /* Tables */
        .table-container {
            background: var(--dark-light);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: var(--bg-tertiary);
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: rgba(0, 255, 106, 0.03);
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .container-sm {
            max-width: 600px;
        }

        .container-lg {
            max-width: 1400px;
        }

        /* Section */
        .section {
            padding: 60px 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 48px;
        }

        .section-tag {
            color: var(--primary);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 12px;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 16px;
            letter-spacing: -1px;
        }

        .section-subtitle {
            color: var(--gray);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Page Header */
        .page-header {
            padding: 40px 0;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .page-subtitle {
            color: var(--gray);
            margin-top: 8px;
        }

        /* Grid */
        .grid {
            display: grid;
            gap: 24px;
        }

        .grid-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .grid-3 {
            grid-template-columns: repeat(3, 1fr);
        }

        .grid-4 {
            grid-template-columns: repeat(4, 1fr);
        }

        .grid-auto {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }

        /* Flex utilities */
        .flex {
            display: flex;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .flex-center {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .gap-sm { gap: 8px; }
        .gap-md { gap: 16px; }
        .gap-lg { gap: 24px; }

        /* Text utilities */
        .text-center { text-align: center; }
        .text-primary { color: var(--primary); }
        .text-gray { color: var(--gray); }
        .text-danger { color: var(--danger); }

        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }

        /* Spacing utilities */
        .mt-sm { margin-top: 8px; }
        .mt-md { margin-top: 16px; }
        .mt-lg { margin-top: 24px; }
        .mt-xl { margin-top: 40px; }

        .mb-sm { margin-bottom: 8px; }
        .mb-md { margin-bottom: 16px; }
        .mb-lg { margin-bottom: 24px; }
        .mb-xl { margin-bottom: 40px; }

        .p-md { padding: 16px; }
        .p-lg { padding: 24px; }
        .p-xl { padding: 40px; }

        /* Footer */
        .footer {
            padding: 40px;
            background: var(--bg-primary);
            border-top: 1px solid var(--border-color);
            margin-top: auto;
            transition: background 0.3s;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .footer-logo img {
            width: 36px;
            height: 36px;
            border-radius: 8px;
        }

        .footer-links {
            display: flex;
            gap: 24px;
        }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        .footer-copy {
            width: 100%;
            text-align: center;
            padding-top: 24px;
            margin-top: 24px;
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        /* Mobile menu */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .grid-4 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 12px 20px;
            }

            .navbar-menu {
                display: none;
            }

            .mobile-toggle {
                display: block;
            }

            .grid-2, .grid-3, .grid-4 {
                grid-template-columns: 1fr;
            }

            .section {
                padding: 40px 0;
            }

            .page-header {
                padding: 24px 0;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .container {
                padding: 0 16px;
            }

            .footer {
                padding: 24px 16px;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
            }

            .footer-links {
                flex-wrap: wrap;
                justify-content: center;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        /* Price display */
        .price {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
        }

        .price-sm {
            font-size: 1.2rem;
        }

        .price-lg {
            font-size: 2rem;
        }

        /* Status indicators */
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        .status-dot.available { background: var(--primary); }
        .status-dot.rented { background: var(--warning); }
        .status-dot.maintenance { background: var(--danger); }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray);
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        /* Main content wrapper */
        .main-content {
            min-height: calc(100vh - 180px);
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="@auth @if(auth()->user()->isAdmin()){{ route('admin.dashboard') }}@else{{ route('welcome') }}@endif @else {{ route('welcome') }} @endauth" class="navbar-logo">
            <img src="{{ asset('logos/logo.jpeg') }}" alt="VoltRide">
            <span>VoltRide</span>
        </a>

        <div class="navbar-menu">
            <a href="{{ route('scooters.index') }}">🛴 Trottinettes</a>
            @auth
                <a href="{{ route('reservations.index') }}">📅 Mes réservations</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
                    <a href="{{ route('admin.scooters.index') }}">⚙️ Gestion</a>
                @endif
            @endauth
        </div>

        <div class="navbar-user">
            <button class="theme-toggle" onclick="toggleTheme()" title="Changer de thème">
                <span id="theme-icon">🌙</span>
            </button>
            @auth
                <div class="user-badge">
                    👤 {{ auth()->user()->name }}
                    @if(auth()->user()->isAdmin())
                        <span class="admin-badge">ADMIN</span>
                    @endif
                </div>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Se connecter</a>
            @endauth
        </div>

        <button class="mobile-toggle" onclick="toggleMobileMenu()">☰</button>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-logo">
                <img src="{{ asset('logos/logo.jpeg') }}" alt="VoltRide">
                <span>VoltRide</span>
            </div>
            <div class="footer-links">
                <a href="{{ route('scooters.index') }}">Nos trottinettes</a>
                <a href="{{ route('welcome') }}">Accueil</a>
                @guest
                    <a href="{{ route('login') }}">Connexion</a>
                @endguest
            </div>
            <div class="footer-copy">
                <p>© {{ date('Y') }} VoltRide. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script>
        // Theme management
        function initTheme() {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);
        }
        
        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        }
        
        function updateThemeIcon(theme) {
            const icon = document.getElementById('theme-icon');
            if (icon) {
                icon.textContent = theme === 'dark' ? '🌙' : '☀️';
            }
        }
        
        // Initialize theme on page load
        initTheme();
        
        function toggleMobileMenu() {
            const menu = document.querySelector('.navbar-menu');
            menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
        }
    </script>
    @yield('scripts')
</body>
</html>
