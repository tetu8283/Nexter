<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-container">

        <!-- Session Status -->
        <div class="auth-session-status mb-4">
            {{ session('status') }}
        </div>

        <h2>ログイン</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-item">
                <label for="email">Email</label>
                <input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                <div class="input-error mt-2">
                    {{ $errors->first('email') }}
                </div>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <label for="password">Password</label>
                <input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password">
                <div class="input-error mt-2">
                    {{ $errors->first('password') }}
                </div>
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>
                @endif

                <button type="submit" class="ms-3 primary-button">
                    Log in
                </button>
            </div>
        </form>
    </div>
</body>
</html>
