@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold text-primary-custom">
                <i class="fas fa-school me-2"></i>Écoles
            </h5>
            @if(auth()->user()->is_superadmin)
            <a href="{{ route('schools.create') }}" class="btn btn-primary-custom">
                <i class="fas fa-plus-circle me-2"></i>Ajouter une école
            </a>
            @endif
        </div>
        
        <div class="card-body p-4">
            <div class="row g-4">
                @forelse($schools as $school)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm hover-card">
                            <div class="card-img-top d-flex align-items-center justify-content-center p-4" style="height: 120px; background-color: {{ $school->primary_color }}">
                                @if($school->logo)
                                    <img src="{{ $school->logo_url }}" alt="{{ $school->name }}" class="img-fluid" style="max-height: 80px;">
                                @else
                                    <span class="display-4 text-white fw-bold">{{ strtoupper(substr($school->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-1">{{ $school->name }}</h5>
                                <p class="text-muted small mb-3">{{ $school->contact_email }}</p>
                                
                                <div class="d-flex flex-wrap gap-2 mb-4">
                                    <span class="badge bg-primary-custom bg-opacity-10 text-primary-custom">
                                        {{ $school->campuses->count() }} campus
                                    </span>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        {{ $school->admins->count() }} administrateurs
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                                <a href="{{ route('schools.show', $school) }}" class="text-decoration-none text-primary-custom">
                                    <i class="fas fa-eye me-1"></i> Détails
                                </a>
                                
                                <form action="{{ route('schools.switch', $school) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary-custom">
                                        <i class="fas fa-sign-in-alt me-1"></i> Connecter
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center p-5 bg-light rounded">
                            <i class="fas fa-school fa-3x text-muted mb-3"></i>
                            <h4 class="fw-bold mb-2">Aucune école trouvée</h4>
                            <p class="text-muted mb-4">Vous n'avez pas encore d'école associée à votre compte.</p>
                            
                            @if(auth()->user()->is_superadmin)
                                <a href="{{ route('schools.create') }}" class="btn btn-primary-custom">
                                    <i class="fas fa-plus-circle me-2"></i>Créer votre première école
                                </a>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .hover-card {
        transition: all 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection 