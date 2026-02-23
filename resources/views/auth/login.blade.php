<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - ScooterRent</title>
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
        .login-card {
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
    <div class="login-card w-full max-w-md mx-4 px-8 py-12">
        <div class="text-center mb-8">
            <div style="width: 60px; height: 60px; background: var(--primary-gradient); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; font-size: 24px; margin: 0 auto 16px;">
                SR
            </div>
            <h1 style="font-size: 2rem; font-weight: 800; color: var(--green-primary); margin-bottom: 8px;">ScooterRent</h1>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--green-primary); margin-bottom: 8px;">Connexion</h2>
            <p style="color: #4a5568;">Accédez à votre compte</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            @if ($errors->any())
                <div style="background: #fee; border: 1px solid #fcc; color: #c33; padding: 12px; border-radius: 8px;">
                    @foreach ($errors->all() as $error)
                        <p style="font-size: 0.9rem;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

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

            <div style="display: flex; align-items: center; justify-content: space-between;">
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="remember" style="width: 18px; height: 18px; cursor: pointer;">
                    <span style="color: #4a6850; font-size: 0.9rem;">Se souvenir de moi</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="color: var(--green-primary); text-decoration: none; font-weight: 600; font-size: 0.9rem;">Mot de passe oublié ?</a>
                @endif
            </div>

            <button type="submit" 
                style="width: 100%; background: var(--primary-gradient); color: #0f172a; padding: 12px; border-radius: 8px; font-weight: 700; font-size: 1rem; border: none; cursor: pointer; transition: all 0.3s ease; transform: translateY(0);" 
                onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 24px rgba(7, 214, 93, 0.3)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                Se connecter
            </button>
        </form>
    </div>
</body>
</html>
