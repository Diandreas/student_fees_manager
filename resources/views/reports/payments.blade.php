@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <h1 class="h3 fw-bold text-primary-custom mb-0">
                        <i class="fas fa-money-bill-wave me-2"></i>Rapport des Paiements
                    </h1>
                    <div>
                        <a href="{{ route('payments.export') }}" class="btn btn-outline-success me-2">
                            <i class="fas fa-file-excel me-2"></i>Exporter Excel
                        </a>
                        <a href="{{ route('reports.payments.pdf') }}" class="btn btn-outline-danger me-2">
                            <i class="fas fa-file-pdf me-2"></i>Exporter PDF
                        </a>
                        <a href="{{ route('payments.print-list') }}" class="btn btn-outline-secondary me-2" target="_blank">
                            <i class="fas fa-print me-2"></i>Imprimer
                        </a>
                        <a href="{{ route('reports.index') }}" class="btn btn-outline-primary-custom">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Indicateurs clés -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto me-3">
                            <div class="rounded-circle bg-primary-custom bg-opacity-10 p-3">
                                <i class="fas fa-money-bill-wave fa-2x text-primary-custom"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-muted small mb-1">Total des paiements</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($totalPayments, 0, ',', ' ') }} FCFA</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto me-3">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                <i class="fas fa-receipt fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-muted small mb-1">Nombre de transactions</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($paymentsCount, 0, ',', ' ') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto me-3">
                            <div class="rounded-circle bg-info bg-opacity-10 p-3">
                                <i class="fas fa-user-graduate fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-muted small mb-1">Étudiants ayant payé</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($studentsWithPayments, 0, ',', ' ') }} / {{ number_format($studentsCount, 0, ',', ' ') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto me-3">
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                <i class="fas fa-calculator fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-muted small mb-1">Moyenne par transaction</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $paymentsCount > 0 ? number_format($totalPayments / $paymentsCount, 0, ',', ' ') : 0 }} FCFA</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <!-- Graphique d'évolution des paiements -->
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-bold text-primary-custom">
                        <i class="fas fa-chart-line me-2"></i>Évolution des paiements (12 derniers mois)
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div style="height: 300px;">
                        <canvas id="paymentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Paiements par filière -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-bold text-primary-custom">
                        <i class="fas fa-graduation-cap me-2"></i>Paiements par filière
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($paymentsByField->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Filière</th>
                                        <th class="text-end">Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paymentsByField as $field)
                                    <tr>
                                        <td>{{ $field->name }}</td>
                                        <td class="text-end">{{ number_format($field->total, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-circle fa-3x text-muted mb-3"></i>
                            <p>Aucune donnée disponible</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Derniers paiements -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-bold text-primary-custom">
                        <i class="fas fa-list me-2"></i>Derniers paiements
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 table-custom">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col" class="ps-4">Référence</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Étudiant</th>
                                    <th scope="col">Filière</th>
                                    <th scope="col">Montant</th>
                                    <th scope="col" class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayments as $payment)
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $payment->receipt_number }}</td>
                                    <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                    <td>{{ $payment->student->fullName }}</td>
                                    <td>{{ $payment->student->field->name ?? 'Non assigné' }}</td>
                                    <td>
                                        <span class="badge bg-success text-white py-2 px-3">
                                            {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('payments.print', $payment) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                            <h5>Aucun paiement trouvé</h5>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique d'évolution des paiements
    const paymentLabels = @json($labels);
    const paymentData = @json($data);
    
    const ctx = document.getElementById('paymentChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: paymentLabels,
            datasets: [{
                label: 'Montant total des paiements',
                data: paymentData,
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: '#4e73df',
                borderWidth: 2,
                pointBackgroundColor: '#4e73df',
                pointBorderColor: '#fff',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' FCFA';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
