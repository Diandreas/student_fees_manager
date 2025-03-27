@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Paramètres de l'application</h2>
            </div>
            <p class="text-muted">Configurez les paramètres généraux de l'application</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="list-group sticky-top" style="top: 80px;">
                <a href="#appearance" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                    <i class="fas fa-palette me-2"></i>Apparence
                </a>
                <a href="#notifications" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-bell me-2"></i>Notifications
                </a>
                <a href="#language" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-language me-2"></i>Langues
                </a>
                <a href="#export" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-file-export me-2"></i>Export & Impression
                </a>
                <a href="#advanced" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-cogs me-2"></i>Paramètres avancés
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content">
                <!-- Apparence -->
                <div class="tab-pane fade show active" id="appearance">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Apparence</h5>
                        </div>
                        <div class="card-body">
                            @if(session('appearance_success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('appearance_success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('settings.appearance') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-4">
                                    <label class="form-label">Logo de l'application</label>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: contain;">
                                        </div>
                                        <div class="flex-grow-1">
                                            <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                                            <div class="form-text">Format recommandé: PNG avec fond transparent. Dimensions: 200x200px</div>
                                            @error('logo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label">Favicon</label>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <img src="{{ asset('favicon.ico') }}" alt="Favicon" class="img-thumbnail" style="width: 48px; height: 48px; object-fit: contain;">
                                        </div>
                                        <div class="flex-grow-1">
                                            <input type="file" name="favicon" id="favicon" class="form-control @error('favicon') is-invalid @enderror" accept="image/x-icon,image/png">
                                            <div class="form-text">Format recommandé: ICO ou PNG. Dimensions: 32x32px ou 16x16px</div>
                                            @error('favicon')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="app_name" class="form-label">Nom de l'application</label>
                                        <input type="text" id="app_name" name="app_name" class="form-control @error('app_name') is-invalid @enderror" value="{{ config('app.name') }}">
                                        @error('app_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="default_theme" class="form-label">Thème par défaut</label>
                                        <select id="default_theme" name="default_theme" class="form-select @error('default_theme') is-invalid @enderror">
                                            <option value="light">Clair</option>
                                            <option value="dark">Sombre</option>
                                            <option value="auto">Automatique (selon système)</option>
                                        </select>
                                        @error('default_theme')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <button type="button" class="btn btn-outline-primary mb-4" data-bs-toggle="modal" data-bs-target="#theme-modal">
                                    <i class="fas fa-palette me-2"></i>Personnaliser les couleurs du thème
                                </button>
                                
                                <div class="mb-3">
                                    <label class="form-label">Options d'affichage</label>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="show_footer" name="show_footer" value="1" checked>
                                        <label class="form-check-label" for="show_footer">Afficher le pied de page</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="show_breadcrumbs" name="show_breadcrumbs" value="1" checked>
                                        <label class="form-check-label" for="show_breadcrumbs">Afficher le fil d'Ariane (breadcrumbs)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="enable_animations" name="enable_animations" value="1" checked>
                                        <label class="form-check-label" for="enable_animations">Activer les animations</label>
                                    </div>
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
                
                <!-- Notifications -->
                <div class="tab-pane fade" id="notifications">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Notifications</h5>
                        </div>
                        <div class="card-body">
                            @if(session('notifications_success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('notifications_success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('settings.notifications') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Notifications par e-mail</label>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="email_new_student" name="email_notifications[]" value="new_student" checked>
                                        <label class="form-check-label" for="email_new_student">Nouvel étudiant inscrit</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="email_new_payment" name="email_notifications[]" value="new_payment" checked>
                                        <label class="form-check-label" for="email_new_payment">Nouveau paiement enregistré</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="email_payment_due" name="email_notifications[]" value="payment_due" checked>
                                        <label class="form-check-label" for="email_payment_due">Échéance de paiement</label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="email_reports" name="email_notifications[]" value="reports">
                                        <label class="form-check-label" for="email_reports">Rapports périodiques</label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email_from" class="form-label">Adresse d'expédition</label>
                                    <input type="email" id="email_from" name="email_from" class="form-control @error('email_from') is-invalid @enderror" value="noreply@example.com">
                                    <div class="form-text">Adresse e-mail utilisée pour envoyer les notifications</div>
                                    @error('email_from')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email_name" class="form-label">Nom d'expédition</label>
                                    <input type="text" id="email_name" name="email_name" class="form-control @error('email_name') is-invalid @enderror" value="Student Fees Manager">
                                    @error('email_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="fas fa-save me-2"></i>Enregistrer les paramètres
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Langues -->
                <div class="tab-pane fade" id="language">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Langues</h5>
                        </div>
                        <div class="card-body">
                            @if(session('language_success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('language_success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('settings.language') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="default_language" class="form-label">Langue par défaut</label>
                                    <select id="default_language" name="default_language" class="form-select @error('default_language') is-invalid @enderror">
                                        <option value="fr" selected>Français</option>
                                        <option value="en">English</option>
                                    </select>
                                    @error('default_language')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Langues disponibles</label>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="lang_fr" name="available_languages[]" value="fr" checked disabled>
                                        <label class="form-check-label" for="lang_fr">Français</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="lang_en" name="available_languages[]" value="en" checked>
                                        <label class="form-check-label" for="lang_en">English</label>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="fas fa-save me-2"></i>Enregistrer les paramètres
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Export & Impression -->
                <div class="tab-pane fade" id="export">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Export & Impression</h5>
                        </div>
                        <div class="card-body">
                            @if(session('export_success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('export_success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('settings.export') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="paper_size" class="form-label">Format de papier par défaut</label>
                                    <select id="paper_size" name="paper_size" class="form-select @error('paper_size') is-invalid @enderror">
                                        <option value="a4" selected>A4</option>
                                        <option value="letter">Letter</option>
                                        <option value="legal">Legal</option>
                                    </select>
                                    @error('paper_size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="export_format" class="form-label">Format d'export par défaut</label>
                                    <select id="export_format" name="export_format" class="form-select @error('export_format') is-invalid @enderror">
                                        <option value="xlsx" selected>Excel (.xlsx)</option>
                                        <option value="csv">CSV (.csv)</option>
                                        <option value="pdf">PDF (.pdf)</option>
                                    </select>
                                    @error('export_format')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="receipt_footer" class="form-label">Pied de page des reçus</label>
                                    <textarea id="receipt_footer" name="receipt_footer" class="form-control @error('receipt_footer') is-invalid @enderror" rows="3">Merci pour votre paiement. Ce reçu est généré automatiquement et ne nécessite pas de signature.</textarea>
                                    @error('receipt_footer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="include_logo" name="include_logo" value="1" checked>
                                        <label class="form-check-label" for="include_logo">Inclure le logo sur les documents imprimés</label>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="fas fa-save me-2"></i>Enregistrer les paramètres
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Paramètres avancés -->
                <div class="tab-pane fade" id="advanced">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Paramètres avancés</h5>
                        </div>
                        <div class="card-body">
                            @if(session('advanced_success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('advanced_success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('settings.advanced') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="items_per_page" class="form-label">Éléments par page</label>
                                    <select id="items_per_page" name="items_per_page" class="form-select @error('items_per_page') is-invalid @enderror">
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    @error('items_per_page')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="date_format" class="form-label">Format de date</label>
                                    <select id="date_format" name="date_format" class="form-select @error('date_format') is-invalid @enderror">
                                        <option value="d/m/Y" selected>DD/MM/YYYY (31/12/2023)</option>
                                        <option value="Y-m-d">YYYY-MM-DD (2023-12-31)</option>
                                        <option value="m/d/Y">MM/DD/YYYY (12/31/2023)</option>
                                    </select>
                                    @error('date_format')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="currency" class="form-label">Devise</label>
                                    <select id="currency" name="currency" class="form-select @error('currency') is-invalid @enderror">
                                        <option value="XOF" selected>Franc CFA (FCFA)</option>
                                        <option value="EUR">Euro (€)</option>
                                        <option value="USD">Dollar US ($)</option>
                                    </select>
                                    @error('currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Cache</label>
                                    <button type="button" class="btn btn-outline-secondary d-block mb-2">
                                        <i class="fas fa-broom me-2"></i>Vider le cache
                                    </button>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Mode maintenance</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1">
                                        <label class="form-check-label" for="maintenance_mode">Activer le mode maintenance</label>
                                    </div>
                                    <div class="form-text">Lorsqu'il est activé, seuls les administrateurs peuvent accéder à l'application.</div>
                                </div>
                                
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="fas fa-save me-2"></i>Enregistrer les paramètres
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