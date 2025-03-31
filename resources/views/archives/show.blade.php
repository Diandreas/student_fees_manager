<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">
                {{ __('Détails de l\'archive') }} - {{ $archive->academic_year }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('archives.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour à la liste
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Informations générales -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-3 md:col-span-2">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dl class="grid grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Année académique</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $archive->academic_year }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Date de génération</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $archive->created_at->format('d/m/Y H:i') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Taille du fichier</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $archive->formatted_file_size }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Créé par</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $archive->creator ? $archive->creator->name : 'Système' }}</dd>
                                    </div>
                                </dl>
                            </div>
                            
                            <div>
                                <dl class="grid grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Nombre d'étudiants</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $archive->students_count }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Total facturé</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ number_format($archive->total_invoiced, 0, ',', ' ') }} FCFA</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Total payé</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ number_format($archive->total_paid, 0, ',', ' ') }} FCFA</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Montant restant</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ number_format($archive->total_remaining, 0, ',', ' ') }} FCFA</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        @if ($archive->notes)
                            <div class="mt-6">
                                <dt class="text-sm font-medium text-gray-500">Notes</dt>
                                <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $archive->notes }}</dd>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-3 md:col-span-1">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <a href="{{ route('archives.download', $archive) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Télécharger le fichier Excel
                                </a>
                            </div>
                            
                            <div>
                                <button type="button" onclick="document.getElementById('cleanup-modal').classList.remove('hidden')" class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Nettoyer les données
                                </button>
                            </div>
                            
                            <div>
                                <form method="POST" action="{{ route('archives.destroy', $archive) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette archive ? Cette action est irréversible.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Supprimer l'archive
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation pour le nettoyage des données -->
    <div id="cleanup-modal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Nettoyer les données
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Vous êtes sur le point de nettoyer les données de paiement et de facturation pour l'année académique <strong>{{ $archive->academic_year }}</strong>. Cette action supprimera toutes les données détaillées de paiement et de facturation, mais conservera les informations des étudiants.
                                </p>
                                <p class="mt-2 text-sm text-gray-500">
                                    <strong class="text-red-600">Cette action est irréversible.</strong> Assurez-vous d'avoir téléchargé le fichier Excel avant de procéder.
                                </p>
                                
                                <form method="POST" action="{{ route('archives.cleanup', $archive) }}" id="cleanup-form" class="mt-4">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="confirmation" class="block text-sm font-medium text-gray-700">Pour confirmer, tapez "CONFIRMER" ci-dessous</label>
                                        <input type="text" name="confirmation" id="confirmation" class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="CONFIRMER" required>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="document.getElementById('cleanup-form').submit()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Nettoyer les données
                    </button>
                    <button type="button" onclick="document.getElementById('cleanup-modal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 