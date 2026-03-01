<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérifier l'email - VoltRide</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #00FF6A;
            --primary-dark: #00CC55;
            --dark: #0a0a0a;
            --dark-light: #1a1a1a;
            --dark-lighter: #2a2a2a;
            --gray: #888;
            --white: #ffffff;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 30%, rgba(0, 255, 106, 0.08) 0%, transparent 50%),
                        radial-gradient(circle at 70% 70%, rgba(0, 255, 106, 0.05) 0%, transparent 50%);
            animation: bgFloat 20s ease-in-out infinite;
        }
        
        @keyframes bgFloat {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-5%, -5%); }
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 0 20px;
        }
        
        .card {
            background: var(--dark-light);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 48px 40px;
            animation: slideUp 0.6s ease-out;
            text-align: center;
        }
        
        .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 24px;
            box-shadow: 0 0 40px rgba(0, 255, 106, 0.3);
        }
        
        .title {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--white);
            letter-spacing: -1px;
            margin-bottom: 16px;
        }
        
        .description {
            color: var(--gray);
            line-height: 1.6;
            margin-bottom: 32px;
        }
        
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--dark);
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: inherit;
            margin-bottom: 16px;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 255, 106, 0.3);
        }
        
        .btn-secondary {
            width: 100%;
            padding: 14px;
            background: transparent;
            color: var(--gray);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.05);
            color: var(--white);
        }
        
        .alert-success {
            background: rgba(0, 255, 106, 0.1);
            border: 1px solid rgba(0, 255, 106, 0.3);
            color: var(--primary);
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 0.9rem;
        }
        
        .back-home {
            text-align: center;
            margin-top: 24px;
        }
        
        .back-home a {
            color: var(--gray);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }
        
        .back-home a:hover {
            color: var(--white);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="logo-icon"><i class="fa-solid fa-envelope"></i></div>
            <h1 class="title">Vérifiez votre email</h1>
            <p class="description">
                Merci pour votre inscription ! Avant de commencer, veuillez vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="alert-success">
                    Un nouveau lien de vérification a été envoyé à votre adresse email.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-submit">
                    Renvoyer l'email de vérification →
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-secondary">
                    Se déconnecter
                </button>
            </form>
        </div>
        
        <div class="back-home">
            <a href="{{ url('/') }}">← Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>
