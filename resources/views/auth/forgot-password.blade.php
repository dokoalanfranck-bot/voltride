<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - VoltRide</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
        }
        
        .logo {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--dark);
            margin: 0 auto 16px;
            box-shadow: 0 0 30px rgba(0, 255, 106, 0.3);
        }
        
        .logo h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--white);
            letter-spacing: -1px;
        }
        
        .logo h1 span {
            color: var(--primary);
        }
        
        .logo p {
            color: var(--gray);
            margin-top: 8px;
            line-height: 1.6;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            color: var(--white);
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        
        .form-input {
            width: 100%;
            padding: 14px 16px;
            background: var(--dark-lighter);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            font-size: 1rem;
            color: var(--white);
            transition: all 0.3s ease;
            font-family: inherit;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 255, 106, 0.2);
        }
        
        .form-input::placeholder {
            color: var(--gray);
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
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 255, 106, 0.3);
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .alert-success {
            background: rgba(0, 255, 106, 0.1);
            border: 1px solid rgba(0, 255, 106, 0.3);
            color: var(--primary);
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .login-link {
            text-align: center;
            margin-top: 24px;
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
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
            <div class="logo">
                <div class="logo-icon">🔑</div>
                <h1>Volt<span>Ride</span></h1>
                <p>Entrez votre email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
            </div>

            @if (session('status'))
                <div class="alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="form-input" placeholder="votre@email.com" autofocus>
                </div>

                <button type="submit" class="btn-submit">
                    Envoyer le lien →
                </button>
            </form>

            <div class="login-link">
                <a href="{{ route('login') }}">← Retour à la connexion</a>
            </div>
        </div>
        
        <div class="back-home">
            <a href="{{ url('/') }}">← Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>
