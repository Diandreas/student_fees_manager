@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-primary-custom">
                        <i class="fas fa-home me-2"></i>Bienvenue, {{ Auth::user()->name }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('current_school'))
                        <div class="welcome-message mb-4">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center mb-3 mb-md-0">
                                    <img src="{{ session('current_school')->logo_url }}" alt="{{ session('current_school')->name }}" class="img-fluid mb-3" style="max-height: 120px;">
                                </div>
                                <div class="col-md-9">
                                    <h4 class="text-primary-custom">{{ session('current_school')->name }}</h4>
                                    <p class="text-muted">{{ session('current_school')->description }}</p>
                                    <div class="d-flex flex-wrap">
                                        <a href="{{ route('dashboard') }}" class="btn btn-primary-custom me-2 mb-2">
                                            <i class="fas fa-tachometer-alt me-1"></i>{{ $term('dashboard') }}
                                        </a>
                                        <a href="{{ route('students.index') }}" class="btn btn-outline-primary-custom me-2 mb-2">
                                            <i class="fas fa-user-graduate me-1"></i>{{ $term('students') }}
                                        </a>
                                        <a href="{{ route('payments.index') }}" class="btn btn-outline-primary-custom me-2 mb-2">
                                            <i class="fas fa-money-bill-wave me-1"></i>{{ $term('payments') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="info-card p-3 bg-light rounded">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-box bg-primary-custom bg-opacity-10 p-3 rounded-circle me-3">
                                            <i class="fas fa-user-graduate text-primary-custom fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $term('students') }}</h6>
                                            <h5 class="fw-bold mb-0 text-primary-custom">{{ session('current_school')->students()->count() }}</h5>
                                        </div>
                                    </div>
                                    <a href="{{ route('students.index') }}" class="text-decoration-none small text-primary-custom">
                                        <i class="fas fa-arrow-right me-1"></i>Voir tous les {{ strtolower($term('students')) }}
                                    </a>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="info-card p-3 bg-light rounded">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-box bg-primary-custom bg-opacity-10 p-3 rounded-circle me-3">
                                            <i class="fas fa-school text-primary-custom fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $term('campuses') }}</h6>
                                            <h5 class="fw-bold mb-0 text-primary-custom">{{ session('current_school')->campuses()->count() }}</h5>
                                        </div>
                                    </div>
                                    <a href="{{ route('campuses.index') }}" class="text-decoration-none small text-primary-custom">
                                        <i class="fas fa-arrow-right me-1"></i>Voir tous les {{ strtolower($term('campuses')) }}
                                    </a>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="info-card p-3 bg-light rounded">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-box bg-primary-custom bg-opacity-10 p-3 rounded-circle me-3">
                                            <i class="fas fa-graduation-cap text-primary-custom fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $term('fields') }}</h6>
                                            <h5 class="fw-bold mb-0 text-primary-custom">{{ session('current_school')->fields()->count() }}</h5>
                                        </div>
                                    </div>
                                    <a href="{{ route('fields.index') }}" class="text-decoration-none small text-primary-custom">
                                        <i class="fas fa-arrow-right me-1"></i>Voir toutes les {{ strtolower($term('fields')) }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Veuillez sélectionner une école pour commencer à travailler.
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route('schools.index') }}" class="btn btn-primary">
                                <i class="fas fa-building me-2"></i>Sélectionner une école
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            @if (session('current_school'))
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold text-primary-custom">
                                    <i class="fas fa-sync-alt me-2"></i>Activités récentes
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="activity-icon bg-light rounded-circle p-2 me-3">
                                                <i class="fas fa-money-bill-wave text-success"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 small">Paiement reçu pour l'étudiant <strong>Marc Dupont</strong></p>
                                                <span class="text-muted smaller">Aujourd'hui à 15:30</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="activity-icon bg-light rounded-circle p-2 me-3">
                                                <i class="fas fa-user-plus text-primary"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 small">Nouvel étudiant <strong>Sophie Martin</strong> inscrit</p>
                                                <span class="text-muted smaller">Hier à 10:15</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="activity-icon bg-light rounded-circle p-2 me-3">
                                                <i class="fas fa-file-alt text-warning"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 small">Rapport de paiement mensuel généré</p>
                                                <span class="text-muted smaller">Il y a 2 jours</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold text-primary-custom">
                                    <i class="fas fa-info-circle me-2"></i>Liens rapides
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <a href="{{ route('students.create') }}" class="quick-link d-flex align-items-center p-3 rounded bg-light text-decoration-none">
                                            <div class="icon-box bg-primary-custom bg-opacity-10 p-2 rounded-circle me-3">
                                                <i class="fas fa-user-plus text-primary-custom"></i>
                                            </div>
                                            <span>Ajouter un {{ strtolower($term('student')) }}</span>
                                        </a>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <a href="{{ route('payments.create') }}" class="quick-link d-flex align-items-center p-3 rounded bg-light text-decoration-none">
                                            <div class="icon-box bg-primary-custom bg-opacity-10 p-2 rounded-circle me-3">
                                                <i class="fas fa-credit-card text-primary-custom"></i>
                                            </div>
                                            <span>Nouveau {{ strtolower($term('payment')) }}</span>
                                        </a>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <a href="{{ route('reports.student') }}" class="quick-link d-flex align-items-center p-3 rounded bg-light text-decoration-none">
                                            <div class="icon-box bg-primary-custom bg-opacity-10 p-2 rounded-circle me-3">
                                                <i class="fas fa-file-alt text-primary-custom"></i>
                                            </div>
                                            <span>Rapport</span>
                                        </a>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <a href="{{ route('school.settings.appearance') }}" class="quick-link d-flex align-items-center p-3 rounded bg-light text-decoration-none">
                                            <div class="icon-box bg-primary-custom bg-opacity-10 p-2 rounded-circle me-3">
                                                <i class="fas fa-palette text-primary-custom"></i>
                                            </div>
                                            <span>Personnaliser</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .icon-box {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .activity-icon {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .smaller {
        font-size: 0.75rem;
    }
    
    .quick-link {
        transition: all 0.2s;
        color: var(--text-color);
    }
    
    .quick-link:hover {
        transform: translateY(-3px);
        background-color: var(--primary-color-light) !important;
        color: var(--primary-color);
    }
</style>
@endsection
