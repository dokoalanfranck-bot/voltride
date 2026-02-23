<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>⚡ VoltRide - Location de Trottinettes Électriques</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        /* Navigation */
        nav {
            background: linear-gradient(135deg, #47F55B 0%, #07d65d 100%);
            box-shadow: 0 2px 12px rgba(31, 117, 80, 0.15);
            padding: clamp(16px, 3vw, 20px) 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        nav .logo {
            font-size: clamp(1.3rem, 4vw, 1.8rem);
            font-weight: 700;
            color: white;
            text-decoration: none;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 clamp(8px, 2vw, 16px);
            font-size: clamp(0.85rem, 2vw, 1rem);
            transition: opacity 0.3s ease;
        }

        nav a:hover {
            opacity: 0.8;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #47F55B 0%, #07d65d 100%);
            padding: clamp(40px, 10vw, 100px) 1.5rem;
            color: #0f172a;
            text-align: center;
        }

        .hero h1 {
            font-size: clamp(1.8rem, 7vw, 3.5rem);
            font-weight: 800;
            margin-bottom: clamp(16px, 3vw, 24px);
            line-height: 1.2;
            color: #0f172a;
        }

        .hero p {
            font-size: clamp(1rem, 2vw, 1.25rem);
            margin-bottom: clamp(24px, 4vw, 40px);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            color: rgba(15, 23, 42, 0.9);
            opacity: 1;
        }

        .btn-primary {
            background: linear-gradient(135deg, #47F55B 0%, #07d65d 100%);
            color: #0f172a;
            padding: clamp(12px, 2vw, 16px) clamp(20px, 4vw, 32px);
            border-radius: 8px;
            font-weight: 700;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            font-size: clamp(0.95rem, 2vw, 1.1rem);
            cursor: pointer;
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(7, 214, 93, 0.3);
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Features Section */
        .features {
            padding: clamp(40px, 8vw, 80px) 1.5rem;
        }

        .features h2 {
            font-size: clamp(1.8rem, 5vw, 2.5rem);
            color: #0a9b3a;
            text-align: center;
            margin-bottom: clamp(32px, 5vw, 60px);
            font-weight: 800;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: clamp(20px, 4vw, 32px);
            margin-bottom: clamp(40px, 8vw, 80px);
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: clamp(20px, 4vw, 32px);
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(31, 117, 80, 0.12);
        }

        .feature-icon {
            font-size: clamp(2.5rem, 6vw, 3.5rem);
            margin-bottom: 16px;
        }

        .card h3 {
            color: #0a9b3a;
            font-size: clamp(1.1rem, 3vw, 1.4rem);
            margin-bottom: 12px;
            font-weight: 700;
        }

        .card p {
            color: #4a5568;
            font-size: clamp(0.9rem, 2vw, 1rem);
            line-height: 1.6;
        }

        /* CTA Section */
        .cta {
            background: linear-gradient(135deg, #07d65d 0%, #0a9b3a 100%);
            color: white;
            padding: clamp(40px, 8vw, 80px) 1.5rem;
            text-align: center;
        }

        .cta h2 {
            font-size: clamp(1.8rem, 5vw, 2.2rem);
            margin-bottom: 20px;
            font-weight: 800;
        }

        .cta p {
            font-size: clamp(1rem, 2vw, 1.1rem);
            margin-bottom: 32px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-cta {
            background-color: white;
            color: #0a9b3a;
            padding: clamp(14px, 2.5vw, 18px) clamp(24px, 5vw, 40px);
            border-radius: 8px;
            font-weight: 700;
            font-size: clamp(1rem, 2vw, 1.15rem);
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(7, 214, 93, 0.25);
            color: #07d65d;
        }

        /* Footer */
        footer {
            background: #2c3e50;
            color: white;
            padding: clamp(30px, 5vw, 50px) 1.5rem;
            text-align: center;
            font-size: clamp(0.85rem, 2vw, 0.95rem);
        }

        footer p {
            margin-bottom: 12px;
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

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }
        .delay-6 { animation-delay: 0.6s; }

        /* Stats Section */
        .stats {
            background: linear-gradient(135deg, rgba(71, 245, 91, 0.05) 0%, rgba(7, 214, 93, 0.05) 100%);
            padding: clamp(40px, 8vw, 60px) 1.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 32px;
            text-align: center;
            max-width: 1000px;
            margin: 0 auto;
        }

        .stat-item {
            padding: 20px;
        }

        .stat-number {
            font-size: clamp(2.5rem, 6vw, 3.5rem);
            font-weight: 800;
            background: linear-gradient(135deg, #47F55B 0%, #07d65d 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }

        .stat-label {
            color: #4a5568;
            font-size: clamp(0.9rem, 2vw, 1.1rem);
            font-weight: 600;
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Responsive */
        @media (max-width: 768px) {
            nav {
                justify-content: center;
                gap: 12px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <a href="/" class="logo">⚡ VoltRide</a>
        <div style="display: flex; gap: 12px;">
            @auth
                <a href="{{ route('scooters.index') }}">Parcourir</a>
                <a href="{{ route('profile.edit') }}">Mon compte</a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            @else
                <a href="{{ route('scooters.index') }}">Parcourir</a>
                <a href="{{ route('login') }}">Se connecter</a>
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <h1 class="delay-1">Découvrez la mobilité urbaine du futur ⚡</h1>
        <p class="delay-2">Louer une trottinette électrique en quelques secondes. Aucune inscription requise pour commencer!</p>
        <a href="{{ route('scooters.index') }}" class="btn-primary delay-3">🚀 Commencer maintenant</a>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number" data-target="1500">0</div>
                    <div class="stat-label">Locations Effectuées</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="50">0</div>
                    <div class="stat-label">Trottinettes Disponibles</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="98">0</div>
                    <div class="stat-label">% de Satisfaction</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="24">0</div>
                    <div class="stat-label">Heures / 24</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2>Pourquoi VoltRide?</h2>
            <div class="features-grid">
                <div class="card delay-1">
                    <div class="feature-icon">⚡</div>
                    <h3>Rapide & Fiable</h3>
                    <p>Nos trottinettes sont régulièrement entretenues et prêtes à l'emploi. Batterie toujours chargée!</p>
                </div>
                <div class="card delay-2">
                    <div class="feature-icon">💰</div>
                    <h3>Tarifs Compétitifs</h3>
                    <p>Payez uniquement pour ce que vous utilisez. Pas de frais cachés, tout transparent!</p>
                </div>
                <div class="card delay-3">
                    <div class="feature-icon">🔒</div>
                    <h3>Sécurisé & Assuré</h3>
                    <p>L'assurance est incluse. Vous êtes protégé en cas d'incident. Caution solidaire.</p>
                </div>
                <div class="card delay-4">
                    <div class="feature-icon">🚀</div>
                    <h3>Sans Inscription</h3>
                    <p>Réservez en moins de 2 minutes. Pas besoin de créer un compte, juste vos coordonnées!</p>
                </div>
                <div class="card delay-5">
                    <div class="feature-icon">📍</div>
                    <h3>Disponible Partout</h3>
                    <p>Retrouvez nos trottinettes dans les points stratégiques de la ville. Facile d'accès!</p>
                </div>
                <div class="card delay-6">
                    <div class="feature-icon">🌍</div>
                    <h3>Écologique</h3>
                    <p>Zéro émission de CO2. Contribuez à la mobilité durable de notre ville!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Prêt à vous lancer?</h2>
            <p>Explorez notre flotte de trottinettes électriques modernes et réservez la vôtre dès maintenant!</p>
            <a href="{{ route('scooters.index') }}" class="btn-cta">Parcourir nos trottinettes</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 VoltRide - Location de trottinettes électriques</p>
        <p>Toutes nos trottinettes sont assurées et vérifiées régulièrement.</p>
        <p>Paiement sur place - Espèces ou Carte bancaire acceptées</p>
    </footer>

    <script>
        // Intersection Observer for fade-in animations
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -100px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in-up');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe all cards and sections
            document.querySelectorAll('.card, .stat-item, .hero h1, .hero p, .btn-primary').forEach(el => {
                observer.observe(el);
            });

            // Counter animation for stats
            function animateCounter(element, target, duration = 2000) {
                let start = 0;
                const increment = target / (duration / 16);
                const timer = setInterval(() => {
                    start += increment;
                    if (start >= target) {
                        element.textContent = target.toLocaleString();
                        clearInterval(timer);
                    } else {
                        element.textContent = Math.floor(start).toLocaleString();
                    }
                }, 16);
            }

            // Trigger counter animation when stats section is visible
            const statsObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        document.querySelectorAll('.stat-number').forEach(stat => {
                            const target = parseInt(stat.getAttribute('data-target'));
                            animateCounter(stat, target);
                        });
                        statsObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            const statsSection = document.querySelector('.stats');
            if (statsSection) {
                statsObserver.observe(statsSection);
            }
        });
    </script>
</body>
</html>
