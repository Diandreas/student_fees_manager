@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-presidential-blue text-white p-4">
                    <h4 class="mb-0 font-weight-bold">Inscription de votre établissement scolaire</h4>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('school.register') }}">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-presidential-blue border-bottom pb-2 mb-3">Informations sur l'établissement</h5>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="school_name" class="form-label">Nom de l'établissement</label>
                                <input id="school_name" type="text" class="form-control @error('school_name') is-invalid @enderror" name="school_name" value="{{ old('school_name') }}" required autocomplete="school_name" autofocus>
                                @error('school_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="school_email" class="form-label">Email de l'établissement</label>
                                <input id="school_email" type="email" class="form-control @error('school_email') is-invalid @enderror" name="school_email" value="{{ old('school_email') }}" required autocomplete="email">
                                @error('school_email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="school_phone" class="form-label">Téléphone de l'établissement</label>
                                <input id="school_phone" type="text" class="form-control @error('school_phone') is-invalid @enderror" name="school_phone" value="{{ old('school_phone') }}" autocomplete="tel">
                                @error('school_phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="school_address" class="form-label">Adresse de l'établissement</label>
                                <input id="school_address" type="text" class="form-control @error('school_address') is-invalid @enderror" name="school_address" value="{{ old('school_address') }}" autocomplete="address">
                                @error('school_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-presidential-blue border-bottom pb-2 mb-3">Compte administrateur principal</h5>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nom complet</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email administrateur</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password-confirm" class="form-label">Confirmer le mot de passe</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    En vous inscrivant, vous acceptez nos <a href="#" class="alert-link">conditions d'utilisation</a> et notre <a href="#" class="alert-link">politique de confidentialité</a>.
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-presidential-blue btn-lg px-4">
                                    <i class="fas fa-school me-2"></i> Inscrire mon établissement
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="card-footer bg-light p-3 text-center">
                    Vous avez déjà un compte ? <a href="{{ route('login') }}" class="text-presidential-blue font-weight-bold">Connectez-vous</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-presidential-blue {
    background-color: #0d47a1;
}
.text-presidential-blue {
    color: #0d47a1;
}
.btn-presidential-blue {
    background-color: #0d47a1;
    color: white;
}
.btn-presidential-blue:hover {
    background-color: #093777;
    color: white;
}
</style>
@endsection 