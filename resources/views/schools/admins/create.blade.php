@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="font-medium text-gray-700 text-lg">Ajouter un administrateur pour {{ $school->name }}</h5>
        </div>
        
        <div class="p-6">
            <form action="{{ route('schools.admins.store', $school) }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Vous pouvez ajouter un utilisateur existant comme administrateur ou créer un nouveau compte.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sélection du type d'ajout -->
                <div class="mb-6">
                    <div class="flex items-center space-x-6">
                        <label class="inline-flex items-center">
                            <input type="radio" name="admin_type" value="existing" class="form-radio text-primary-600" checked>
                            <span class="ml-2">Utilisateur existant</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="admin_type" value="new" class="form-radio text-primary-600">
                            <span class="ml-2">Nouvel utilisateur</span>
                        </label>
                    </div>
                </div>
                
                <!-- Section utilisateur existant -->
                <div id="existing-user-section">
                    <div class="mb-4">
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Sélectionner un utilisateur</label>
                        <select name="user_id" id="user_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('user_id') border-red-500 @enderror">
                            <option value="">Sélectionner un utilisateur</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Section nouvel utilisateur -->
                <div id="new-user-section" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                            <input type="text" name="name" id="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('name') border-red-500 @enderror" value="{{ old('name') }}">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
                            <input type="email" name="email" id="email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('email') border-red-500 @enderror" value="{{ old('email') }}">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                            <input type="password" name="password" id="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="send_invitation" id="send_invitation" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" checked>
                            <label for="send_invitation" class="ml-2 block text-sm text-gray-700">Envoyer un email d'invitation</label>
                        </div>
                    </div>
                </div>
                
                <!-- Section commune -->
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                    <select name="role" id="role" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('role') border-red-500 @enderror">
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                        <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Gestionnaire</option>
                        <option value="finance" {{ old('role') == 'finance' ? 'selected' : '' }}>Finance</option>
                        <option value="secretary" {{ old('role') == 'secretary' ? 'selected' : '' }}>Secrétariat</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('schools.show', $school) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">Annuler</a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">Ajouter l'administrateur</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const adminTypeRadios = document.querySelectorAll('input[name="admin_type"]');
            const existingUserSection = document.getElementById('existing-user-section');
            const newUserSection = document.getElementById('new-user-section');
            
            // Fonction pour basculer entre les sections
            function toggleSections() {
                const selectedType = document.querySelector('input[name="admin_type"]:checked').value;
                
                if (selectedType === 'existing') {
                    existingUserSection.classList.remove('hidden');
                    newUserSection.classList.add('hidden');
                } else {
                    existingUserSection.classList.add('hidden');
                    newUserSection.classList.remove('hidden');
                }
            }
            
            // Écouter les changements sur les boutons radio
            adminTypeRadios.forEach(radio => {
                radio.addEventListener('change', toggleSections);
            });
            
            // Initialiser l'état
            toggleSections();
        });
    </script>
@endsection 