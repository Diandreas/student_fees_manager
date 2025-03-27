@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1">
                            <li class="breadcrumb-item"><a href="{{ route('campuses.index') }}">Campus</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('campuses.show', $field->campus) }}">{{ $field->campus->name }}</a></li>
                            <li class="breadcrumb-item active">{{ $field->name }}</li>
                        </ol>
                    </nav>
                    <h2 class="mb-0">{{ $field->name }}</h2>
                    @if($field->code)
                        <span class="badge bg-secondary">Code: {{ $field->code }}</span>
                    @endif
                    @if($field->educationLevel)
                        <span class="badge bg-info">Niveau: {{ $field->educationLevel->name }}</span>
                    @endif
                </div>
                <div class="mt-2 mt-md-0">
                    <a href="{{ route('campuses.show', $field->campus) }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Retour au campus
                    </a>
                    <a href="{{ route('fields.edit', $field) }}" class="btn btn-primary me-2">
                        <i class="fas fa-edit me-1"></i> Modifier
                    </a>
                    <a href="{{ route('students.create', ['field_id' => $field->id]) }}" class="btn btn-success">
                        <i class="fas fa-user-plus me-1"></i> Ajouter un étudiant
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Afficher les classes similaires s'il y en a -->
    @if($similarFields->count() > 0)
    <div class="row">
        <div class="col-12 mb-4">
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="fas fa-info-circle me-3 fs-4"></i>
                <div>
                    <h5 class="alert-heading mb-1">Classes similaires</h5>
                    <p class="mb-0">
                        Il existe {{ $similarFields->count() }} autre(s) classe(s) "{{ $field->name }}" dans ce campus. 
                        Vous pouvez les consulter ici :
                    </p>
                    <div class="mt-2">
                        @foreach($similarFields as $similarField)
                            <a href="{{ route('fields.show', $similarField) }}" class="btn btn-sm btn-outline-primary me-2 mb-2">
                                {{ $similarField->name }} 
                                @if($similarField->code)
                                    ({{ $similarField->code }})
                                @endif
                                - {{ $similarField->students->count() }} étudiants
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Informations</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4 text-center">
                        <div class="rounded-circle bg-info mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px; opacity: 0.8;">
                            <span class="fs-1 text-white">{{ substr($field->name, 0, 1) }}</span>
                        </div>
                        <h4 class="mb-0">{{ $field->name }}</h4>
                        <p class="text-muted">{{ $field->campus->name }}</p>
                    </div>

                    <div class="mb-3">
                        @if($field->educationLevel)
                            <p class="mb-1"><strong>Niveau d'éducation:</strong> {{ $field->educationLevel->name }}</p>
                        @endif
                        <p class="mb-1"><strong>Frais d'inscription:</strong> {{ number_format($field->fees, 0, ',', ' ') }} FCFA</p>
                        <p class="mb-1"><strong>Nombre d'étudiants:</strong> {{ $studentStats['total'] }}</p>
                        
                        @if($field->code)
                            <p class="mb-1"><strong>Code:</strong> {{ $field->code }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Statistiques de paiement -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Statistiques de paiement</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6 border-end">
                            <h6 class="text-muted mb-1">Total frais</h6>
                            <h4 class="mb-0">{{ number_format($studentStats['totalFees'], 0, ',', ' ') }} FCFA</h4>
                        </div>
                        <div class="col-6">
                            <h6 class="text-muted mb-1">Payé</h6>
                            <h4 class="mb-0 {{ $studentStats['paymentPercentage'] == 100 ? 'text-success' : 'text-warning' }}">
                                {{ number_format($studentStats['totalPaid'], 0, ',', ' ') }} FCFA
                            </h4>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0">Progression</h6>
                            <span>{{ $studentStats['paymentPercentage'] }}%</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-{{ $studentStats['paymentPercentage'] == 100 ? 'success' : 'warning' }}" role="progressbar" 
                                 style="width: {{ $studentStats['paymentPercentage'] }}%;" 
                                 aria-valuenow="{{ $studentStats['paymentPercentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="p-2 rounded bg-success bg-opacity-10 mb-2">
                                <h2 class="mb-0 text-success">{{ $studentStats['paid'] }}</h2>
                            </div>
                            <h6 class="text-muted small">Payé intégralement</h6>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded bg-warning bg-opacity-10 mb-2">
                                <h2 class="mb-0 text-warning">{{ $studentStats['partial'] }}</h2>
                            </div>
                            <h6 class="text-muted small">Partiellement</h6>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded bg-danger bg-opacity-10 mb-2">
                                <h2 class="mb-0 text-danger">{{ $studentStats['unpaid'] }}</h2>
                            </div>
                            <h6 class="text-muted small">Aucun paiement</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold">Liste des étudiants</h5>
                    <div class="d-flex">
                        <a href="{{ route('students.create', ['field_id' => $field->id]) }}" class="btn btn-sm btn-success me-2">
                            <i class="fas fa-user-plus me-1"></i> Nouvel étudiant
                        </a>
                        <a href="{{ route('fields.report', $field) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-file-export me-1"></i> Exporter
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($field->students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0">Nom complet</th>
                                        <th class="border-0">Email</th>
                                        <th class="border-0">Téléphone</th>
                                        <th class="border-0">Statut paiement</th>
                                        <th class="border-0">Montant payé</th>
                                        <th class="border-0">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($field->students as $student)
                                        <tr>
                                            <td>
                                                <a href="{{ route('students.show', $student) }}" class="text-decoration-none fw-bold text-dark">
                                                    {{ $student->fullName }}
                                                </a>
                                            </td>
                                            <td>{{ $student->email }}</td>
                                            <td>{{ $student->phone ?? 'N/A' }}</td>
                                            <td>
                                                @if($student->payment_status === 'paid')
                                                    <span class="badge bg-success">Payé</span>
                                                @elseif($student->payment_status === 'partial')
                                                    <span class="badge bg-warning">Partiel</span>
                                                @else
                                                    <span class="badge bg-danger">Non payé</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span>{{ number_format($student->paid_amount, 0, ',', ' ') }} FCFA</span>
                                                    <div class="progress mt-1" style="height: 4px; width: 80px;">
                                                        <div class="progress-bar bg-{{ $student->payment_status === 'paid' ? 'success' : ($student->payment_status === 'partial' ? 'warning' : 'danger') }}" 
                                                             style="width: {{ $field->fees > 0 ? round(($student->paid_amount / $field->fees) * 100) : 0 }}%;"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('students.show', $student) }}" class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" class="btn btn-outline-success">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </a>
                                                    <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-secondary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info m-3 text-center">
                            <p class="mb-0">Aucun étudiant n'a été inscrit dans cette filière.</p>
                            <a href="{{ route('students.create', ['field_id' => $field->id]) }}" class="btn btn-primary mt-3">
                                <i class="fas fa-user-plus me-1"></i> Ajouter un étudiant
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 