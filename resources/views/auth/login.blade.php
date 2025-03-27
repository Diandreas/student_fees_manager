@extends('layouts.app')

@section('content')
<div class="flex min-h-screen items-center justify-center bg-gray-50">
    <div class="w-full max-w-md">
        <div class="card shadow-lg">
            <div class="card-header bg-primary-600 py-6">
                <h2 class="text-xl font-bold text-center text-white">{{ __('Connexion') }}</h2>
            </div>

            <div class="card-body p-6">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Adresse email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                               class="form-input w-full @error('email') border-red-500 @enderror">

                        @error('email')
                            <span class="text-sm text-red-600" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Mot de passe') }}</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="form-input w-full @error('password') border-red-500 @enderror">

                        @error('password')
                            <span class="text-sm text-red-600" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="flex items-center">
                            <input class="rounded text-primary-600 focus:ring-primary-500" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="ml-2 text-sm text-gray-700" for="remember">
                                {{ __('Se souvenir de moi') }}
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col space-y-3">
                        <button type="submit" class="btn-primary w-full">
                            {{ __('Connexion') }}
                        </button>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-center text-primary-600 hover:underline" href="{{ route('password.request') }}">
                                {{ __('Mot de passe oublié?') }}
                            </a>
                        @endif
                        
                        @if (Route::has('register'))
                            <div class="text-center mt-4 text-sm text-gray-600">
                                {{ __('Pas encore de compte?') }} 
                                <a class="text-primary-600 font-medium hover:underline" href="{{ route('register') }}">
                                    {{ __('Inscrivez-vous') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
