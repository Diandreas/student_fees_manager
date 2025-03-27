@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <h1 class="h3 fw-bold text-primary-custom mb-0">
                        <i class="fas fa-user-graduate me-2"></i>{{ session('current_school') ? session('current_school')->term('students') : 'Gestion des étudiants' }}
                    </h1>
                    <a href="{{ route('students.create') }}" class="btn btn-primary-custom">
                        <i class="fas fa-plus me-2"></i>Ajouter un {{ session('current_school') ? strtolower(session('current_school')->term('student')) : 'étudiant' }}
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
                                Liste des {{ session('current_school') ? strtolower(session('current_school')->term('students')) : 'étudiants' }}
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <form class="d-flex" action="{{ route('students.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" value="{{ request()->search }}" placeholder="Rechercher par nom, email, téléphone ou filière..." aria-label="Rechercher">
                                    <button class="btn btn-primary-custom" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request()->has('search') && !empty(request()->search))
                                    <a href="{{ route('students.index') }}" class="btn btn-secondary-custom">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div id="studentsContainer">
                    <!-- Mode Liste uniquement -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 table-custom">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col" class="ps-4">#ID</th>
                                    <th scope="col">Nom complet</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Téléphone</th>
                                    <th scope="col">Parent</th>
                                    <th scope="col">{{ session('current_school') ? session('current_school')->term('field') : 'Filière' }}</th>
                                    <th scope="col">{{ session('current_school') ? session('current_school')->term('campus') : 'Campus' }}</th>
                                    <th scope="col" class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                <tr>
                                    <th scope="row" class="ps-4">{{ $student->id }}</th>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary-custom bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; overflow: hidden;">
                                                @if($student->photo)
                                                    <img src="{{ Storage::url('students/' . $student->photo) }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $student->fullName }}">
                                                @else
                                                    <span class="fw-bold text-primary-custom">{{ substr($student->fullName, 0, 1) }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="fw-bold mb-0">{{ $student->fullName }}</p>
                                                <p class="text-muted mb-0 small">inscrit le {{ $student->created_at->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->phone ?? 'Non spécifié' }}</td>
                                    <td>
                                        @if($student->parent_name || $student->parent_tel)
                                            <span class="d-block fw-medium">{{ $student->parent_name ?? 'Parent' }}</span>
                                            <span class="small text-muted">{{ $student->parent_tel ?? 'Aucun numéro' }}</span>
                                        @else
                                            <span class="text-muted">Non spécifié</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-custom bg-opacity-10 text-primary-custom fw-normal py-2 px-3 rounded-pill">
                                            {{ $student->field->name ?? 'Non assigné' }}
                                        </span>
                                    </td>
                                    <td>{{ $student->field->campus->name ?? 'Non assigné' }}</td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $student->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Modal de suppression -->
                                        <div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        Êtes-vous sûr de vouloir supprimer l'{{ session('current_school') ? strtolower(session('current_school')->term('student')) : 'étudiant' }} <strong>{{ $student->fullName }}</strong> ?
                                                        <p class="text-danger mb-0 mt-2"><small>Cette action est irréversible.</small></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline">
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
                                    <td colspan="8" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                            <h5>Aucun {{ session('current_school') ? strtolower(session('current_school')->term('student')) : 'étudiant' }} trouvé</h5>
                                            <p class="text-muted">
                                                @if(request()->has('search') && !empty(request()->search))
                                                    Aucun résultat pour la recherche "{{ request()->search }}"
                                                    <br><a href="{{ route('students.index') }}" class="text-primary-custom">Voir tous les {{ session('current_school') ? strtolower(session('current_school')->term('students')) : 'étudiants' }}</a>
                                                @else
                                                    Commencez par ajouter un {{ session('current_school') ? strtolower(session('current_school')->term('student')) : 'étudiant' }}
                                                @endif
                                            </p>
                                            @if(!request()->has('search'))
                                            <a href="{{ route('students.create') }}" class="btn btn-primary-custom">
                                                <i class="fas fa-plus me-2"></i>Ajouter un {{ session('current_school') ? strtolower(session('current_school')->term('student')) : 'étudiant' }}
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
                
                @if($students->count() > 0)
                <div class="card-footer bg-transparent py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 text-muted">Affichage de <span class="fw-medium">{{ $students->firstItem() ?? 0 }}</span> à <span class="fw-medium">{{ $students->lastItem() ?? 0 }}</span> sur <span class="fw-medium">{{ $students->total() }}</span> {{ session('current_school') ? strtolower(session('current_school')->term('students')) : 'étudiants' }}</p>
                        </div>
                        <div>
                            {{ $students->links('pagination::bootstrap-5') }}
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
    /* Style pour les couleurs principales */
    .text-primary-custom {
        color: var(--primary-color);
    }
    
    .btn-primary-custom {
        background-color: var(--primary-color);
        color: white;
    }
    
    .btn-secondary-custom {
        background-color: var(--secondary-color);
        color: white;
    }
</style>
@endpush
@endsection
