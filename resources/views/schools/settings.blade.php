@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-0">Paramètres de l'école</h1>
            <p class="text-muted">Gérez les informations et configurations de votre établissement</p>
        </div>
    </div>

    <form action="{{ route('schools.settings.update', $school) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Colonne gauche -->
            <div class="col-lg-8">
                <!-- Informations générales -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Informations générales</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nom de l'école <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $school->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $school->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $school->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="address" class="form-label">Adresse</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="1">{{ old('address', $school->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="description" class="form-label">Description de l'école</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $school->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paramètres de facturation -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Paramètres de facturation</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="currency" class="form-label">Devise <span class="text-danger">*</span></label>
                                <select class="form-select @error('currency') is-invalid @enderror" id="currency" name="currency" required>
                                    <option value="XAF" {{ old('currency', $school->currency) == 'XAF' ? 'selected' : '' }}>XAF (Franc CFA)</option>
                                    <option value="EUR" {{ old('currency', $school->currency) == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                                    <option value="USD" {{ old('currency', $school->currency) == 'USD' ? 'selected' : '' }}>USD (Dollar)</option>
                                </select>
                                @error('currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="payment_due_days" class="form-label">Délai de paiement (jours) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('payment_due_days') is-invalid @enderror" id="payment_due_days" name="payment_due_days" value="{{ old('payment_due_days', $school->payment_due_days) }}" required min="1">
                                @error('payment_due_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="late_payment_fee" class="form-label">Frais de retard (%)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('late_payment_fee') is-invalid @enderror" id="late_payment_fee" name="late_payment_fee" value="{{ old('late_payment_fee', $school->late_payment_fee) }}" min="0" max="100">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('late_payment_fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Paramètres de notification</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="has_email_notifications" name="has_email_notifications" {{ old('has_email_notifications', $school->has_email_notifications) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="has_email_notifications">Activer les notifications par email</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="has_sms_notifications" name="has_sms_notifications" {{ old('has_sms_notifications', $school->has_sms_notifications) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="has_sms_notifications">Activer les notifications par SMS</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mt-3">
                                <label for="notification_settings_email_sender" class="form-label">Nom de l'expéditeur email</label>
                                <input type="text" class="form-control" id="notification_settings_email_sender" 
                                    name="notification_settings[email_sender]" 
                                    value="{{ $school->notification_settings['email_sender'] ?? $school->name }}"
                                    placeholder="{{ $school->name }}">
                            </div>
                            
                            <div class="col-md-6 mt-3">
                                <label for="notification_settings_reply_to" class="form-label">Adresse de réponse</label>
                                <input type="email" class="form-control" id="notification_settings_reply_to" 
                                    name="notification_settings[reply_to]" 
                                    value="{{ $school->notification_settings['reply_to'] ?? $school->email }}"
                                    placeholder="{{ $school->email }}">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Terminologie -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Terminologie</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Personnalisez les termes utilisés dans l'application</p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="terminology_student" class="form-label">Étudiant</label>
                                <input type="text" class="form-control" id="terminology_student" 
                                       name="terminology[student]" 
                                       value="{{ $school->terminology['student'] ?? 'Étudiant' }}">
                            </div>
                            <div class="col-md-4">
                                <label for="terminology_students" class="form-label">Étudiants</label>
                                <input type="text" class="form-control" id="terminology_students" 
                                       name="terminology[students]" 
                                       value="{{ $school->terminology['students'] ?? 'Étudiants' }}">
                            </div>
                            <div class="col-md-4">
                                <label for="terminology_class" class="form-label">Classe</label>
                                <input type="text" class="form-control" id="terminology_class" 
                                       name="terminology[class]" 
                                       value="{{ $school->terminology['class'] ?? 'Classe' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite -->
            <div class="col-lg-4">
                <!-- Logo -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Logo de l'école</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            @if($school->logo)
                                <img src="{{ Storage::url($school->logo) }}" alt="Logo" class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: contain;">
                            @else
                                <div class="img-thumbnail d-flex align-items-center justify-content-center bg-light mx-auto" style="width: 150px; height: 150px;">
                                    <i class="fas fa-school fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Changer le logo</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted d-block">Format recommandé: png ou jpg, 512x512px maximum</small>
                    </div>
                </div>

                <!-- État de l'école -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">État de l'école</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active', $school->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">École active</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="has_online_payments" name="has_online_payments" {{ old('has_online_payments', $school->has_online_payments) ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_online_payments">Activer les paiements en ligne</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="row">
            <div class="col-12 text-end">
                <button type="reset" class="btn btn-light me-2">Annuler</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                </button>
            </div>
        </div>
    </form>
</div>
@endsection 