<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - ScooterRent</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900">Verify your email</h2>
                <p class="mt-2 text-sm text-gray-600">
                    We've sent a verification link to your email address.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="rounded-md bg-green-50 p-4">
                    <p class="text-sm text-green-700">A new verification link has been sent to your email.</p>
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-center text-blue-600 hover:text-blue-500">
                    Sign out
                </button>
            </form>
        </div>
    </div>
</body>
</html>
