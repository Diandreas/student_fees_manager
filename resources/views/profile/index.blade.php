@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Mon profil</h2>
            </div>
            <p class="text-muted">Gérez vos informations personnelles et vos paramètres de connexion</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="list-group sticky-top" style="top: 80px;">
                <a href="#personal" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                    <i class="fas fa-user me-2"></i>Informations personnelles
                </a>
                <a href="#security" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-lock me-2"></i>Sécurité
                </a>
                <a href="#preferences" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-sliders-h me-2"></i>Préférences
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content">
                <!-- Informations personnelles -->
                <div class="tab-pane fade show active" id="personal">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Informations personnelles</h5>
                        </div>
                        <div class="card-body">
                            @if(session('profile_success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('profile_success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-4 text-center">
                                    <div class="position-relative d-inline-block">
                                        <img src="{{ auth()->user()->avatar ? asset('storage/avatars/' . auth()->user()->avatar) : asset('images/default-avatar.png') }}" alt="Avatar" class="rounded-circle img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                                        <label for="avatar" class="position-absolute bottom-0 end-0 bg-primary-custom text-white rounded-circle p-2" style="cursor: pointer;">
                                            <i class="fas fa-camera"></i>
                                        </label>
                                        <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*">
                                    </div>
                                    <div class="form-text mt-2">Cliquez sur l'icône pour changer votre photo de profil</div>
                                    @error('avatar')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Nom complet</label>
                                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ auth()->user()->name }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Adresse e-mail</label>
                                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ auth()->user()->email }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Téléphone</label>
                                        <input type="tel" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ auth()->user()->phone ?? '' }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="job_title" class="form-label">Fonction</label>
                                        <input type="text" id="job_title" name="job_title" class="form-control @error('job_title') is-invalid @enderror" value="{{ auth()->user()->job_title ?? '' }}">
                                        @error('job_title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="address" class="form-label">Adresse</label>
                                    <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ auth()->user()->address ?? '' }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="bio" class="form-label">Biographie</label>
                                    <textarea id="bio" name="bio" class="form-control @error('bio') is-invalid @enderror" rows="3">{{ auth()->user()->bio ?? '' }}</textarea>
                                    <div class="form-text">Une brève description de vous-même</div>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Sécurité -->
                <div class="tab-pane fade" id="security">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Sécurité</h5>
                        </div>
                        <div class="card-body">
                            @if(session('password_success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('password_success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('profile.password') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Mot de passe actuel</label>
                                    <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">Nouveau mot de passe</label>
                                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                    <div class="form-text">Minimum 8 caractères, incluant des lettres, des chiffres et des caractères spéciaux</div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="fas fa-lock me-2"></i>Changer le mot de passe
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Préférences -->
                <div class="tab-pane fade" id="preferences">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Préférences</h5>
                        </div>
                        <div class="card-body">
                            @if(session('preferences_success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('preferences_success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('profile.preferences') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="language" class="form-label">Langue de l'interface</label>
                                    <select id="language" name="language" class="form-select @error('language') is-invalid @enderror">
                                        <option value="fr" {{ auth()->user()->language == 'fr' ? 'selected' : '' }}>Français</option>
                                        <option value="en" {{ auth()->user()->language == 'en' ? 'selected' : '' }}>English</option>
                                    </select>
                                    @error('language')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="theme" class="form-label">Thème</label>
                                    <select id="theme" name="theme" class="form-select @error('theme') is-invalid @enderror">
                                        <option value="light" {{ auth()->user()->theme == 'light' ? 'selected' : '' }}>Clair</option>
                                        <option value="dark" {{ auth()->user()->theme == 'dark' ? 'selected' : '' }}>Sombre</option>
                                        <option value="auto" {{ auth()->user()->theme == 'auto' ? 'selected' : '' }}>Automatique (selon système)</option>
                                    </select>
                                    @error('theme')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Notifications</label>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" value="1" {{ auth()->user()->email_notifications ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email_notifications">Recevoir des notifications par e-mail</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="browser_notifications" name="browser_notifications" value="1" {{ auth()->user()->browser_notifications ? 'checked' : '' }}>
                                        <label class="form-check-label" for="browser_notifications">Activer les notifications du navigateur</label>
                                    </div>
                                </div>
                                
                                <button type="button" class="btn btn-outline-primary mb-4" data-bs-toggle="modal" data-bs-target="#theme-modal">
                                    <i class="fas fa-palette me-2"></i>Personnaliser les couleurs du thème
                                </button>
                                
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="fas fa-save me-2"></i>Enregistrer les préférences
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/theme-changer.js') }}"></script>
@endpush 