@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 flex flex-col sm:flex-row justify-between items-center gap-4">
                <h1 class="text-xl font-bold text-primary-600 flex items-center">
                    <i class="fas fa-toggle-on mr-2"></i>Modification du statut
                </h1>
                <a href="{{ route('schools.settings.index', $school) }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Retour aux paramètres
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="border-b border-gray-100 p-5">
            <h5 class="font-bold text-primary-600 flex items-center">
                <i class="fas fa-power-off mr-2"></i>Statut de l'établissement
            </h5>
        </div>
        <div class="p-5">
            <form action="{{ route('schools.settings.status', $school) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-5">
                    <div class="flex flex-col">
                        <div class="relative mb-4">
                            <div class="flex items-center">
                                <input id="status_active" name="status" type="radio" value="active" 
                                    {{ $school->status === 'active' ? 'checked' : '' }}
                                    class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-500">
                                <label for="status_active" class="ml-3 block text-sm font-medium text-gray-700">
                                    <span class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-2">
                                            <i class="fas fa-check-circle mr-1"></i>Actif
                                        </span>
                                        Établissement pleinement fonctionnel
                                    </span>
                                </label>
                            </div>
                            <p class="mt-1 ml-7 text-sm text-gray-500">L'accès est autorisé pour tous les utilisateurs et toutes les fonctionnalités sont disponibles.</p>
                        </div>
                        
                        <div class="relative mb-4">
                            <div class="flex items-center">
                                <input id="status_maintenance" name="status" type="radio" value="maintenance" 
                                    {{ $school->status === 'maintenance' ? 'checked' : '' }}
                                    class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-500">
                                <label for="status_maintenance" class="ml-3 block text-sm font-medium text-gray-700">
                                    <span class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mr-2">
                                            <i class="fas fa-tools mr-1"></i>Maintenance
                                        </span>
                                        Mode maintenance
                                    </span>
                                </label>
                            </div>
                            <p class="mt-1 ml-7 text-sm text-gray-500">Seuls les administrateurs peuvent accéder au système. Un message de maintenance sera affiché pour les autres utilisateurs.</p>
                        </div>
                        
                        <div class="relative">
                            <div class="flex items-center">
                                <input id="status_disabled" name="status" type="radio" value="disabled" 
                                    {{ $school->status === 'disabled' ? 'checked' : '' }}
                                    class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-500">
                                <label for="status_disabled" class="ml-3 block text-sm font-medium text-gray-700">
                                    <span class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-2">
                                            <i class="fas fa-ban mr-1"></i>Désactivé
                                        </span>
                                        Compte désactivé
                                    </span>
                                </label>
                            </div>
                            <p class="mt-1 ml-7 text-sm text-gray-500">L'établissement est entièrement désactivé. Personne ne peut y accéder, y compris les administrateurs.</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <div class="mt-2">
                        <label for="maintenance_message" class="block text-sm font-medium text-gray-700 mb-1">Message de maintenance</label>
                        <textarea id="maintenance_message" name="maintenance_message" rows="3" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">{{ old('maintenance_message', $school->maintenance_message) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Ce message sera affiché aux utilisateurs lorsque l'établissement est en mode maintenance.</p>
                    </div>
                </div>
                
                <div class="flex justify-end mt-4">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>Mettre à jour le statut
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 