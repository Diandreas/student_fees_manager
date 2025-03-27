@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar des paramètres -->
        @include('school-settings.partials.sidebar')
        
        <!-- Contenu principal -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 text-primary-custom">Modèles de documents</h1>
                <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                    <i class="fas fa-plus me-2"></i>Ajouter un modèle
                </button>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary-custom">
                        Modèles disponibles
                    </h5>
                    <p class="text-muted mb-0">
                        Gérez vos modèles de documents personnalisés
                    </p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Fichier</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($documents) && count($documents) > 0)
                                    @foreach($documents as $document)
                                        <tr>
                                            <td>{{ $document->name }}</td>
                                            <td>
                                                @switch($document->document_type)
                                                    @case('certificate')
                                                        <span class="badge bg-success">Certificat</span>
                                                        @break
                                                    @case('report')
                                                        <span class="badge bg-primary">Rapport</span>
                                                        @break
                                                    @case('receipt')
                                                        <span class="badge bg-info">Reçu</span>
                                                        @break
                                                    @case('letter')
                                                        <span class="badge bg-warning">Lettre</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">Autre</span>
                                                @endswitch
                                            </td>
                                            <td>{{ $document->description ?? 'Aucune description' }}</td>
                                            <td>
                                                <a href="{{ $document->file_url }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-file-alt me-1"></i>Voir
                                                </a>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editDocumentModal{{ $document->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteDocumentModal{{ $document->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <!-- Modal de suppression -->
                                        <div class="modal fade" id="deleteDocumentModal{{ $document->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirmer la suppression</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Êtes-vous sûr de vouloir supprimer le modèle <strong>{{ $document->name }}</strong> ?</p>
                                                        <p class="text-danger">Cette action est irréversible.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <form action="{{ route('school.settings.documents.destroy', ['school' => $school->id, 'document' => $document->id]) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-muted mb-0">Aucun modèle de document n'a encore été ajouté</p>
                                            <button type="button" class="btn btn-outline-primary mt-3" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                                                <i class="fas fa-plus me-2"></i>Ajouter votre premier modèle
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary-custom">
                        Documents système par défaut
                    </h5>
                    <p class="text-muted mb-0">
                        Ces modèles sont utilisés lorsqu'aucun modèle personnalisé n'est disponible
                    </p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Aperçu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-success">Certificat</span></td>
                                    <td>Modèle de certificat par défaut du système</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-eye me-1"></i>Aperçu
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">Rapport</span></td>
                                    <td>Modèle de rapport par défaut du système</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-eye me-1"></i>Aperçu
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-info">Reçu</span></td>
                                    <td>Modèle de reçu par défaut du système</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-eye me-1"></i>Aperçu
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'ajout de document -->
<div class="modal fade" id="addDocumentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un modèle de document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('school.settings.documents.store', $school) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom du modèle</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="document_type" class="form-label">Type de document</label>
                        <select class="form-select" id="document_type" name="document_type" required>
                            <option value="certificate">Certificat</option>
                            <option value="report">Rapport</option>
                            <option value="receipt">Reçu</option>
                            <option value="letter">Lettre</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="template_file" class="form-label">Fichier modèle</label>
                        <input type="file" class="form-control" id="template_file" name="template_file" required>
                        <div class="form-text">Formats acceptés: PDF, DOCX. Taille max: 5 Mo</div>
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
@endsection 