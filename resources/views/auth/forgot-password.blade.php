<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - ScooterRent</title>
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
        .card {
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
    <div class="card w-full max-w-md mx-4 px-8 py-12">
        <div class="text-center mb-8">
            <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--green-primary); margin-bottom: 8px;">Mot de passe oublié ?</h1>
            <p style="color: #4a5568;">Entrez votre email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
        </div>

        @if (session('status'))
            <div style="background: #d1fae5; border: 1px solid #6ee7b7; color: var(--green-primary); padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                <p style="font-size: 0.9rem;">{{ session('status') }}</p>
            </div>
        @endif

        <form class="space-y-4" action="{{ route('password.email') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div style="background: #fee; border: 1px solid #fcc; color: #c33; padding: 12px; border-radius: 8px;">
                    <p style="font-size: 0.9rem;">{{ $errors->first('email') }}</p>
                </div>
            @endif

            <div>
                <label for="email" style="display: block; font-weight: 600; color: var(--green-primary); margin-bottom: 8px;">Adresse email</label>
                <input id="email" name="email" type="email" required
                    style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; transition: all 0.3s ease;"
                    placeholder="Votre email" value="{{ old('email') }}">
            </div>

            <button type="submit"
                style="width: 100%; background: var(--primary-gradient); color: #0f172a; padding: 12px; border-radius: 8px; font-weight: 700; font-size: 1rem; border: none; cursor: pointer; transition: all 0.3s ease; transform: translateY(0);" 
                onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 24px rgba(7, 214, 93, 0.3)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                Envoyer le lien de réinitialisation
            </button>

            <div style="text-align: center;">
                <a href="{{ route('login') }}" style="color: var(--green-primary); font-weight: 700; text-decoration: none;">Retour à la connexion</a>
            </div>
        </form>
    </div>
</body>
</html>
