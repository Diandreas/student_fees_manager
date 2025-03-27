@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="card">
            <div class="card-body flex flex-col sm:flex-row justify-between items-center p-4">
                <h1 class="text-xl font-bold text-primary-600 mb-4 sm:mb-0">
                    <i class="fas fa-school mr-2"></i>Gestion des campus
                </h1>
                <a href="{{ route('campuses.create') }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>Ajouter un campus
                </a>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <div class="card">
            <div class="card-header">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <h5 class="font-bold text-primary-600 mb-4 md:mb-0">
                        Liste des campus
                    </h5>
                    <form class="w-full md:w-auto" action="{{ route('campuses.index') }}" method="GET">
                        <div class="flex">
                            <input type="text" class="form-input rounded-r-none" name="search" value="{{ request()->search }}" placeholder="Rechercher un campus..." aria-label="Rechercher">
                            <button class="px-4 py-2 bg-primary-600 text-white rounded-l-none rounded-r border-l-0 hover:bg-primary-700" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            @if(request()->has('search') && !empty(request()->search))
                            <a href="{{ route('campuses.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-r border-l-0 hover:bg-gray-600">
                                <i class="fas fa-times"></i>
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="flex justify-end px-4 pt-3">
                <div class="flex items-center mb-3 rounded p-1 bg-gray-100">
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
            
            <div id="campusesContainer" class="mode-list">
                <!-- Mode Liste -->
                <div class="mode-list-content">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre de filières</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($campuses as $campus)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $campus->name }}</td>
                                    <td class="px-6 py-4">{{ Str::limit($campus->description, 100) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-primary-100 text-primary-800">
                                            {{ $campus->fields_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex justify-end space-x-1">
                                            <a href="{{ route('campuses.show', $campus->id) }}" class="px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200" title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('campuses.edit', $campus->id) }}" class="px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200" onclick="document.getElementById('deleteModal{{ $campus->id }}').classList.remove('hidden')" title="Supprimer">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Modal de suppression -->
                                        <div id="deleteModal{{ $campus->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" tabindex="-1">
                                            <div class="bg-white rounded-lg max-w-md w-full mx-4">
                                                <div class="border-b px-4 py-3 flex justify-between items-center">
                                                    <h5 class="font-bold text-lg">Confirmation de suppression</h5>
                                                    <button type="button" class="text-gray-500 hover:text-gray-700" onclick="document.getElementById('deleteModal{{ $campus->id }}').classList.add('hidden')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="p-4 text-left">
                                                    <p>Êtes-vous sûr de vouloir supprimer le campus <strong>{{ $campus->name }}</strong> ?</p>
                                                    <p class="text-red-600 mt-2 text-sm">Cette action est irréversible et supprimera également toutes les filières associées.</p>
                                                </div>
                                                <div class="border-t px-4 py-3 flex justify-end space-x-2">
                                                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300" onclick="document.getElementById('deleteModal{{ $campus->id }}').classList.add('hidden')">Annuler</button>
                                                    <form action="{{ route('campuses.destroy', $campus->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Supprimer</button>
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
                                            <i class="fas fa-school text-5xl text-gray-300 mb-4"></i>
                                            <h5 class="text-lg font-medium text-gray-500 mb-2">Aucun campus trouvé</h5>
                                            <p class="text-gray-400 mb-4">
                                                @if(request()->has('search') && !empty(request()->search))
                                                    Aucun résultat pour la recherche "{{ request()->search }}"
                                                    <br><a href="{{ route('campuses.index') }}" class="text-primary-600 hover:underline">Voir tous les campus</a>
                                                @else
                                                    Commencez par ajouter un campus
                                                @endif
                                            </p>
                                            @if(!request()->has('search'))
                                            <a href="{{ route('campuses.create') }}" class="btn-primary">
                                                <i class="fas fa-plus mr-2"></i>Ajouter un campus
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
                <div class="mode-card-content" style="display: none;">
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4">
                        @forelse($campuses as $campus)
                            <div class="card h-full">
                                <div class="card-body">
                                    <div class="flex justify-between items-start mb-3">
                                        <h5 class="font-bold text-lg">{{ $campus->name }}</h5>
                                        <span class="px-2 py-1 bg-primary-100 text-primary-800 text-xs font-semibold rounded-full">
                                            <i class="fas fa-layer-group mr-1"></i> {{ $campus->fields_count }}
                                        </span>
                                    </div>
                                    <p class="text-gray-500 mb-4">{{ $campus->description ? Str::limit($campus->description, 150) : 'Aucune description' }}</p>
                                    <div class="flex justify-between mt-auto">
                                        <a href="{{ route('campuses.show', $campus->id) }}" class="px-3 py-1 bg-primary-600 text-white text-sm rounded hover:bg-primary-700">
                                            <i class="fas fa-eye mr-1"></i> Détails
                                        </a>
                                        <a href="{{ route('campuses.edit', $campus->id) }}" class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                                            <i class="fas fa-edit mr-1"></i> Modifier
                                        </a>
                                        <button type="button" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700" onclick="document.getElementById('deleteModal{{ $campus->id }}').classList.remove('hidden')">
                                            <i class="fas fa-trash-alt mr-1"></i> Supprimer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center">
                                <i class="fas fa-school text-5xl text-gray-300 mb-4"></i>
                                <h5 class="text-lg font-medium text-gray-500 mb-2">Aucun campus trouvé</h5>
                                <p class="text-gray-400 mb-4">
                                    @if(request()->has('search') && !empty(request()->search))
                                        Aucun résultat pour la recherche "{{ request()->search }}"
                                        <br><a href="{{ route('campuses.index') }}" class="text-primary-600 hover:underline">Voir tous les campus</a>
                                    @else
                                        Commencez par ajouter un campus
                                    @endif
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            @if($campuses->count() > 0)
            <div class="card-footer">
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <div class="mb-4 sm:mb-0">
                        <p class="text-sm text-gray-500">Affichage de <span class="font-medium">{{ $campuses->firstItem() ?? 0 }}</span> à <span class="font-medium">{{ $campuses->lastItem() ?? 0 }}</span> sur <span class="font-medium">{{ $campuses->total() }}</span> campus</p>
                    </div>
                    <div>
                        {{ $campuses->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion du mode d'affichage (liste ou cartes)
        const container = document.getElementById('campusesContainer');
        const buttons = document.querySelectorAll('.mode-button');
        
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const mode = this.getAttribute('data-mode');
                
                // Mise à jour de la classe sur le conteneur
                container.className = 'mode-' + mode;
                
                // Style des boutons
                buttons.forEach(btn => {
                    const btnMode = btn.getAttribute('data-mode');
                    if (btnMode === mode) {
                        btn.classList.remove('border', 'border-primary-600', 'text-primary-600');
                        btn.classList.add('bg-primary-600', 'text-white');
                    } else {
                        btn.classList.remove('bg-primary-600', 'text-white');
                        btn.classList.add('border', 'border-primary-600', 'text-primary-600');
                    }
                });
                
                // Affichage du contenu
                if (mode === 'list') {
                    document.querySelector('.mode-list-content').style.display = 'block';
                    document.querySelector('.mode-card-content').style.display = 'none';
                } else {
                    document.querySelector('.mode-list-content').style.display = 'none';
                    document.querySelector('.mode-card-content').style.display = 'block';
                }
            });
        });
    });
</script>
@endpush

@endsection
