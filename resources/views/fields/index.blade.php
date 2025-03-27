@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <h1 class="h3 fw-bold text-primary-custom mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>Gestion des filières
                    </h1>
                    <a href="{{ route('fields.create') }}" class="btn btn-primary-custom">
                        <i class="fas fa-plus me-2"></i>Ajouter une filière
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
                                Liste des filières
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <form class="d-flex" action="{{ route('fields.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" value="{{ request()->search }}" placeholder="Rechercher une filière..." aria-label="Rechercher">
                                    <button class="btn btn-primary-custom" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request()->has('search') && !empty(request()->search))
                                    <a href="{{ route('fields.index') }}" class="btn btn-secondary-custom">
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
                
                <div class="card-body">
                    @if($fields->isEmpty())
                        <div class="text-center py-5">
                            <img src="{{ asset('images/empty-state.svg') }}" alt="Aucune filière" class="img-fluid mb-3" style="max-width: 200px;">
                            <h5>Aucune filière trouvée</h5>
                            <p class="text-muted">Il n'y a aucune filière à afficher pour le moment.</p>
                            <a href="{{ route('fields.create') }}" class="btn btn-primary-custom">
                                <i class="fas fa-plus me-2"></i>Ajouter une filière
                            </a>
                        </div>
                    @else
                        <div id="fieldsContainer" class="mode-list">
                            <!-- Mode Liste -->
                            <div class="mode-list-content">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0 table-custom">
                                        <thead class="bg-light">
                                            <tr>
                                                <th scope="col" class="ps-4">Nom</th>
                                                <th scope="col">Campus</th>
                                                <th scope="col">Frais de scolarité</th>
                                                <th scope="col" class="text-end pe-4">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($fields as $field)
                                            <tr>
                                                <td class="ps-4 fw-medium">{{ $field->name }}</td>
                                                <td>
                                                    <span class="badge bg-primary-custom bg-opacity-10 text-primary-custom py-2 px-3">
                                                        {{ $field->campus->name }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold">{{ number_format($field->fees, 0, ',', ' ') }} FCFA</span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="btn-group">
                                                        <a href="{{ route('fields.show', $field->id) }}" class="btn btn-sm btn-outline-primary" title="Voir les détails">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('fields.edit', $field->id) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $field->id }}" title="Supprimer">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                    
                                                    <!-- Modal de suppression -->
                                                    <div class="modal fade" id="deleteModal{{ $field->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-start">
                                                                    Êtes-vous sûr de vouloir supprimer la filière <strong>{{ $field->name }}</strong> ?
                                                                    <p class="text-danger mb-0 mt-2"><small>Cette action est irréversible.</small></p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                    <form action="{{ route('fields.destroy', $field->id) }}" method="POST" class="d-inline">
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
                                                        <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                                        <h5>Aucune filière trouvée</h5>
                                                        <p class="text-muted">
                                                            @if(request()->has('search') && !empty(request()->search))
                                                                Aucun résultat pour la recherche "{{ request()->search }}"
                                                                <br><a href="{{ route('fields.index') }}" class="text-primary-custom">Voir toutes les filières</a>
                                                            @else
                                                                Commencez par ajouter une filière
                                                            @endif
                                                        </p>
                                                        @if(!request()->has('search'))
                                                        <a href="{{ route('fields.create') }}" class="btn btn-primary-custom">
                                                            <i class="fas fa-plus me-2"></i>Ajouter une filière
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
                                    @foreach($fields as $field)
                                    <div class="col-md-6 col-lg-4 mb-4 list-item">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h5 class="card-title mb-0 text-primary-custom">{{ $field->name }}</h5>
                                                    <span class="badge rounded-pill bg-primary-custom">{{ $field->educationLevel?->name ?? 'N/A' }}</span>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-8">
                                                        <p class="card-text mb-1"><i class="fas fa-school text-primary-custom me-2"></i>{{ $field->campus?->name ?? 'N/A' }}</p>
                                                        <p class="card-text mb-1"><i class="fas fa-money-bill-wave text-primary-custom me-2"></i>Frais annuels: {{ number_format($field->annual_fees, 0, ',', ' ') }} FCFA</p>
                                                        <p class="card-text mb-1"><i class="fas fa-calendar text-primary-custom me-2"></i>Durée: {{ $field->duration }} an(s)</p>
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <div class="bg-primary-custom bg-opacity-10 rounded-circle p-2 mx-auto" style="width: 60px; height: 60px;">
                                                            <div class="fw-bold text-primary-custom">{{ $field->students_count }}</div>
                                                            <small class="text-primary-custom">Étudiants</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="{{ route('fields.show', $field) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye me-1"></i>Voir
                                                    </a>
                                                    <a href="{{ route('fields.edit', $field) }}" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-edit me-1"></i>Modifier
                                                    </a>
                                                    <a href="{{ route('fields.report', $field) }}" class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-chart-bar me-1"></i>Rapport
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="document.getElementById('delete-field-{{ $field->id }}-card').submit()">
                                                        <i class="fas fa-trash me-1"></i>Supprimer
                                                    </button>
                                                    <form id="delete-field-{{ $field->id }}-card" method="POST" action="{{ route('fields.destroy', $field) }}" class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
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
                <div class="card-footer bg-transparent py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 text-muted">Affichage de <span class="fw-medium">{{ $fields->firstItem() ?? 0 }}</span> à <span class="fw-medium">{{ $fields->lastItem() ?? 0 }}</span> sur <span class="fw-medium">{{ $fields->total() }}</span> filières</p>
                        </div>
                        <div>
                            {{ $fields->links('pagination::bootstrap-5') }}
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
        const container = document.getElementById('fieldsContainer');
        const modeButtons = document.querySelectorAll('.mode-button');
        
        // Récupérer le mode sauvegardé si disponible
        const savedMode = localStorage.getItem('display-mode-fields');
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
                localStorage.setItem('display-mode-fields', mode);
            });
        });
    });
</script>
@endpush
@endsection
