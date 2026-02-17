<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - ScooterRent</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900">Forgot your password?</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Enter your email address and we'll send you a link to reset your password.
                </p>
            </div>

            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4">
                    <p class="text-sm text-green-700">{{ session('status') }}</p>
                </div>
            @endif

            <form class="mt-8 space-y-6" action="{{ route('password.email') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="rounded-md bg-red-50 p-4">
                        <p class="text-sm text-red-700">{{ $errors->first('email') }}</p>
                    </div>
                @endif

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                    <input id="email" name="email" type="email" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="Email address" value="{{ old('email') }}">
                </div>

                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Send Password Reset Link
                </button>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">Back to login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
