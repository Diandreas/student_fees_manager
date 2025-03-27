@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow rounded-3 border-0">
                <div class="card-header bg-primary-custom text-white py-3">
                    <h4 class="mb-0 text-center">{{ __('Inscription') }}</h4>
                </div>

                <div class="card-body p-4">
                    <div class="progress mb-4">
                        <div class="progress-bar bg-primary-custom" role="progressbar" id="registrationProgress" style="width: 33%;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100">Étape 1/3</div>
                    </div>
                    
                    <form method="POST" action="{{ route('register') }}" id="registrationForm">
                        @csrf
                        <input type="hidden" name="current_step" id="current_step" value="1">
                        
                        <!-- Étape 1: Informations de l'école -->
                        <div class="form-step" id="step1">
                            <h5 class="text-primary-custom mb-4 border-bottom pb-2">{{ __('Informations de l\'établissement') }}</h5>
                            
                            <div class="mb-3">
                                <label for="school_name" class="form-label">{{ __('Nom de l\'établissement') }}</label>
                                <input id="school_name" type="text" class="form-control @error('school_name') is-invalid @enderror" name="school_name" value="{{ old('school_name') }}" required>
                                @error('school_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="school_email" class="form-label">{{ __('Email de l\'établissement') }}</label>
                                <input id="school_email" type="email" class="form-control @error('school_email') is-invalid @enderror" name="school_email" value="{{ old('school_email') }}" required>
                                @error('school_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="school_phone" class="form-label">{{ __('Téléphone de l\'établissement') }}</label>
                                <input id="school_phone" type="text" class="form-control @error('school_phone') is-invalid @enderror" name="school_phone" value="{{ old('school_phone') }}">
                                @error('school_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="school_address" class="form-label">{{ __('Adresse de l\'établissement') }}</label>
                                <input id="school_address" type="text" class="form-control @error('school_address') is-invalid @enderror" name="school_address" value="{{ old('school_address') }}">
                                @error('school_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-primary next-step">{{ __('Suivant') }} <i class="fas fa-arrow-right ms-1"></i></button>
                            </div>
                        </div>
                        
                        <!-- Étape 2: Informations personnelles -->
                        <div class="form-step d-none" id="step2">
                            <h5 class="text-primary-custom mb-4 border-bottom pb-2">{{ __('Informations de l\'administrateur') }}</h5>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Nom complet') }}</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Adresse Email') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary prev-step"><i class="fas fa-arrow-left me-1"></i> {{ __('Précédent') }}</button>
                                <button type="button" class="btn btn-primary next-step">{{ __('Suivant') }} <i class="fas fa-arrow-right ms-1"></i></button>
                            </div>
                        </div>
                        
                        <!-- Étape 3: Mot de passe et confirmation -->
                        <div class="form-step d-none" id="step3">
                            <h5 class="text-primary-custom mb-4 border-bottom pb-2">{{ __('Sécurité du compte') }}</h5>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Mot de passe') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="password-confirm" class="form-label">{{ __('Confirmer le mot de passe') }}</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    {{ __('J\'accepte les conditions d\'utilisation et la politique de confidentialité') }}
                                </label>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary prev-step"><i class="fas fa-arrow-left me-1"></i> {{ __('Précédent') }}</button>
                                <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i> {{ __('S\'inscrire') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light py-3 text-center">
                    {{ __('Vous avez déjà un compte?') }}
                    <a href="{{ route('login') }}" class="text-primary-custom fw-bold">{{ __('Connectez-vous') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
    const steps = document.querySelectorAll('.form-step');
    const progressBar = document.getElementById('registrationProgress');
    const currentStepInput = document.getElementById('current_step');
    
    // Progression d'une étape à la suivante
    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', function() {
            const currentStep = parseInt(currentStepInput.value);
            const nextStep = currentStep + 1;
            
            // Vérifier la validité du formulaire avant de passer à l'étape suivante
            let isValid = true;
            const inputs = steps[currentStep-1].querySelectorAll('input[required]');
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) return;
            
            // Passer à l'étape suivante
            steps[currentStep-1].classList.add('d-none');
            steps[nextStep-1].classList.remove('d-none');
            
            // Mettre à jour la progression
            currentStepInput.value = nextStep;
            const progress = (nextStep / steps.length) * 100;
            progressBar.style.width = progress + '%';
            progressBar.setAttribute('aria-valuenow', progress);
            progressBar.textContent = 'Étape ' + nextStep + '/' + steps.length;
        });
    });
    
    // Retour à l'étape précédente
    document.querySelectorAll('.prev-step').forEach(button => {
        button.addEventListener('click', function() {
            const currentStep = parseInt(currentStepInput.value);
            const prevStep = currentStep - 1;
            
            steps[currentStep-1].classList.add('d-none');
            steps[prevStep-1].classList.remove('d-none');
            
            // Mettre à jour la progression
            currentStepInput.value = prevStep;
            const progress = (prevStep / steps.length) * 100;
            progressBar.style.width = progress + '%';
            progressBar.setAttribute('aria-valuenow', progress);
            progressBar.textContent = 'Étape ' + prevStep + '/' + steps.length;
        });
    });
});
</script>
@endsection
