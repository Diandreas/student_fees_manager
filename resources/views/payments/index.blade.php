@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold text-primary-custom">
                        <i class="fas fa-money-bill-wave me-2"></i>{{ session('current_school')->term('payments', 'Paiements') }}
                    </h5>
                    
                    <div>
                        <a href="{{ route('payments.create') }}" class="btn btn-primary-custom btn-sm">
                            <i class="fas fa-plus-circle me-1"></i> {{ session('current_school')->term('new_payment', 'Nouveau paiement') }}
                        </a>
                        <a href="{{ route('payments.export') }}" class="btn btn-outline-primary-custom btn-sm ms-2">
                            <i class="fas fa-file-excel me-1"></i> {{ session('current_school')->term('export', 'Exporter tout') }}
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="card border-0 bg-primary-custom bg-opacity-10 h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary-custom p-3 me-3">
                                            <i class="fas fa-money-bill-wave text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-muted">{{ session('current_school')->term('total_payments', 'Total des paiements') }}</h6>
                                            <h4 class="mb-0 fw-bold text-primary-custom">{{ number_format($totalAmount, 0, ',', ' ') }} {{ session('current_school')->term('currency', 'FCFA') }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="card border-0 bg-success bg-opacity-10 h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-success p-3 me-3">
                                            <i class="fas fa-file-invoice text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-muted">{{ session('current_school')->term('number_of_payments', 'Nombre de paiements') }}</h6>
                                            <h4 class="mb-0 fw-bold text-success">{{ $payments->count() }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card border-0 bg-info bg-opacity-10 h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-info p-3 me-3">
                                            <i class="fas fa-calendar-alt text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-muted">{{ session('current_school')->term('last_payment', 'Dernier paiement') }}</h6>
                                            <h4 class="mb-0 fw-bold text-info">
                                                @if($payments->count() > 0)
                                                {{ \Carbon\Carbon::parse($payments->first()->payment_date)->format('d/m/Y') }}
                                                @else
                                                -
                                                @endif
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover border-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ session('current_school')->term('receipt_number', 'Reçu N°') }}</th>
                                    <th>{{ session('current_school')->term('student', 'Étudiant') }}</th>
                                    <th>{{ session('current_school')->term('field', 'Filière') }}</th>
                                    <th>{{ session('current_school')->term('amount', 'Montant') }}</th>
                                    <th>{{ session('current_school')->term('payment_date', 'Date') }}</th>
                                    <th>{{ session('current_school')->term('payment_method', 'Méthode') }}</th>
                                    <th class="text-end">{{ session('current_school')->term('actions', 'Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($payments as $payment)
                                    <tr>
                                        <td><span class="badge bg-secondary">{{ $payment->receipt_number }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-primary-custom bg-opacity-10 me-2">
                                                    {{ strtoupper(substr($payment->student->fullName, 0, 1)) }}
                                                </div>
                                                {{ $payment->student->fullName }}
                                            </div>
                                        </td>
                                        <td>{{ $payment->student->field->name }}</td>
                                        <td><span class="fw-bold text-success">{{ number_format($payment->amount, 0, ',', ' ') }} {{ session('current_school')->term('currency', 'FCFA') }}</span></td>
                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge bg-info text-dark">
                                                @if($payment->payment_method == 'cash')
                                                <i class="fas fa-money-bill-wave me-1"></i>
                                                @elseif($payment->payment_method == 'bank')
                                                <i class="fas fa-university me-1"></i>
                                                @elseif($payment->payment_method == 'mobile')
                                                <i class="fas fa-mobile-alt me-1"></i>
                                                @else
                                                <i class="fas fa-credit-card me-1"></i>
                                                @endif
                                                {{ ucfirst($payment->payment_method) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group float-end" role="group">
                                                <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('payments.export', $payment->student_id) }}" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Exporter">
                                                    <i class="fas fa-file-excel"></i>
                                                </a>
                                                <a href="{{ route('payments.print', $payment->id) }}" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Imprimer reçu">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Supprimer" onclick="return confirm('{{ session('current_school')->term('confirm_delete', 'Êtes-vous sûr de vouloir supprimer ce paiement?') }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                                <h6 class="text-muted">{{ session('current_school')->term('no_payments', 'Aucun paiement trouvé') }}</h6>
                                                <a href="{{ route('payments.create') }}" class="btn btn-sm btn-primary-custom mt-3">
                                                    <i class="fas fa-plus-circle me-1"></i> {{ session('current_school')->term('new_payment', 'Nouveau paiement') }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: var(--primary-color);
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.delete-form {
    display: inline-block;
}
</style>

@push('scripts')
<script>
    // Initialiser les tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush
@endsection
