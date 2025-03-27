@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <h1 class="h3 fw-bold text-primary-custom mb-0">
                        <i class="fas fa-school me-2"></i>Gestion des campus
                    </h1>
                    <a href="{{ route('campuses.create') }}" class="btn btn-primary-custom">
                        <i class="fas fa-plus me-2"></i>Ajouter un campus
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0 fw-bold text-primary-custom">
                                Liste des campus
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <form class="d-flex" action="{{ route('campuses.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" value="{{ request()->search }}" placeholder="Rechercher un campus..." aria-label="Rechercher">
                                    <button class="btn btn-primary-custom" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request()->has('search') && !empty(request()->search))
                                    <a href="{{ route('campuses.index') }}" class="btn btn-secondary-custom">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end pe-4 pt-3">
                    <div class="display-mode-container d-flex align-items-center mb-3 rounded p-1 bg-light">
                        <button type="button" class="btn btn-sm btn-primary-custom mx-1 d-flex align-items-center justify-content-center mode-button" data-mode="list">
                            <i class="fas fa-list"></i>
                            <span class="ms-1 d-none d-sm-inline">Liste</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary-custom mx-1 d-flex align-items-center justify-content-center mode-button" data-mode="card">
                            <i class="fas fa-grip-horizontal"></i>
                            <span class="ms-1 d-none d-sm-inline">Cartes</span>
                        </button>
                    </div>
                </div>
                
                <div id="campusesContainer" class="mode-list">
                    <!-- Mode Liste -->
                    <div class="mode-list-content">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 table-custom">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col" class="ps-4">Nom</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Nombre de filières</th>
                                        <th scope="col" class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($campuses as $campus)
                                    <tr>
                                        <td class="ps-4 fw-medium">{{ $campus->name }}</td>
                                        <td>{{ Str::limit($campus->description, 100) }}</td>
                                        <td>
                                            <span class="badge bg-primary-custom bg-opacity-10 text-primary-custom py-2 px-3">
                                                {{ $campus->fields_count }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                <a href="{{ route('campuses.show', $campus->id) }}" class="btn btn-sm btn-outline-primary" title="Voir les détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('campuses.edit', $campus->id) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $campus->id }}" title="Supprimer">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                            
                                            <!-- Modal de suppression -->
                                            <div class="modal fade" id="deleteModal{{ $campus->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-start">
                                                            Êtes-vous sûr de vouloir supprimer le campus <strong>{{ $campus->name }}</strong> ?
                                                            <p class="text-danger mb-0 mt-2"><small>Cette action est irréversible et supprimera également toutes les filières associées.</small></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            <form action="{{ route('campuses.destroy', $campus->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Supprimer</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-school fa-3x text-muted mb-3"></i>
                                                <h5>Aucun campus trouvé</h5>
                                                <p class="text-muted">
                                                    @if(request()->has('search') && !empty(request()->search))
                                                        Aucun résultat pour la recherche "{{ request()->search }}"
                                                        <br><a href="{{ route('campuses.index') }}" class="text-primary-custom">Voir tous les campus</a>
                                                    @else
                                                        Commencez par ajouter un campus
                                                    @endif
                                                </p>
                                                @if(!request()->has('search'))
                                                <a href="{{ route('campuses.create') }}" class="btn btn-primary-custom">
                                                    <i class="fas fa-plus me-2"></i>Ajouter un campus
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
                        <div class="row g-3 p-3">
                            @forelse($campuses as $campus)
                                <div class="col-md-4 col-lg-3 list-item">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-header bg-transparent border-0 p-4">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="card-title mb-0 fw-bold">{{ $campus->name }}</h5>
                                                <span class="badge bg-primary-custom text-white py-2 px-3">
                                                    <i class="fas fa-layer-group me-1"></i> {{ $campus->fields_count }} filières
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body p-4 pt-0">
                                            <p class="text-muted">{{ $campus->description ? Str::limit($campus->description, 150) : 'Aucune description' }}</p>
                                        </div>
                                        <div class="card-footer bg-transparent p-4 border-0">
                                            <div class="d-flex justify-content-between">
                                                <a href="{{ route('campuses.show', $campus->id) }}" class="btn btn-primary-custom btn-sm">
                                                    <i class="fas fa-eye me-1"></i> Détails
                                                </a>
                                                <a href="{{ route('campuses.edit', $campus->id) }}" class="btn btn-secondary-custom btn-sm">
                                                    <i class="fas fa-edit me-1"></i> Modifier
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $campus->id }}">
                                                    <i class="fas fa-trash-alt me-1"></i> Supprimer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <i class="fas fa-school fa-3x text-muted mb-3"></i>
                                    <h5>Aucun campus trouvé</h5>
                                    <p class="text-muted">
                                        @if(request()->has('search') && !empty(request()->search))
                                            Aucun résultat pour la recherche "{{ request()->search }}"
                                            <br><a href="{{ route('campuses.index') }}" class="text-primary-custom">Voir tous les campus</a>
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
                <div class="card-footer bg-transparent py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 text-muted">Affichage de <span class="fw-medium">{{ $campuses->firstItem() ?? 0 }}</span> à <span class="fw-medium">{{ $campuses->lastItem() ?? 0 }}</span> sur <span class="fw-medium">{{ $campuses->total() }}</span> campus</p>
                        </div>
                        <div>
                            {{ $campuses->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Styles pour les différents modes d'affichage */
    .mode-list-content { display: block; }
    .mode-card-content { display: none; }
    
    /* Quand le mode est activé */
    .mode-list .mode-list-content { display: block; }
    .mode-list .mode-card-content { display: none; }
    
    .mode-card .mode-list-content { display: none; }
    .mode-card .mode-card-content { display: block; }
    
    /* Style pour les boutons de mode */
    .display-mode-container .btn {
        border-radius: 4px;
        padding: 0.5rem 1rem;
    }
    
    /* Style pour le bouton actif */
    .display-mode-container .btn-primary-custom {
        background-color: var(--primary-color);
        color: white;
    }
    
    .display-mode-container .btn-outline-primary-custom {
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
        background-color: transparent;
    }
    
    .display-mode-container .btn-outline-primary-custom:hover {
        background-color: var(--primary-color);
        color: white;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Récupérer les éléments
        const container = document.getElementById('campusesContainer');
        const modeButtons = document.querySelectorAll('.mode-button');
        
        // Récupérer le mode sauvegardé si disponible
        const savedMode = localStorage.getItem('display-mode-campuses');
        if (savedMode) {
            container.className = 'mode-' + savedMode;
            
            // Mettre à jour les boutons
            modeButtons.forEach(btn => {
                if (btn.getAttribute('data-mode') === savedMode) {
                    btn.classList.remove('btn-outline-primary-custom');
                    btn.classList.add('btn-primary-custom');
                } else {
                    btn.classList.remove('btn-primary-custom');
                    btn.classList.add('btn-outline-primary-custom');
                }
            });
        }
        
        // Ajouter les écouteurs d'événements aux boutons
        modeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const mode = this.getAttribute('data-mode');
                
                // Mettre à jour la classe du conteneur
                container.className = 'mode-' + mode;
                
                // Mettre à jour les styles des boutons
                modeButtons.forEach(b => {
                    if (b === this) {
                        b.classList.remove('btn-outline-primary-custom');
                        b.classList.add('btn-primary-custom');
                    } else {
                        b.classList.remove('btn-primary-custom');
                        b.classList.add('btn-outline-primary-custom');
                    }
                });
                
                // Sauvegarder la préférence
                localStorage.setItem('display-mode-campuses', mode);
            });
        });
    });
</script>
@endpush
@endsection
