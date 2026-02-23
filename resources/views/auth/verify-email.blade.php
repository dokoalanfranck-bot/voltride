<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérifier l'email - ScooterRent</title>
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
    </style>
</head>
<body>
    <div class="card w-full max-w-md mx-4 px-8 py-12">
        <div class="text-center mb-8">
            <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--green-primary); margin-bottom: 8px;">Vérifier votre email</h1>
            <p style="color: #4a5568;">Un lien de vérification a été envoyé à votre adresse email.</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div style="background: #d1fae5; border: 1px solid #6ee7b7; color: var(--green-primary); padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                <p style="font-size: 0.9rem;">Un nouveau lien de vérification a été envoyé à votre email.</p>
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
            @csrf
            <button type="submit"
                style="width: 100%; background: var(--primary-gradient); color: #0f172a; padding: 12px; border-radius: 8px; font-weight: 700; font-size: 1rem; border: none; cursor: pointer; transition: all 0.3s ease; transform: translateY(0);" 
                onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 24px rgba(7, 214, 93, 0.3)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                Renvoyer l'email de vérification
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="width: 100%; text-align: center; color: var(--green-primary); font-weight: 600; text-decoration: none; padding: 12px; border: none; background: none; cursor: pointer; transition: all 0.3s;" 
                onmouseover="this.style.color='#07d65d';"
                onmouseout="this.style.color='var(--green-primary)';">
                Se déconnecter
            </button>
        </form>
    </div>
</body>
</html>
