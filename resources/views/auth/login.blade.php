<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200">
    <div class="flex items-center justify-center min-h-screen bg-cover" style="background-image: url('');">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
            <div class="text-center mb-6">
                <svg class="w-12 h-12 mx-auto text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22.5C6.201 22.5 1.5 17.799 1.5 12S6.201 1.5 12 1.5 22.5 6.201 22.5 12 17.799 22.5 12 22.5zM11.25 6.75c-.414 0-.75.336-.75.75v4.5c0 .414.336.75.75.75s.75-.336.75-.75v-4.5c0-.414-.336-.75-.75-.75zm0 9c-.414 0-.75.336-.75.75v.75c0 .414.336.75.75.75s.75-.336.75-.75v-.75c0-.414-.336-.75-.75-.75z"/>
                </svg>
                <h2 class="text-2xl font-bold text-gray-800 mt-4">Welcome</h2>
                <p class="text-gray-600">Please login to your account</p>
            </div>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full px-4 py-2 bg-gray-100 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full px-4 py-2 bg-gray-100 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-600 hover:underline" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>
                <div>
                    <button type="submit" class="w-full py-3 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-md transition duration-200">{{ __('Log in') }}</button>
                </div>
            </form>
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">{{ __("Don't have an account?") }} <a href="{{ route('register') }}" class="text-blue-600 hover:underline">{{ __('Register') }}</a></p>
            </div>
        </div>
    </div>
</body>
</html>
