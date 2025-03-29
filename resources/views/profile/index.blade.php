@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 flex flex-col sm:flex-row justify-between items-center gap-4">
                <h1 class="text-xl font-bold text-primary-600 flex items-center">
                    <i class="fas fa-user-circle mr-2"></i>Mon profil
                </h1>
                <a href="{{ route('dashboard') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Menu de navigation -->
        <div>
            <div class="bg-white rounded-xl shadow-sm overflow-hidden sticky top-6">
                <nav class="flex flex-col">
                    <a href="#personal" class="flex items-center gap-3 p-4 border-l-4 border-primary-500 bg-primary-50 text-primary-700 font-medium">
                        <i class="fas fa-user w-5 text-center"></i>
                        <span>Informations personnelles</span>
                    </a>
                    <a href="#security" class="flex items-center gap-3 p-4 border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300">
                        <i class="fas fa-lock w-5 text-center"></i>
                        <span>Sécurité</span>
                    </a>
                    <a href="#preferences" class="flex items-center gap-3 p-4 border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300">
                        <i class="fas fa-sliders-h w-5 text-center"></i>
                        <span>Préférences</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Contenu des sections -->
        <div class="lg:col-span-3">
            <!-- Informations personnelles -->
            <div id="personal" class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="border-b border-gray-100 p-5">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-user mr-2"></i>Informations personnelles
                    </h5>
                </div>
                <div class="p-5">
                    @if(session('profile_success'))
                        <div class="bg-green-50 text-green-800 rounded-lg p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm">{{ session('profile_success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6 text-center">
                            <div class="relative inline-block">
                                <img src="{{ auth()->user()->avatar ? asset('storage/avatars/' . auth()->user()->avatar) : asset('images/default-avatar.png') }}" 
                                     alt="Avatar" class="h-24 w-24 rounded-full object-cover border-2 border-gray-200">
                                <label for="avatar" class="absolute bottom-0 right-0 bg-primary-600 text-white rounded-full p-2 cursor-pointer shadow-sm hover:bg-primary-700 transition-all">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*">
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Cliquez sur l'icône pour changer votre photo de profil</p>
                            @error('avatar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                                <input type="text" id="name" name="name" value="{{ auth()->user()->name }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse e-mail</label>
                                <input type="email" id="email" name="email" value="{{ auth()->user()->email }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                                <input type="tel" id="phone" name="phone" value="{{ auth()->user()->phone ?? '' }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="job_title" class="block text-sm font-medium text-gray-700 mb-1">Fonction</label>
                                <input type="text" id="job_title" name="job_title" value="{{ auth()->user()->job_title ?? '' }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('job_title') border-red-500 @enderror">
                                @error('job_title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                            <textarea id="address" name="address" rows="2"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('address') border-red-500 @enderror">{{ auth()->user()->address ?? '' }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Biographie</label>
                            <textarea id="bio" name="bio" rows="3"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('bio') border-red-500 @enderror">{{ auth()->user()->bio ?? '' }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Une brève description de vous-même</p>
                            @error('bio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Sécurité -->
            <div id="security" class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="border-b border-gray-100 p-5">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-lock mr-2"></i>Sécurité
                    </h5>
                </div>
                <div class="p-5">
                    @if(session('password_success'))
                        <div class="bg-green-50 text-green-800 rounded-lg p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm">{{ session('password_success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                            <input type="password" id="current_password" name="current_password"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('current_password') border-red-500 @enderror">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                            <input type="password" id="password" name="password"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('password') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Minimum 8 caractères, incluant des lettres, des chiffres et des caractères spéciaux</p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-lock mr-2"></i>Changer le mot de passe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Préférences -->
            <div id="preferences" class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="border-b border-gray-100 p-5">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-sliders-h mr-2"></i>Préférences
                    </h5>
                </div>
                <div class="p-5">
                    @if(session('preferences_success'))
                        <div class="bg-green-50 text-green-800 rounded-lg p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm">{{ session('preferences_success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('profile.preferences') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Langue de l'interface</label>
                            <select id="language" name="language"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                <option value="fr" {{ auth()->user()->language == 'fr' ? 'selected' : '' }}>Français</option>
                                <option value="en" {{ auth()->user()->language == 'en' ? 'selected' : '' }}>English</option>
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <h6 class="font-medium text-gray-700 mb-3">Notifications</h6>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" id="email_notifications" name="email_notifications" value="1" {{ auth()->user()->email_notifications ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="email_notifications" class="font-medium text-gray-700">Recevoir des notifications par e-mail</label>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" id="browser_notifications" name="browser_notifications" value="1" {{ auth()->user()->browser_notifications ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="browser_notifications" class="font-medium text-gray-700">Activer les notifications du navigateur</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 