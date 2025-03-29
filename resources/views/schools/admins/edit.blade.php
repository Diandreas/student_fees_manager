@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="font-medium text-gray-700 text-lg">Modifier les droits d'accès de {{ $admin->name }}</h5>
        </div>
        
        <div class="p-6">
            <form action="{{ route('schools.admins.update', [$school, $admin]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Vous pouvez modifier les droits d'accès de cet administrateur pour {{ $school->name }}. Tous les administrateurs ont un accès complet à la gestion de l'école.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="user_name" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'utilisateur</label>
                    <input type="text" id="user_name" value="{{ $admin->name }}" class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm sm:text-sm" disabled>
                </div>
                
                <div class="mb-4">
                    <label for="user_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="user_email" value="{{ $admin->email }}" class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm sm:text-sm" disabled>
                </div>
                
                <!-- Champ caché pour conserver le rôle -->
                <input type="hidden" name="role" value="admin">
                
                <div class="mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" {{ $schoolAdmin->is_active ?? '' ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">Accès actif</label>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Décochez pour désactiver temporairement l'accès de cet administrateur.</p>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('schools.show', $school) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">Annuler</a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
@endsection 