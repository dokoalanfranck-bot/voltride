<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - ScooterRent</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --green-primary: #0a9b3a;
            --primary-gradient: linear-gradient(135deg, #47F55B 0%, #07d65d 100%);
        }
        body {
            background: var(--primary-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 12px 32px rgba(0,0,0,0.15);
            animation: slideUp 0.6s ease-out;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        input:focus {
            border-color: #07d65d !important;
            box-shadow: 0 0 0 3px rgba(7, 214, 93, 0.2) !important;
            outline: none;
        }
    </style>
</head>
<body>
    <div class="register-card w-full max-w-md mx-4 px-8 py-12">
        <div class="text-center mb-8">
            <div style="width: 60px; height: 60px; background: var(--primary-gradient); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; font-size: 24px; margin: 0 auto 16px;">
                SR
            </div>
            <h1 style="font-size: 2rem; font-weight: 800; color: var(--green-primary); margin-bottom: 8px;">ScooterRent</h1>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--green-primary); margin-bottom: 8px;">Inscription</h2>
            <p style="color: #4a5568;">Créez votre compte gratuitement</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            @if ($errors->any())
                <div style="background: #fee; border: 1px solid #fcc; color: #c33; padding: 12px; border-radius: 8px;">
                    @foreach ($errors->all() as $error)
                        <p style="font-size: 0.9rem;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div>
                <label style="display: block; font-weight: 600; color: var(--green-primary); margin-bottom: 8px;">Nom complet</label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                    style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; transition: all 0.3s ease;">
            </div>

            <div>
                <label style="display: block; font-weight: 600; color: var(--green-primary); margin-bottom: 8px;">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                    style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; transition: all 0.3s ease;">
            </div>

            <div>
                <label style="display: block; font-weight: 600; color: var(--green-primary); margin-bottom: 8px;">Mot de passe</label>
                <input type="password" name="password" required 
                    style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; transition: all 0.3s ease;">
            </div>

            <div>
                <label style="display: block; font-weight: 600; color: var(--green-primary); margin-bottom: 8px;">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" required 
                    style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; transition: all 0.3s ease;">
            </div>

            <button type="submit" 
                style="width: 100%; background: var(--primary-gradient); color: #0f172a; padding: 12px; border-radius: 8px; font-weight: 700; font-size: 1rem; border: none; cursor: pointer; transition: all 0.3s ease; transform: translateY(0);" 
                onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 24px rgba(7, 214, 93, 0.3)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                Créer un compte
            </button>

            <p style="text-align: center; color: #4a5568; font-size: 0.9rem;">
                Vous avez un compte ? 
                <a href="{{ route('login') }}" style="color: var(--green-primary); font-weight: 700; text-decoration: none;">Se connecter</a>
            </p>
        </form>
    </div>
</body>
</html>
