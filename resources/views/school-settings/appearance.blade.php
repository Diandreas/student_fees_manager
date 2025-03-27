@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar des paramètres -->
        @include('school-settings.partials.sidebar')
        
        <!-- Contenu principal -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 text-primary-custom">Personnalisation de l'apparence</h1>
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
                        Personnalisation des couleurs
                    </h5>
                    <p class="text-muted mb-0">
                        Définissez les couleurs principales de votre interface
                    </p>
                </div>
                <div class="card-body">
                    <form action="{{ route('school.settings.appearance.update', $school) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="theme_color" class="form-label">Couleur principale</label>
                                    <div class="input-group">
                                        <input type="color" id="theme_color" name="theme_color" 
                                            class="form-control form-control-color" 
                                            value="{{ $school->theme_color ?? '#1a56db' }}">
                                        <input type="text" class="form-control" 
                                            value="{{ $school->theme_color ?? '#1a56db' }}" 
                                            id="theme_color_text">
                                    </div>
                                    <div class="form-text">Couleur principale de l'application</div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="secondary_color" class="form-label">Couleur secondaire</label>
                                    <div class="input-group">
                                        <input type="color" id="secondary_color" name="secondary_color" 
                                            class="form-control form-control-color" 
                                            value="{{ $school->secondary_color ?? '#9061F9' }}">
                                        <input type="text" class="form-control" 
                                            value="{{ $school->secondary_color ?? '#9061F9' }}" 
                                            id="secondary_color_text">
                                    </div>
                                    <div class="form-text">Couleur d'accentuation et détails</div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="header_color" class="form-label">Couleur d'entête</label>
                                    <div class="input-group">
                                        <input type="color" id="header_color" name="header_color" 
                                            class="form-control form-control-color" 
                                            value="{{ $school->header_color ?? '#1E40AF' }}">
                                        <input type="text" class="form-control" 
                                            value="{{ $school->header_color ?? '#1E40AF' }}" 
                                            id="header_color_text">
                                    </div>
                                    <div class="form-text">Couleur de la barre de navigation</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sidebar_color" class="form-label">Couleur de la barre latérale</label>
                                    <div class="input-group">
                                        <input type="color" id="sidebar_color" name="sidebar_color" 
                                            class="form-control form-control-color" 
                                            value="{{ $school->sidebar_color ?? '#1E293B' }}">
                                        <input type="text" class="form-control" 
                                            value="{{ $school->sidebar_color ?? '#1E293B' }}" 
                                            id="sidebar_color_text">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="text_color" class="form-label">Couleur du texte</label>
                                    <div class="input-group">
                                        <input type="color" id="text_color" name="text_color" 
                                            class="form-control form-control-color" 
                                            value="{{ $school->text_color ?? '#334155' }}">
                                        <input type="text" class="form-control" 
                                            value="{{ $school->text_color ?? '#334155' }}" 
                                            id="text_color_text">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="font_family" class="form-label">Police de caractères</label>
                                    <select class="form-select" id="font_family" name="font_family">
                                        <option value="Poppins" {{ ($school->font_family ?? '') == 'Poppins' ? 'selected' : '' }}>Poppins</option>
                                        <option value="Roboto" {{ ($school->font_family ?? '') == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                                        <option value="Open Sans" {{ ($school->font_family ?? '') == 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                                        <option value="Montserrat" {{ ($school->font_family ?? '') == 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
                                        <option value="Lato" {{ ($school->font_family ?? '') == 'Lato' ? 'selected' : '' }}>Lato</option>
                                    </select>
                                    <div class="form-text">Police utilisée dans l'interface</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="layout" class="form-label">Disposition</label>
                                    <select class="form-select" id="layout" name="layout">
                                        <option value="default" {{ ($school->layout ?? '') == 'default' ? 'selected' : '' }}>Standard</option>
                                        <option value="compact" {{ ($school->layout ?? '') == 'compact' ? 'selected' : '' }}>Compact</option>
                                        <option value="wide" {{ ($school->layout ?? '') == 'wide' ? 'selected' : '' }}>Large</option>
                                    </select>
                                    <div class="form-text">Organisation générale de l'interface</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="card_style" class="form-label">Style des cartes</label>
                                    <select class="form-select" id="card_style" name="card_style">
                                        <option value="default" {{ ($school->card_style ?? '') == 'default' ? 'selected' : '' }}>Standard</option>
                                        <option value="rounded" {{ ($school->card_style ?? '') == 'rounded' ? 'selected' : '' }}>Arrondi</option>
                                        <option value="flat" {{ ($school->card_style ?? '') == 'flat' ? 'selected' : '' }}>Plat</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="button_style" class="form-label">Style des boutons</label>
                                    <select class="form-select" id="button_style" name="button_style">
                                        <option value="default" {{ ($school->button_style ?? '') == 'default' ? 'selected' : '' }}>Standard</option>
                                        <option value="rounded" {{ ($school->button_style ?? '') == 'rounded' ? 'selected' : '' }}>Arrondi</option>
                                        <option value="flat" {{ ($school->button_style ?? '') == 'flat' ? 'selected' : '' }}>Plat</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="show_animations" name="show_animations" 
                                            {{ ($school->preferences['show_animations'] ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_animations">Activer les animations</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary" id="previewTheme">
                                <i class="fas fa-eye me-2"></i>Prévisualiser
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary-custom">
                        Thèmes prédéfinis
                    </h5>
                    <p class="text-muted mb-0">
                        Sélectionnez un thème prêt à l'emploi
                    </p>
                </div>
                <div class="card-body">
                    <x-theme-switcher />
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .color-swatch {
        width: 30px;
        height: 30px;
        border-radius: 4px;
    }
    
    .theme-preset {
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .theme-preset:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Synchroniser les champs de couleur avec les champs texte
        const colorInputs = document.querySelectorAll('input[type="color"]');
        colorInputs.forEach(input => {
            const textInput = document.getElementById(input.id + '_text');
            
            input.addEventListener('input', function() {
                textInput.value = this.value;
            });
            
            textInput.addEventListener('input', function() {
                input.value = this.value;
            });
        });
        
        // Prévisualisation du thème
        document.getElementById('previewTheme').addEventListener('click', function() {
            // Récupérer les valeurs des couleurs
            const themeColor = document.getElementById('theme_color').value;
            const secondaryColor = document.getElementById('secondary_color').value;
            const headerColor = document.getElementById('header_color').value;
            
            // Appliquer temporairement les couleurs
            document.documentElement.style.setProperty('--primary-color', themeColor);
            document.documentElement.style.setProperty('--secondary-color', secondaryColor);
            document.documentElement.style.setProperty('--header-color', headerColor);
            document.documentElement.style.setProperty('--accent-color', secondaryColor);
            
            // Notifier l'utilisateur
            alert('Les couleurs ont été appliquées temporairement pour prévisualisation. Cliquez sur "Enregistrer les modifications" pour les conserver.');
        });
        
        // Appliquer les thèmes prédéfinis
        document.querySelectorAll('.apply-theme').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const theme = this.dataset.theme;
                
                if (theme === 'classic') {
                    document.getElementById('theme_color').value = '#0A3D62';
                    document.getElementById('theme_color_text').value = '#0A3D62';
                    document.getElementById('secondary_color').value = '#1E5B94';
                    document.getElementById('secondary_color_text').value = '#1E5B94';
                    document.getElementById('header_color').value = '#071E3D';
                    document.getElementById('header_color_text').value = '#071E3D';
                    document.getElementById('sidebar_color').value = '#1E293B';
                    document.getElementById('sidebar_color_text').value = '#1E293B';
                    document.getElementById('text_color').value = '#334155';
                    document.getElementById('text_color_text').value = '#334155';
                } else if (theme === 'modern') {
                    document.getElementById('theme_color').value = '#1A237E';
                    document.getElementById('theme_color_text').value = '#1A237E';
                    document.getElementById('secondary_color').value = '#3949AB';
                    document.getElementById('secondary_color_text').value = '#3949AB';
                    document.getElementById('header_color').value = '#0D1B2A';
                    document.getElementById('header_color_text').value = '#0D1B2A';
                    document.getElementById('sidebar_color').value = '#1E293B';
                    document.getElementById('sidebar_color_text').value = '#1E293B';
                    document.getElementById('text_color').value = '#334155';
                    document.getElementById('text_color_text').value = '#334155';
                } else if (theme === 'nature') {
                    document.getElementById('theme_color').value = '#2E7D32';
                    document.getElementById('theme_color_text').value = '#2E7D32';
                    document.getElementById('secondary_color').value = '#388E3C';
                    document.getElementById('secondary_color_text').value = '#388E3C';
                    document.getElementById('header_color').value = '#1B5E20';
                    document.getElementById('header_color_text').value = '#1B5E20';
                    document.getElementById('sidebar_color').value = '#2A3B32';
                    document.getElementById('sidebar_color_text').value = '#2A3B32';
                    document.getElementById('text_color').value = '#33403A';
                    document.getElementById('text_color_text').value = '#33403A';
                }
                
                // Prévisualiser les couleurs
                document.getElementById('previewTheme').click();
            });
        });
    });
</script>
@endpush
@endsection 