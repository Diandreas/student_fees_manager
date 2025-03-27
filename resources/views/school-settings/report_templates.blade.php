@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar des paramètres -->
        @include('school-settings.partials.sidebar')
        
        <!-- Contenu principal -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 text-primary-custom">Modèles de rapports</h1>
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
                        Personnalisation des rapports
                    </h5>
                    <p class="text-muted mb-0">
                        Configurez l'apparence et le contenu des rapports générés par le système
                    </p>
                </div>
                <div class="card-body">
                    <form action="{{ route('school.settings.reports.update', $school) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Entête des rapports</label>
                                    <div class="input-group">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="show_logo" name="report_settings[show_logo]" 
                                                {{ $school->report_settings['show_logo'] ?? true ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_logo">Afficher le logo</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="report_header_text" class="form-label">Texte d'entête supplémentaire</label>
                                    <textarea id="report_header_text" name="report_settings[header_text]" class="form-control" rows="2">{{ $school->report_settings['header_text'] ?? '' }}</textarea>
                                    <div class="form-text">Texte additionnel qui apparaîtra sous le nom de l'école dans l'entête</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="report_footer_text" class="form-label">Texte de pied de page</label>
                                    <textarea id="report_footer_text" name="report_settings[footer_text]" class="form-control" rows="2">{{ $school->report_settings['footer_text'] ?? '' }}</textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="report_signature" class="form-label">Signature</label>
                                    <input type="file" id="report_signature" name="report_signature" class="form-control" accept="image/*">
                                    @if($school->report_settings['signature_image'] ?? null)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $school->report_settings['signature_image']) }}" alt="Signature" class="img-thumbnail" style="max-height: 60px;">
                                            <div class="form-check mt-1">
                                                <input class="form-check-input" type="checkbox" id="remove_signature" name="remove_signature">
                                                <label class="form-check-label" for="remove_signature">Supprimer la signature</label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label">Couleurs des rapports</label>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="report_header_color" class="form-label">Couleur d'entête</label>
                                        <input type="color" id="report_header_color" name="report_settings[header_color]" 
                                            class="form-control form-control-color w-100" 
                                            value="{{ $school->report_settings['header_color'] ?? $school->theme_color }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="report_accent_color" class="form-label">Couleur d'accentuation</label>
                                        <input type="color" id="report_accent_color" name="report_settings[accent_color]" 
                                            class="form-control form-control-color w-100" 
                                            value="{{ $school->report_settings['accent_color'] ?? $school->secondary_color }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="report_text_color" class="form-label">Couleur du texte d'entête</label>
                                        <input type="color" id="report_text_color" name="report_settings[text_color]" 
                                            class="form-control form-control-color w-100" 
                                            value="{{ $school->report_settings['text_color'] ?? '#ffffff' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label">Contenu des rapports</label>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="show_school_info" name="report_settings[show_school_info]" 
                                                {{ $school->report_settings['show_school_info'] ?? true ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_school_info">Informations de l'école</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="show_watermark" name="report_settings[show_watermark]" 
                                                {{ $school->report_settings['show_watermark'] ?? false ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_watermark">Filigrane</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="show_date" name="report_settings[show_date]" 
                                                {{ $school->report_settings['show_date'] ?? true ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_date">Date de génération</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary-custom">
                        Aperçu du rapport
                    </h5>
                </div>
                <div class="card-body text-center">
                    <p class="mb-3">Générez un aperçu pour voir comment vos rapports seront formatés</p>
                    <a href="{{ route('school.settings.reports.preview') }}" class="btn btn-secondary" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>Aperçu du rapport
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 