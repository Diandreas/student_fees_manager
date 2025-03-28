@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-primary-600 py-6">
                <h4 class="text-xl font-bold text-center text-white">Inscription de votre établissement scolaire</h4>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('school.register') }}">
                    @csrf

                    <div class="mb-8">
                        <h5 class="text-primary-600 border-b border-primary-200 pb-2 mb-4 text-lg font-semibold">Informations sur l'établissement</h5>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="school_name" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'établissement</label>
                                <input id="school_name" type="text" name="school_name" value="{{ old('school_name') }}" required autocomplete="school_name" autofocus
                                       class="form-input w-full @error('school_name') border-red-500 @enderror">
                                @error('school_name')
                                    <span class="text-sm text-red-600 mt-1" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <label for="school_email" class="block text-sm font-medium text-gray-700 mb-1">Email de l'établissement</label>
                                <input id="school_email" type="email" name="school_email" value="{{ old('school_email') }}" required autocomplete="email"
                                       class="form-input w-full @error('school_email') border-red-500 @enderror">
                                @error('school_email')
                                    <span class="text-sm text-red-600 mt-1" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <label for="school_phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone de l'établissement</label>
                                <input id="school_phone" type="text" name="school_phone" value="{{ old('school_phone') }}" autocomplete="tel"
                                       class="form-input w-full @error('school_phone') border-red-500 @enderror">
                                @error('school_phone')
                                    <span class="text-sm text-red-600 mt-1" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <label for="school_address" class="block text-sm font-medium text-gray-700 mb-1">Adresse de l'établissement</label>
                                <input id="school_address" type="text" name="school_address" value="{{ old('school_address') }}" autocomplete="address"
                                       class="form-input w-full @error('school_address') border-red-500 @enderror">
                                @error('school_address')
                                    <span class="text-sm text-red-600 mt-1" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h5 class="text-primary-600 border-b border-primary-200 pb-2 mb-4 text-lg font-semibold">Compte administrateur principal</h5>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name"
                                       class="form-input w-full @error('name') border-red-500 @enderror">
                                @error('name')
                                    <span class="text-sm text-red-600 mt-1" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email administrateur</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                                       class="form-input w-full @error('email') border-red-500 @enderror">
                                @error('email')
                                    <span class="text-sm text-red-600 mt-1" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                                <input id="password" type="password" name="password" required autocomplete="new-password"
                                       class="form-input w-full @error('password') border-red-500 @enderror">
                                @error('password')
                                    <span class="text-sm text-red-600 mt-1" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                                <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                                       class="form-input w-full">
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        En vous inscrivant, vous acceptez nos 
                                        <a href="#" class="font-medium text-blue-800 hover:text-blue-900 underline">conditions d'utilisation</a> 
                                        et notre 
                                        <a href="#" class="font-medium text-blue-800 hover:text-blue-900 underline">politique de confidentialité</a>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-150">
                            <i class="fas fa-school mr-2"></i>
                            Inscrire mon établissement
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 text-center">
                <p class="text-sm text-gray-600">
                    Vous avez déjà un compte ? 
                    <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-800">
                        Connectez-vous
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection 