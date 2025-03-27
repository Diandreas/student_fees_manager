@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">{{ $campus->name }}</h2>
                <div>
                    <a href="{{ route('campuses.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                    <a href="{{ route('campuses.edit', $campus) }}" class="btn btn-primary me-2">
                        <i class="fas fa-edit me-1"></i> Modifier
                    </a>
                    <a href="{{ route('fields.create', ['campus_id' => $campus->id]) }}" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Ajouter une filière
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Informations</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4 text-center">
                        <div class="rounded-circle bg-primary mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px; opacity: 0.8;">
                            <span class="fs-1 text-white">{{ substr($campus->name, 0, 1) }}</span>
                        </div>
                        <h4 class="mb-0">{{ $campus->name }}</h4>
                        @if($currentSchool)
                            <p class="text-muted">{{ $currentSchool->name }}</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        @if($campus->description)
                            <p>{{ $campus->description }}</p>
                        @endif
                        <p class="mb-1"><strong>Nombre de filières:</strong> {{ $campus->fields->count() }}</p>
                        <p class="mb-0"><strong>Total étudiants:</strong> {{ $campus->fields->sum('students_count') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold">Filières</h5>
                    <a href="{{ route('fields.create', ['campus_id' => $campus->id]) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus me-1"></i> Nouvelle filière
                    </a>
                </div>
                <div class="card-body">
                    @if($groupedFields->count() > 0)
                        <div class="accordion" id="accordionFields">
                            @foreach($groupedFields as $fieldName => $fields)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ \Illuminate\Support\Str::slug($fieldName) }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                                data-bs-target="#collapse{{ \Illuminate\Support\Str::slug($fieldName) }}" aria-expanded="false" 
                                                aria-controls="collapse{{ \Illuminate\Support\Str::slug($fieldName) }}">
                                            <span class="fw-bold">{{ $fieldName }}</span>
                                            <span class="badge bg-primary rounded-pill ms-2">{{ $fields->count() }} classe(s)</span>
                                            <span class="badge bg-info rounded-pill ms-2">{{ $fields->sum('students_count') }} étudiants</span>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ \Illuminate\Support\Str::slug($fieldName) }}" class="accordion-collapse collapse" 
                                         aria-labelledby="heading{{ \Illuminate\Support\Str::slug($fieldName) }}" data-bs-parent="#accordionFields">
                                        <div class="accordion-body p-0">
                                            <div class="list-group list-group-flush">
                                                @foreach($fields as $field)
                                                    <a href="{{ route('fields.show', $field) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-1">{{ $field->name }}</h6>
                                                            <p class="mb-0 text-muted small">
                                                                @if($field->code)
                                                                    Code: {{ $field->code }} | 
                                                                @endif
                                                                @if(isset($field->education_level) && $field->education_level)
                                                                    Niveau: {{ $field->education_level->name }} | 
                                                                @endif
                                                                Frais: {{ number_format($field->fees, 0, ',', ' ') }} FCFA
                                                            </p>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge bg-primary rounded-pill me-3">{{ $field->students_count }} étudiants</span>
                                                            <i class="fas fa-chevron-right text-muted"></i>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <p class="mb-0">Aucune filière n'a été créée pour ce campus.</p>
                            <a href="{{ route('fields.create', ['campus_id' => $campus->id]) }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-1"></i> Ajouter une filière
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 