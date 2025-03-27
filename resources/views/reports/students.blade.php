@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <h1 class="h3 fw-bold text-primary-custom mb-0">
                        <i class="fas fa-user-graduate me-2"></i>Rapport des Étudiants
                    </h1>
                    <div>
                        <a href="#" class="btn btn-outline-success me-2" onclick="window.print()">
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
                                <i class="fas fa-users fa-2x text-primary-custom"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-muted small mb-1">Total des étudiants</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($totalStudents, 0, ',', ' ') }}</div>
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
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-muted small mb-1">Payé intégralement</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($paymentStats['fullyPaid'], 0, ',', ' ') }}</div>
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
                                <i class="fas fa-exclamation-circle fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-muted small mb-1">Payé partiellement</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($paymentStats['partiallyPaid'], 0, ',', ' ') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto me-3">
                            <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                                <i class="fas fa-times-circle fa-2x text-danger"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-muted small mb-1">Aucun paiement</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($paymentStats['notPaid'], 0, ',', ' ') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <!-- Graphique des statuts de paiement -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-bold text-primary-custom">
                        <i class="fas fa-chart-pie me-2"></i>Statuts de paiement
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-center">
                        <div style="height: 250px; width: 250px;">
                            <canvas id="paymentStatusChart"></canvas>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="d-flex align-items-center">
                                <span class="badge bg-success rounded-circle p-2 me-2">&nbsp;</span>
                                Payé intégralement
                            </span>
                            <span class="fw-bold">{{ $totalStudents > 0 ? round(($paymentStats['fullyPaid'] / $totalStudents) * 100) : 0 }}%</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="d-flex align-items-center">
                                <span class="badge bg-warning rounded-circle p-2 me-2">&nbsp;</span>
                                Payé partiellement
                            </span>
                            <span class="fw-bold">{{ $totalStudents > 0 ? round(($paymentStats['partiallyPaid'] / $totalStudents) * 100) : 0 }}%</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="d-flex align-items-center">
                                <span class="badge bg-danger rounded-circle p-2 me-2">&nbsp;</span>
                                Aucun paiement
                            </span>
                            <span class="fw-bold">{{ $totalStudents > 0 ? round(($paymentStats['notPaid'] / $totalStudents) * 100) : 0 }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Montants des paiements -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-bold text-primary-custom">
                        <i class="fas fa-money-bill-wave me-2"></i>Statut de recouvrement
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <div class="recovery-rate-circle">
                                <canvas id="recoveryRateChart" width="200" height="200"></canvas>
                            </div>
                            <div class="position-absolute top-50 start-50 translate-middle text-center">
                                <h3 class="mb-0 fw-bold">{{ $recoveryRate }}%</h3>
                                <p class="mb-0 small text-muted">Recouvrement</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Total attendu</span>
                                <span class="fw-bold">{{ number_format($paymentStats['totalFees'], 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" style="width: 100%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Total reçu</span>
                                <span class="fw-bold">{{ number_format($paymentStats['totalPaid'], 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: {{ $recoveryRate }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Reste à percevoir</span>
                                <span class="fw-bold">{{ number_format($paymentStats['totalRemaining'], 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" style="width: {{ 100 - $recoveryRate }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Répartition par campus -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-bold text-primary-custom">
                        <i class="fas fa-university me-2"></i>Répartition par campus
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($studentsByCampus->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Campus</th>
                                        <th class="text-end">Nombre</th>
                                        <th class="text-end">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentsByCampus as $campus)
                                    <tr>
                                        <td>{{ $campus->name }}</td>
                                        <td class="text-end">{{ $campus->count }}</td>
                                        <td class="text-end">{{ $totalStudents > 0 ? round(($campus->count / $totalStudents) * 100) : 0 }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-university fa-3x text-muted mb-3"></i>
                            <p>Aucun campus trouvé</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Étudiants par filière -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-bold text-primary-custom">
                        <i class="fas fa-graduation-cap me-2"></i>Étudiants par filière
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($studentsByField->count() > 0)
                        <div class="row">
                            @foreach($studentsByField as $field)
                            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0 fw-bold">{{ $field->name }}</h6>
                                            <span class="badge bg-primary-custom bg-opacity-10 text-white py-2 px-3">
                                                {{ $field->students_count }} étudiants
                                            </span>
                                        </div>
                                        <p class="text-muted small mb-3">{{ $field->campus->name }}</p>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-primary" style="width: {{ $totalStudents > 0 ? ($field->students_count / $totalStudents) * 100 : 0 }}%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <small class="text-muted">% du total</small>
                                            <small class="fw-bold">{{ $totalStudents > 0 ? round(($field->students_count / $totalStudents) * 100, 1) : 0 }}%</small>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent p-4 border-0">
                                        <a href="{{ route('fields.show', $field->id) }}" class="btn btn-sm btn-outline-primary-custom w-100">
                                            <i class="fas fa-eye me-1"></i> Voir les détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                            <p>Aucune filière trouvée</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique des statuts de paiement
    const ctx = document.getElementById('paymentStatusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Payé intégralement', 'Payé partiellement', 'Aucun paiement'],
            datasets: [{
                data: [
                    {{ $paymentStats['fullyPaid'] }}, 
                    {{ $paymentStats['partiallyPaid'] }}, 
                    {{ $paymentStats['notPaid'] }}
                ],
                backgroundColor: [
                    '#28a745',
                    '#ffc107',
                    '#dc3545'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    
    // Graphique circulaire du taux de recouvrement
    const recoveryRateChart = new Chart(
        document.getElementById('recoveryRateChart'),
        {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [{{ $recoveryRate }}, {{ 100 - $recoveryRate }}],
                    backgroundColor: [
                        '#4CAF50',
                        '#f2f2f2'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '75%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: false
                    }
                }
            }
        }
    );
});
</script>
@endpush

@push('styles')
<style>
.recovery-rate-circle {
    position: relative;
    width: 200px;
    height: 200px;
}
</style>
@endpush
@endsection 