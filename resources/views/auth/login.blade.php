@extends('layouts.app')

@section('content')
<div class="flex min-h-screen items-center justify-center bg-gray-50 py-12">
    <div class="w-full max-w-lg">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-primary-600 py-6">
                <h2 class="text-xl font-bold text-center text-white">{{ __('Connexion') }}</h2>
            </div>

            <div class="p-6">
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

                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" name="remember" id="remember" class="form-checkbox h-4 w-4 text-primary-600"
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                {{ __('Se souvenir de moi') }}
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-primary-600 hover:text-primary-800" href="{{ route('password.request') }}">
                                {{ __('Mot de passe oublié ?') }}
                            </a>
                        @endif
                    </div>

                    <div class="flex flex-col space-y-3">
                        <button type="submit" class="btn-primary w-full">
                            {{ __('Se connecter') }}
                        </button>
                        
                        <div class="text-center mt-4 text-sm text-gray-600">
                            {{ __('Vous n\'avez pas encore de compte ?') }}
                            <a class="text-primary-600 font-medium hover:underline" href="{{ route('school.register') }}">
                                {{ __('Inscrivez votre établissement') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
