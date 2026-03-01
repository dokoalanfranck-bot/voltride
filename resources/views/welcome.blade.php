<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>⚡ VoltRide - La mobilité électrique réinventée</title>
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
            --dark: #0a0a0a;
            --dark-light: #1a1a1a;
            --gray: #888;
            --white: #ffffff;
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
        [data-theme="light"] {
            --primary: #00CC55;
            --primary-dark: #00994D;
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
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--dark);
            color: var(--white);
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--navbar-bg);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 12px 40px;
            background: rgba(10, 10, 10, 0.95);
        }

        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 800;
            font-size: 1.5rem;
        }

        .navbar-logo img {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            object-fit: cover;
        }

        .navbar-links {
            display: flex;
            gap: 32px;
            align-items: center;
        }

        .navbar-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .navbar-links a:hover {
            color: var(--text-primary);
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
            margin-left: 16px;
        }
        .theme-toggle:hover {
            background: var(--primary);
            color: var(--bg-primary);
            transform: rotate(180deg);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--dark);
            position: relative;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(0, 255, 106, 0.3);
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

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 120px 40px 80px;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(ellipse at 20% 50%, rgba(0, 255, 106, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 50%, rgba(0, 200, 100, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 100%, rgba(0, 255, 106, 0.08) 0%, transparent 50%);
            z-index: 0;
        }

        .hero-grid {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 900px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(0, 255, 106, 0.1);
            border: 1px solid rgba(0, 255, 106, 0.3);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            color: var(--primary);
            margin-bottom: 32px;
            animation: fadeInUp 0.8s ease-out;
        }

        .hero-title {
            font-size: clamp(3rem, 10vw, 5.5rem);
            font-weight: 900;
            line-height: 1.05;
            margin-bottom: 28px;
            letter-spacing: -3px;
            animation: fadeInUp 0.8s ease-out 0.1s both;
        }

        .hero-title .highlight {
            background: linear-gradient(135deg, var(--primary) 0%, #00ffaa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: clamp(1.1rem, 2.5vw, 1.4rem);
            color: var(--gray);
            max-width: 600px;
            margin: 0 auto 48px;
            line-height: 1.7;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .hero-cta {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease-out 0.3s both;
        }

        /* Features Section */
        .features {
            padding: 120px 40px;
            background: var(--dark);
            position: relative;
        }

        .features::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 106, 0.3), transparent);
        }

        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 80px;
        }

        .section-tag {
            color: var(--primary);
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 16px;
        }

        .section-title {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 800;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }

        .section-subtitle {
            color: var(--gray);
            font-size: 1.1rem;
            line-height: 1.7;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: var(--dark-light);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 24px;
            padding: 40px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary), transparent);
            opacity: 0;
            transition: opacity 0.4s;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            border-color: rgba(0, 255, 106, 0.2);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, rgba(0, 255, 106, 0.2) 0%, rgba(0, 255, 106, 0.05) 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 24px;
        }

        .feature-card h3 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .feature-card p {
            color: var(--gray);
            line-height: 1.7;
        }

        /* How it works */
        .how-it-works {
            padding: 120px 40px;
            background: linear-gradient(180deg, var(--dark) 0%, var(--dark-light) 100%);
        }

        .steps-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
        }

        .step {
            flex: 1;
            min-width: 280px;
            max-width: 350px;
            text-align: center;
            position: relative;
        }

        .step-number {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 900;
            color: var(--dark);
            margin: 0 auto 24px;
            position: relative;
            z-index: 2;
        }

        .step h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .step p {
            color: var(--gray);
            line-height: 1.6;
        }

        /* CTA Section */
        .cta-section {
            padding: 120px 40px;
            background: var(--dark-light);
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(0, 255, 106, 0.08) 0%, transparent 50%);
            animation: rotate 30s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .cta-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        .cta-title {
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 900;
            margin-bottom: 24px;
            letter-spacing: -2px;
        }

        .cta-subtitle {
            font-size: 1.2rem;
            color: var(--gray);
            margin-bottom: 40px;
            line-height: 1.7;
        }

        /* Footer */
        .footer {
            padding: 60px 40px 40px;
            background: var(--dark);
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 24px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 1.3rem;
        }

        .footer-logo img {
            width: 40px;
            height: 40px;
            border-radius: 10px;
        }

        .footer-links {
            display: flex;
            gap: 32px;
        }

        .footer-links a {
            color: var(--gray);
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        .footer-copy {
            width: 100%;
            text-align: center;
            padding-top: 40px;
            margin-top: 40px;
            border-top: 1px solid rgba(255,255,255,0.05);
            color: var(--gray);
            font-size: 0.9rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-on-scroll {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s ease-out;
        }

        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                padding: 16px 20px;
            }

            .navbar-links {
                display: none;
            }

            .hero {
                padding: 100px 20px 60px;
            }

            .features, .how-it-works, .cta-section {
                padding: 80px 20px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .steps-container {
                flex-direction: column;
                align-items: center;
            }

            .step {
                max-width: 100%;
            }
        }

        /* Floating elements */
        .float-element {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(0, 255, 106, 0.3), rgba(0, 255, 106, 0.1));
            filter: blur(40px);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .float-1 {
            width: 300px;
            height: 300px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .float-2 {
            width: 200px;
            height: 200px;
            top: 60%;
            right: 15%;
            animation-delay: 2s;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <a href="/" class="navbar-logo">
            <img src="{{ asset('logos/logo.jpeg') }}" alt="VoltRide">
            <span>VoltRide</span>
        </a>
        <div class="navbar-links">
            <a href="#features">Avantages</a>
            <a href="#how">Comment ça marche</a>
            <a href="{{ route('scooters.index') }}">Nos trottinettes</a>
            @auth
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            @else
                <a href="{{ route('login') }}">Connexion</a>
            @endauth
        </div>
        <button class="theme-toggle" onclick="toggleTheme()" title="Changer de thème">
            <span id="theme-icon">🌙</span>
        </button>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-grid"></div>
        <div class="float-element float-1"></div>
        <div class="float-element float-2"></div>
        
        <div class="hero-content">
            <div class="hero-badge">
                <span>⚡</span>
                <span>Disponible maintenant dans votre ville</span>
            </div>
            <h1 class="hero-title">
                La mobilité<br>
                <span class="highlight">électrique</span><br>
                réinventée
            </h1>
            <p class="hero-subtitle">
                Louez une trottinette électrique en quelques secondes. Sans inscription, sans engagement. Juste la liberté de rouler.
            </p>
            <div class="hero-cta">
                <a href="{{ route('scooters.index') }}" class="btn btn-primary">
                    🚀 Réserver maintenant
                </a>
                <a href="#how" class="btn btn-outline">
                    Comment ça marche
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="section-header animate-on-scroll">
            <div class="section-tag">Pourquoi VoltRide</div>
            <h2 class="section-title">Tout ce dont vous avez besoin pour rouler en toute sérénité</h2>
            <p class="section-subtitle">Une expérience de location pensée pour vous, du premier clic jusqu'à votre destination.</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card animate-on-scroll">
                <div class="feature-icon">⚡</div>
                <h3>Réservation instantanée</h3>
                <p>Choisissez votre trottinette et réservez en moins de 2 minutes. Aucun compte requis, juste vos coordonnées.</p>
            </div>
            <div class="feature-card animate-on-scroll">
                <div class="feature-icon">🔋</div>
                <h3>Toujours chargée</h3>
                <p>Nos trottinettes sont contrôlées et rechargées après chaque location. Batterie pleine garantie.</p>
            </div>
            <div class="feature-card animate-on-scroll">
                <div class="feature-icon">💰</div>
                <h3>Prix transparents</h3>
                <p>Pas de frais cachés, pas de surprise. Vous payez exactement ce que vous voyez, rien de plus.</p>
            </div>
            <div class="feature-card animate-on-scroll">
                <div class="feature-icon">🛡️</div>
                <h3>Assurance incluse</h3>
                <p>Roulez l'esprit tranquille. Chaque location inclut une assurance responsabilité civile complète.</p>
            </div>
            <div class="feature-card animate-on-scroll">
                <div class="feature-icon">🌍</div>
                <h3>Éco-responsable</h3>
                <p>Zéro émission, zéro bruit. Participez à la mobilité durable et réduisez votre empreinte carbone.</p>
            </div>
            <div class="feature-card animate-on-scroll">
                <div class="feature-icon">📞</div>
                <h3>Support 7j/7</h3>
                <p>Une question, un problème ? Notre équipe est disponible tous les jours pour vous assister.</p>
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section class="how-it-works" id="how">
        <div class="section-header animate-on-scroll">
            <div class="section-tag">Simplicité</div>
            <h2 class="section-title">Réservez en 3 étapes</h2>
            <p class="section-subtitle">Pas de processus compliqué. Juste une expérience fluide et rapide.</p>
        </div>
        
        <div class="steps-container">
            <div class="step animate-on-scroll">
                <div class="step-number">1</div>
                <h3>Choisissez</h3>
                <p>Parcourez notre flotte et sélectionnez la trottinette qui vous convient. Filtrez par autonomie, vitesse ou prix.</p>
            </div>
            <div class="step animate-on-scroll">
                <div class="step-number">2</div>
                <h3>Réservez</h3>
                <p>Indiquez vos dates et vos coordonnées. Confirmation immédiate par email. Aucun compte à créer.</p>
            </div>
            <div class="step animate-on-scroll">
                <div class="step-number">3</div>
                <h3>Roulez</h3>
                <p>Récupérez votre trottinette, payez sur place et profitez de votre trajet. C'est aussi simple que ça.</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-content animate-on-scroll">
            <h2 class="cta-title">Prêt à prendre<br>la route ?</h2>
            <p class="cta-subtitle">
                Rejoignez des milliers d'utilisateurs qui ont déjà adopté une mobilité plus libre, plus verte et plus fun.
            </p>
            <a href="{{ route('scooters.index') }}" class="btn btn-primary" style="font-size: 1.1rem; padding: 18px 36px;">
                🛴 Voir les trottinettes disponibles
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-logo">
                <img src="{{ asset('logos/logo.jpeg') }}" alt="VoltRide">
                <span>VoltRide</span>
            </div>
            <div class="footer-links">
                <a href="{{ route('scooters.index') }}">Nos trottinettes</a>
                <a href="#features">Avantages</a>
                <a href="#how">Comment ça marche</a>
                @guest
                    <a href="{{ route('login') }}">Connexion</a>
                @endguest
            </div>
            <div class="footer-copy">
                <p>© {{ date('Y') }} VoltRide. Tous droits réservés. Paiement sécurisé sur place.</p>
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
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
