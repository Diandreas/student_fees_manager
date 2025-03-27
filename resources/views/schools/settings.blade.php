@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Paramètres de l'école</h2>
                <a href="{{ route('schools.show', $school) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Retour
                </a>
            </div>
            <p class="text-muted">Personnalisez votre établissement selon vos besoins</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="list-group sticky-top" style="top: 80px;">
                <a href="#general" class="list-group-item list-group-item-action active" data-bs-toggle="list">Paramètres généraux</a>
                <a href="#terminology" class="list-group-item list-group-item-action" data-bs-toggle="list">Terminologie</a>
                <a href="#education-levels" class="list-group-item list-group-item-action" data-bs-toggle="list">Niveaux d'éducation</a>
                <a href="#features" class="list-group-item list-group-item-action" data-bs-toggle="list">Fonctionnalités</a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content">
                <!-- Paramètres généraux -->
                <div class="tab-pane fade show active" id="general">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Paramètres généraux</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('school.settings.general', $school) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-4">
                                    <label class="form-label">Logo de l'établissement</label>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <img src="{{ $school->getLogoUrlAttribute() }}" alt="{{ $school->name }}" 
                                                 class="img-thumbnail" style="width: 100px; height: 100px; object-fit: contain;">
                                        </div>
                                        <div class="flex-grow-1">
                                            <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                                            <div class="form-text">Format recommandé: PNG ou JPG, dimensions minimales 200x200px</div>
                                            @error('logo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            
                                            @if($school->logo)
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" name="remove_logo" id="remove_logo">
                                                    <label class="form-check-label" for="remove_logo">
                                                        Supprimer le logo actuel
                                                    </label>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="school_type" class="form-label">Type d'établissement</label>
                                    <select id="school_type" name="school_type" class="form-select @error('school_type') is-invalid @enderror">
                                        @foreach($schoolTypes as $value => $label)
                                            <option value="{{ $value }}" {{ $school->school_type == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('school_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="theme_color" class="form-label">Couleur principale</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <input type="color" id="theme_color_picker" value="{{ $school->theme_color }}" 
                                                    onchange="document.getElementById('theme_color').value = this.value">
                                            </span>
                                            <input type="text" id="theme_color" name="theme_color" class="form-control @error('theme_color') is-invalid @enderror" 
                                                value="{{ $school->theme_color }}">
                                        </div>
                                        @error('theme_color')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="header_color" class="form-label">Couleur de l'en-tête</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <input type="color" id="header_color_picker" value="{{ $school->header_color }}" 
                                                    onchange="document.getElementById('header_color').value = this.value">
                                            </span>
                                            <input type="text" id="header_color" name="header_color" class="form-control @error('header_color') is-invalid @enderror" 
                                                value="{{ $school->header_color }}">
                                        </div>
                                        @error('header_color')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="sidebar_color" class="form-label">Couleur de la barre latérale</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <input type="color" id="sidebar_color_picker" value="{{ $school->sidebar_color }}" 
                                                    onchange="document.getElementById('sidebar_color').value = this.value">
                                            </span>
                                            <input type="text" id="sidebar_color" name="sidebar_color" class="form-control @error('sidebar_color') is-invalid @enderror" 
                                                value="{{ $school->sidebar_color }}">
                                        </div>
                                        @error('sidebar_color')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="text_color" class="form-label">Couleur du texte</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <input type="color" id="text_color_picker" value="{{ $school->text_color }}" 
                                                    onchange="document.getElementById('text_color').value = this.value">
                                            </span>
                                            <input type="text" id="text_color" name="text_color" class="form-control @error('text_color') is-invalid @enderror" 
                                                value="{{ $school->text_color }}">
                                        </div>
                                        @error('text_color')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Terminologie -->
                <div class="tab-pane fade" id="terminology">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Personnalisation de la terminologie</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">Personnalisez les termes utilisés dans votre établissement pour une meilleure adaptation à votre contexte.</p>
                            
                            <form action="{{ route('school.settings.terminology', $school) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="terminology[student]" class="form-label">Étudiant (singulier)</label>
                                        <input type="text" id="terminology[student]" name="terminology[student]" class="form-control"
                                            value="{{ $school->term('student') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="terminology[students]" class="form-label">Étudiants (pluriel)</label>
                                        <input type="text" id="terminology[students]" name="terminology[students]" class="form-control"
                                            value="{{ $school->term('students') }}">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="terminology[field]" class="form-label">Filière (singulier)</label>
                                        <input type="text" id="terminology[field]" name="terminology[field]" class="form-control"
                                            value="{{ $school->term('field') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="terminology[fields]" class="form-label">Filières (pluriel)</label>
                                        <input type="text" id="terminology[fields]" name="terminology[fields]" class="form-control"
                                            value="{{ $school->term('fields') }}">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="terminology[campus]" class="form-label">Campus (singulier)</label>
                                        <input type="text" id="terminology[campus]" name="terminology[campus]" class="form-control"
                                            value="{{ $school->term('campus') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="terminology[campuses]" class="form-label">Campus (pluriel)</label>
                                        <input type="text" id="terminology[campuses]" name="terminology[campuses]" class="form-control"
                                            value="{{ $school->term('campuses') }}">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="terminology[fee]" class="form-label">Frais (singulier)</label>
                                        <input type="text" id="terminology[fee]" name="terminology[fee]" class="form-control"
                                            value="{{ $school->term('fee') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="terminology[fees]" class="form-label">Frais (pluriel)</label>
                                        <input type="text" id="terminology[fees]" name="terminology[fees]" class="form-control"
                                            value="{{ $school->term('fees') }}">
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">Enregistrer la terminologie</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Niveaux d'éducation -->
                <div class="tab-pane fade" id="education-levels">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Niveaux d'éducation</h5>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addLevelModal">
                                <i class="fas fa-plus me-1"></i> Ajouter un niveau
                            </button>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">Définissez les niveaux d'éducation utilisés dans votre établissement.</p>
                            
                            @if($educationLevels->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Code</th>
                                                <th>Durée (années)</th>
                                                <th>Ordre</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($educationLevels as $level)
                                                <tr>
                                                    <td>{{ $level->name }}</td>
                                                    <td>{{ $level->code }}</td>
                                                    <td>{{ $level->duration_years }}</td>
                                                    <td>{{ $level->order }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $level->is_active ? 'success' : 'danger' }}">
                                                            {{ $level->is_active ? 'Actif' : 'Inactif' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <button type="button" class="btn btn-outline-primary" 
                                                                data-bs-toggle="modal" data-bs-target="#editLevelModal{{ $level->id }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-danger" 
                                                                data-bs-toggle="modal" data-bs-target="#deleteLevelModal{{ $level->id }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    Aucun niveau d'éducation n'a été défini. Commencez par ajouter des niveaux pour votre établissement.
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Modaux pour les niveaux d'éducation -->
                    <div class="modal fade" id="addLevelModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('school.education-levels.store', $school) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ajouter un niveau d'éducation</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nom</label>
                                            <input type="text" id="name" name="name" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="code" class="form-label">Code</label>
                                            <input type="text" id="code" name="code" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="duration_years" class="form-label">Durée (années)</label>
                                                <input type="number" id="duration_years" name="duration_years" class="form-control" min="1" value="1" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="order" class="form-label">Ordre</label>
                                                <input type="number" id="order" name="order" class="form-control" min="0" value="0" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary">Ajouter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    @foreach($educationLevels as $level)
                        <!-- Modal d'édition -->
                        <div class="modal fade" id="editLevelModal{{ $level->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('school.education-levels.update', ['school' => $school, 'level' => $level]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modifier le niveau d'éducation</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="name{{ $level->id }}" class="form-label">Nom</label>
                                                <input type="text" id="name{{ $level->id }}" name="name" class="form-control" value="{{ $level->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="code{{ $level->id }}" class="form-label">Code</label>
                                                <input type="text" id="code{{ $level->id }}" name="code" class="form-control" value="{{ $level->code }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="description{{ $level->id }}" class="form-label">Description</label>
                                                <textarea id="description{{ $level->id }}" name="description" class="form-control" rows="3">{{ $level->description }}</textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="duration_years{{ $level->id }}" class="form-label">Durée (années)</label>
                                                    <input type="number" id="duration_years{{ $level->id }}" name="duration_years" class="form-control" min="1" value="{{ $level->duration_years }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="order{{ $level->id }}" class="form-label">Ordre</label>
                                                    <input type="number" id="order{{ $level->id }}" name="order" class="form-control" min="0" value="{{ $level->order }}" required>
                                                </div>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_active{{ $level->id }}" name="is_active" value="1" {{ $level->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active{{ $level->id }}">Actif</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Modal de suppression -->
                        <div class="modal fade" id="deleteLevelModal{{ $level->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('school.education-levels.destroy', ['school' => $school, 'level' => $level]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Supprimer le niveau d'éducation</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Êtes-vous sûr de vouloir supprimer le niveau "{{ $level->name }}" ?</p>
                                            <p class="text-danger">Cette action est irréversible.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Fonctionnalités -->
                <div class="tab-pane fade" id="features">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Fonctionnalités</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">Activez ou désactivez les fonctionnalités selon vos besoins.</p>
                            
                            <div class="list-group mb-4">
                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Paiements en ligne</h6>
                                        <p class="mb-0 text-muted small">Permettre aux parents/étudiants de payer les frais en ligne</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="online_payments" 
                                            {{ $school->has_online_payments ? 'checked' : '' }}
                                            {{ $school->subscription_plan === 'basic' ? 'disabled' : '' }}>
                                    </div>
                                </div>
                                
                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Notifications SMS</h6>
                                        <p class="mb-0 text-muted small">Envoyer des notifications par SMS aux parents/étudiants</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="sms_notifications" 
                                            {{ $school->has_sms_notifications ? 'checked' : '' }}
                                            {{ $school->subscription_plan === 'basic' ? 'disabled' : '' }}>
                                    </div>
                                </div>
                                
                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Portail parents</h6>
                                        <p class="mb-0 text-muted small">Donner accès aux parents pour suivre les paiements et informations</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="parent_portal" 
                                            {{ $school->has_parent_portal ? 'checked' : '' }}
                                            {{ $school->subscription_plan === 'basic' ? 'disabled' : '' }}>
                                    </div>
                                </div>
                            </div>
                            
                            @if($school->subscription_plan === 'basic')
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Ces fonctionnalités avancées sont disponibles uniquement avec un abonnement Premium ou Entreprise.
                                    <a href="#" class="alert-link">Mettez à niveau votre abonnement</a> pour y accéder.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 