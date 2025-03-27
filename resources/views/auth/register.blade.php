@extends('layouts.app')

@section('content')
<div class="flex min-h-screen items-center justify-center bg-gray-50 py-12">
    <div class="w-full max-w-lg">
        <div class="card shadow-lg">
            <div class="card-header bg-primary-600 py-6">
                <h2 class="text-xl font-bold text-center text-white">{{ __('Inscription') }}</h2>
            </div>

            <div class="card-body p-6">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nom complet') }}</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                               class="form-input w-full @error('name') border-red-500 @enderror">
                        @error('name')
                            <span class="text-sm text-red-600" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Adresse email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                               class="form-input w-full @error('email') border-red-500 @enderror">
                        @error('email')
                            <span class="text-sm text-red-600" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Numéro de téléphone') }}</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required autocomplete="tel"
                               class="form-input w-full @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <span class="text-sm text-red-600" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Mot de passe') }}</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                               class="form-input w-full @error('password') border-red-500 @enderror">
                        @error('password')
                            <span class="text-sm text-red-600" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Confirmer le mot de passe') }}</label>
                        <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                               class="form-input w-full">
                    </div>

                    <div class="flex flex-col space-y-3">
                        <button type="submit" class="btn-primary w-full">
                            {{ __('S\'inscrire') }}
                        </button>
                        
                        <div class="text-center mt-4 text-sm text-gray-600">
                            {{ __('Vous avez déjà un compte?') }}
                            <a class="text-primary-600 font-medium hover:underline" href="{{ route('login') }}">
                                {{ __('Connectez-vous') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
