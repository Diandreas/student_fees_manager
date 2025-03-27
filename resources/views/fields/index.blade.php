@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="mb-6">
        <div class="card">
            <div class="card-body flex justify-between items-center">
                <h1 class="text-xl font-bold text-primary-600">
                    <i class="fas fa-graduation-cap mr-2"></i>Gestion des filières
                </h1>
                <a href="{{ route('fields.create') }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>Ajouter une filière
                </a>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <div class="card">
            <div class="card-header">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-3 md:space-y-0">
                    <h5 class="font-bold text-primary-600">
                        Liste des filières
                    </h5>
                    <form class="flex" action="{{ route('fields.index') }}" method="GET">
                        <div class="flex w-full md:w-auto">
                            <input type="text" class="form-input rounded-r-none" name="search" value="{{ request()->search }}" placeholder="Rechercher une filière...">
                            <button class="bg-primary-600 hover:bg-primary-700 text-white px-3 rounded-l-none rounded-r-md" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            @if(request()->has('search') && !empty(request()->search))
                            <a href="{{ route('fields.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 rounded-md ml-1">
                                <i class="fas fa-times"></i>
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="flex justify-end px-4 pt-3">
                <div class="flex items-center p-1 bg-gray-100 rounded mb-3">
                    <button type="button" class="px-3 py-1 bg-primary-600 text-white rounded flex items-center justify-center mr-1 mode-button" data-mode="list">
                        <i class="fas fa-list"></i>
                        <span class="ml-1 hidden sm:inline">Liste</span>
                    </button>
                    <button type="button" class="px-3 py-1 border border-primary-600 text-primary-600 rounded flex items-center justify-center ml-1 mode-button" data-mode="card">
                        <i class="fas fa-grip-horizontal"></i>
                        <span class="ml-1 hidden sm:inline">Cartes</span>
                    </button>
                </div>
            </div>
            
            <div class="card-body">
                @if($fields->isEmpty())
                    <div class="py-12 flex flex-col items-center">
                        <img src="{{ asset('images/empty-state.svg') }}" alt="Aucune filière" class="w-48 h-auto mb-4">
                        <h5 class="text-lg font-bold mb-1">Aucune filière trouvée</h5>
                        <p class="text-gray-500 mb-4">Il n'y a aucune filière à afficher pour le moment.</p>
                        <a href="{{ route('fields.create') }}" class="btn-primary">
                            <i class="fas fa-plus mr-2"></i>Ajouter une filière
                        </a>
                    </div>
                @else
                    <div id="fieldsContainer" class="mode-list">
                        <!-- Mode Liste -->
                        <div class="mode-list-content block">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campus</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Frais de scolarité</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($fields as $field)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $field->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 bg-primary-100 text-primary-800 rounded-full">
                                                    {{ $field->campus->name }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap font-bold">
                                                {{ number_format($field->fees, 0, ',', ' ') }} FCFA
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="flex justify-end space-x-1">
                                                    <a href="{{ route('fields.show', $field->id) }}" class="px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200" title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('fields.edit', $field->id) }}" class="px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200" 
                                                            onclick="document.getElementById('deleteModal{{ $field->id }}').classList.remove('hidden')" title="Supprimer">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                                
                                                <!-- Modal de suppression -->
                                                <div id="deleteModal{{ $field->id }}" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
                                                    <div class="relative bg-white rounded-lg max-w-md w-full mx-auto p-6">
                                                        <div class="flex justify-between items-center mb-4">
                                                            <h5 class="text-lg font-bold">Confirmation de suppression</h5>
                                                            <button type="button" class="text-gray-400 hover:text-gray-600" 
                                                                    onclick="document.getElementById('deleteModal{{ $field->id }}').classList.add('hidden')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                        <div class="text-left">
                                                            <p>Êtes-vous sûr de vouloir supprimer la filière <strong>{{ $field->name }}</strong> ?</p>
                                                            <p class="text-red-600 my-2"><small>Cette action est irréversible.</small></p>
                                                        </div>
                                                        <div class="flex justify-end space-x-2 mt-4">
                                                            <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
                                                                    onclick="document.getElementById('deleteModal{{ $field->id }}').classList.add('hidden')">
                                                                Annuler
                                                            </button>
                                                            <form action="{{ route('fields.destroy', $field->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                                                    Supprimer
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center">
                                                    <i class="fas fa-graduation-cap text-5xl text-gray-400 mb-4"></i>
                                                    <h5 class="text-lg font-bold mb-1">Aucune filière trouvée</h5>
                                                    <p class="text-gray-500 mb-4">
                                                        @if(request()->has('search') && !empty(request()->search))
                                                            Aucun résultat pour la recherche "{{ request()->search }}"
                                                            <br><a href="{{ route('fields.index') }}" class="text-primary-600 hover:underline">Voir toutes les filières</a>
                                                        @else
                                                            Commencez par ajouter une filière
                                                        @endif
                                                    </p>
                                                    @if(!request()->has('search'))
                                                    <a href="{{ route('fields.create') }}" class="btn-primary">
                                                        <i class="fas fa-plus mr-2"></i>Ajouter une filière
                                                    </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Mode Cartes -->
                        <div class="mode-card-content hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-3">
                                @foreach($fields as $field)
                                <div class="card hover-lift">
                                    <div class="card-body">
                                        <div class="flex justify-between items-center mb-3">
                                            <h5 class="text-lg font-bold text-primary-600">{{ $field->name }}</h5>
                                            <span class="px-2 py-1 bg-primary-100 text-primary-800 text-xs font-medium rounded-full">{{ $field->educationLevel?->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
                                            <div class="col-span-3">
                                                <p class="mb-1 flex items-center"><i class="fas fa-school text-primary-600 mr-2 w-5"></i>{{ $field->campus?->name ?? 'N/A' }}</p>
                                                <p class="mb-1 flex items-center"><i class="fas fa-money-bill-wave text-primary-600 mr-2 w-5"></i>{{ number_format($field->annual_fees, 0, ',', ' ') }} FCFA</p>
                                                <p class="mb-1 flex items-center"><i class="fas fa-calendar text-primary-600 mr-2 w-5"></i>{{ $field->duration }} an(s)</p>
                                            </div>
                                            <div class="flex justify-center items-center">
                                                <div class="w-16 h-16 rounded-full bg-primary-100 flex flex-col items-center justify-center">
                                                    <div class="font-bold text-primary-600">{{ $field->students_count }}</div>
                                                    <div class="text-xs text-primary-600">Étudiants</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('fields.show', $field) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                                <i class="fas fa-eye mr-1"></i>Voir
                                            </a>
                                            <a href="{{ route('fields.edit', $field) }}" class="px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200">
                                                <i class="fas fa-edit mr-1"></i>Modifier
                                            </a>
                                            <a href="{{ route('fields.report', $field) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                                <i class="fas fa-chart-bar mr-1"></i>Rapport
                                            </a>
                                            <button type="button" class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200" 
                                                onclick="document.getElementById('delete-field-{{ $field->id }}-card').submit()">
                                                <i class="fas fa-trash mr-1"></i>Supprimer
                                            </button>
                                            <form id="delete-field-{{ $field->id }}-card" method="POST" action="{{ route('fields.destroy', $field) }}" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            @if($fields->count() > 0)
            <div class="card-footer">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0">
                    <div>
                        <p class="text-gray-500 mb-0">Affichage de <span class="font-medium">{{ $fields->firstItem() ?? 0 }}</span> à <span class="font-medium">{{ $fields->lastItem() ?? 0 }}</span> sur <span class="font-medium">{{ $fields->total() }}</span> filières</p>
                    </div>
                    <div class="pagination-container">
                        {{ $fields->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Styles pour les différents modes d'affichage */
    .mode-list .mode-list-content { display: block; }
    .mode-list .mode-card-content { display: none; }
    
    .mode-card .mode-list-content { display: none; }
    .mode-card .mode-card-content { display: block; }
    
    /* Style pour la pagination */
    .pagination-container nav div:first-child {
        @apply hidden;
    }
    
    .pagination-container nav > div:last-child > span,
    .pagination-container nav > div:last-child a {
        @apply inline-flex items-center px-4 py-2 mx-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50;
    }
    
    .pagination-container nav > div:last-child > span.text-gray-400 {
        @apply text-gray-400 bg-gray-100;
    }
    
    .pagination-container nav > div:last-child > span.text-white {
        @apply bg-primary-600 text-white border-primary-600;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('fieldsContainer');
        const buttons = document.querySelectorAll('.mode-button');
        
        if (container && buttons.length) {
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const mode = this.getAttribute('data-mode');
                    
                    // Mettre à jour la classe du conteneur
                    container.className = `mode-${mode}`;
                    
                    // Mettre à jour l'apparence des boutons
                    buttons.forEach(btn => {
                        const btnMode = btn.getAttribute('data-mode');
                        
                        if (btnMode === mode) {
                            // Bouton actif
                            if (mode === 'list') {
                                btn.className = 'px-3 py-1 bg-primary-600 text-white rounded flex items-center justify-center mr-1 mode-button';
                            } else {
                                btn.className = 'px-3 py-1 bg-primary-600 text-white rounded flex items-center justify-center ml-1 mode-button';
                            }
                        } else {
                            // Bouton inactif
                            if (btnMode === 'list') {
                                btn.className = 'px-3 py-1 border border-primary-600 text-primary-600 rounded flex items-center justify-center mr-1 mode-button';
                            } else {
                                btn.className = 'px-3 py-1 border border-primary-600 text-primary-600 rounded flex items-center justify-center ml-1 mode-button';
                            }
                        }
                    });
                    
                    // Enregistrer la préférence dans le stockage local
                    localStorage.setItem('fieldsViewMode', mode);
                });
            });
            
            // Charger la préférence de mode depuis le stockage local
            const savedMode = localStorage.getItem('fieldsViewMode');
            if (savedMode) {
                const targetButton = document.querySelector(`.mode-button[data-mode="${savedMode}"]`);
                if (targetButton) {
                    targetButton.click();
                }
            }
        }
    });
</script>
@endpush
@endsection
