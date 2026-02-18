<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScooterRent - Location de Trottinettes Électriques</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --green-primary: #1f7550;
            --green-light: #2d9b6f;
            --green-dark: #155d3b;
        }

        nav {
            background: linear-gradient(135deg, #09B826 0%, #04243a 100%);
            box-shadow: 0 2px 12px rgba(31, 117, 80, 0.15);
        }

        nav a {
            color: white;
            transition: opacity 0.3s ease;
        }

        nav a:hover {
            opacity: 0.8;
        }

        .hero {
            background: linear-gradient(135deg, #045111 0%, #09B826 100%);
            padding: 100px 20px;
            color: white;
            text-align: center;
        }

        .btn-primary {
            background-color: white;
            color: var(--green-primary);
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--green-primary) 0%, var(--green-light) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: white;
            font-size: 32px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(31, 117, 80, 0.12);
        }

        .footer {
            background: linear-gradient(135deg, #09B826 0%, #045111 100%);
            color: white;
            padding: 40px 20px;
        }
    </style>
</head>
<body class="bg-white">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <img src="{{ route('storage.logo', ['filename' => 'logo.jpeg']) }}" alt="VoltRide Logo" style="width: 60px; height: 60px; object-fit: contain; display: flex; align-items: center;">
                    <a href="/" class="text-xl font-bold text-white">VoltRide</a>
                </div>
                <div class="flex space-x-4">
                    @auth
                        <a href="{{ route('scooters.index') }}" class="hover:opacity-80">Parcourir</a>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="hover:opacity-80">Déconnexion</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" style="border: 2px solid white; padding: 8px 16px; border-radius: 6px;" class="hover:opacity-80">Connexion</a>
                        <a href="{{ route('register') }}" class="btn-primary" style="background-color: white; color: var(--green-primary); padding: 8px 16px;">Inscription</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="max-w-4xl mx-auto">
            <h1 style="font-size: 3rem; font-weight: 800; margin-bottom: 20px;">Explorez la Ville en Trottinette</h1>
            <p style="font-size: 1.25rem; margin-bottom: 40px; opacity: 0.95;">Solution de mobilité urbaine rapide, écologique et accessible à tous.</p>
            <a href="{{ route('scooters.index') }}" class="btn-primary">Commencer maintenant</a>
        </div>
    </section>

    <!-- Features Section -->
    <section style="padding: 80px 20px; background-color: #f8f9fa;">
        <div class="max-w-7xl mx-auto">
            <h2 style="font-size: 2.5rem; font-weight: 700; color: var(--green-primary); text-align: center; margin-bottom: 60px;">Pourquoi choisir VoltRide ?</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="card">
                    <div class="feature-icon">🛴</div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--green-primary); margin-bottom: 12px;">Trottinettes Modernes</h3>
                    <p class="text-gray-600">Flotte de trottinettes électriques bien entretenues et régulièrement mises à jour.</p>
                </div>

                <div class="card">
                    <div class="feature-icon">⚡</div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--green-primary); margin-bottom: 12px;">Rapide & Efficient</h3>
                    <p class="text-gray-600">Parcourez la ville rapidement avec une autonomie garantie et recharge ultra-rapide.</p>
                </div>

                <div class="card">
                    <div class="feature-icon">💚</div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--green-primary); margin-bottom: 12px;">Écologique</h3>
                    <p class="text-gray-600">Zéro émission de CO₂. Contribuez à un avenir urbain plus vert et durable.</p>
                </div>

                <div class="card">
                    <div class="feature-icon">💰</div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--green-primary); margin-bottom: 12px;">Tarifs Compétitifs</h3>
                    <p class="text-gray-600">Pricing transparent et abordable. Pas de frais cachés, seulement ce que vous utilisez.</p>
                </div>

                <div class="card">
                    <div class="feature-icon">🔒</div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--green-primary); margin-bottom: 12px;">Sécurité Garantie</h3>
                    <p class="text-gray-600">Assurance incluse et système de sécurité avancé pour vos déplacements.</p>
                </div>

                <div class="card">
                    <div class="feature-icon">📱</div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--green-primary); margin-bottom: 12px;">Application Intuitive</h3>
                    <p class="text-gray-600">Réservation simple en 2 clics. Gérez vos trajets directement depuis votre téléphone.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="hero">
        <div class="max-w-4xl mx-auto">
            <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 24px;">Prêt à profiter de la liberté ?</h2>
            <p style="font-size: 1.125rem; margin-bottom: 32px; opacity: 0.95;">Rejoignez des milliers d'utilisateurs satisfaits et commencez votre première location.</p>
            <a href="{{ route('register') }}" class="btn-primary">S'inscrire gratuitement</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 style="font-weight: 700; margin-bottom: 16px;">À propos</h3>
                    <p class="text-sm opacity-80">ScooterRent est votre partenaire de mobilité urbaine responsable.</p>
                </div>
                <div>
                    <h3 style="font-weight: 700; margin-bottom: 16px;">Service</h3>
                    <ul class="text-sm space-y-2 opacity-80">
                        <li><a href="#" class="hover:opacity-100">Aide & Support</a></li>
                        <li><a href="#" class="hover:opacity-100">Conditions</a></li>
                        <li><a href="#" class="hover:opacity-100">Confidentialité</a></li>
                    </ul>
                </div>
                <div>
                    <h3 style="font-weight: 700; margin-bottom: 16px;">Entreprise</h3>
                    <ul class="text-sm space-y-2 opacity-80">
                        <li><a href="#" class="hover:opacity-100">À propos</a></li>
                        <li><a href="#" class="hover:opacity-100">Blog</a></li>
                        <li><a href="#" class="hover:opacity-100">Carrières</a></li>
                    </ul>
                </div>
                <div>
                    <h3 style="font-weight: 700; margin-bottom: 16px;">Contact</h3>
                    <p class="text-sm opacity-80">
                        Email: @gmail.com<br>
                        Tél: 99185904
                    </p>
                </div>
            </div>
            <div style="border-top: 1px solid rgba(255,255,255,0.2); padding-top: 24px; text-align: center; opacity: 0.80;">
                <p>&copy; 2026 ScooterRent. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>
