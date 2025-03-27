@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Sélectionner une école</h5>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <p>Veuillez sélectionner une école pour accéder à votre espace de gestion :</p>
                    </div>

                    <div class="row">
                        @foreach($schools as $school)
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-hover shadow-sm">
                                    <div class="card-body text-center">
                                        @if($school->logo)
                                            <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="img-fluid mb-3" style="max-height: 80px;">
                                        @else
                                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; background-color: {{ $school->theme_color ?? '#1a56db' }}">
                                                <span class="text-white font-weight-bold" style="font-size: 2rem;">{{ substr($school->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <h5 class="card-title">{{ $school->name }}</h5>
                                        @if($school->address)
                                            <p class="card-text text-muted small">{{ $school->address }}</p>
                                        @endif
                                        <form action="{{ route('schools.switch', $school) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary mt-3" style="background-color: {{ $school->theme_color ?? '#1a56db' }}; border-color: {{ $school->theme_color ?? '#1a56db' }};">
                                                Sélectionner
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(count($schools) == 0)
                        <div class="alert alert-info">
                            <p class="mb-0">Vous n'avez accès à aucune école pour le moment.</p>
                            
                            @if(Auth::user()->is_superadmin)
                                <div class="mt-3">
                                    <a href="{{ route('schools.create') }}" class="btn btn-primary">Créer une école</a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 