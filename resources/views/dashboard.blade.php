<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        /* Assurer une meilleure lisibilité des textes sur fond coloré */
        .text-on-primary {
            color: white !important;
        }
        /* Style pour la progress bar */
        .progress-thin {
            height: 8px;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h3 fw-bold text-primary-custom mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>Tableau de Bord
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques générales -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-primary-custom">
                            <i class="fas fa-user-graduate fa-2x"></i>
                        </div>
                        <div class="bg-primary-custom bg-opacity-10 rounded-circle p-2">
                            <div class="fw-bold text-white">{{ isset($studentsCount) ? $studentsCount : 0 }}</div>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold">Étudiants</h5>
                    <p class="card-text text-muted">Total d'étudiants inscrits</p>
                </div>
                <div class="card-footer bg-transparent border-0 py-3">
                    <a href="{{ route('students.index') }}" class="text-decoration-none stretched-link text-primary-custom fw-medium">
                        <i class="fas fa-arrow-right me-1"></i> Voir tous les étudiants
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-success">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-2">
                            <div class="fw-bold text-white">{{ isset($paymentsCount) ? $paymentsCount : 0 }}</div>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold">Paiements</h5>
                    <p class="card-text text-muted">Total des paiements reçus</p>
                </div>
                <div class="card-footer bg-transparent border-0 py-3">
                    <a href="{{ route('payments.index') }}" class="text-decoration-none stretched-link text-success fw-medium">
                        <i class="fas fa-arrow-right me-1"></i> Gérer les paiements
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-info">
                            <i class="fas fa-school fa-2x"></i>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-2">
                            <div class="fw-bold text-white">{{ isset($campusesCount) ? $campusesCount : 0 }}</div>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold">Campus</h5>
                    <p class="card-text text-muted">Nombre de campus gérés</p>
                </div>
                <div class="card-footer bg-transparent border-0 py-3">
                    <a href="{{ route('campuses.index') }}" class="text-decoration-none stretched-link text-info fw-medium">
                        <i class="fas fa-arrow-right me-1"></i> Voir les campus
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-warning">
                            <i class="fas fa-graduation-cap fa-2x"></i>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-2">
                            <div class="fw-bold text-white">{{ isset($fieldsCount) ? $fieldsCount : 0 }}</div>
                        </div>
                    </div>
                    <h5 class="card-title fw-bold">Filières</h5>
                    <p class="card-text text-muted">Total des filières proposées</p>
                </div>
                <div class="card-footer bg-transparent border-0 py-3">
                    <a href="{{ route('fields.index') }}" class="text-decoration-none stretched-link text-warning fw-medium">
                        <i class="fas fa-arrow-right me-1"></i> Gérer les filières
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques de recouvrement -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-pie me-2 text-primary-custom"></i>État des paiements
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="chart-container" style="height: 200px;">
                                <canvas id="paymentStatusChart"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="fw-bold">Payé intégralement</h6>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>{{ $paymentStatus['fully_paid'] }} étudiants</span>
                                    <span>{{ $totalStudents > 0 ? number_format(($paymentStatus['fully_paid'] / $totalStudents) * 100, 1) : 0 }}%</span>
                                </div>
                                <div class="progress progress-thin">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $totalStudents > 0 ? ($paymentStatus['fully_paid'] / $totalStudents) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <h6 class="fw-bold">Partiellement payé</h6>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>{{ $paymentStatus['partial_paid'] }} étudiants</span>
                                    <span>{{ $totalStudents > 0 ? number_format(($paymentStatus['partial_paid'] / $totalStudents) * 100, 1) : 0 }}%</span>
                                </div>
                                <div class="progress progress-thin">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $totalStudents > 0 ? ($paymentStatus['partial_paid'] / $totalStudents) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Aucun paiement</h6>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>{{ $paymentStatus['no_payment'] }} étudiants</span>
                                    <span>{{ $totalStudents > 0 ? number_format(($paymentStatus['no_payment'] / $totalStudents) * 100, 1) : 0 }}%</span>
                                </div>
                                <div class="progress progress-thin">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $totalStudents > 0 ? ($paymentStatus['no_payment'] / $totalStudents) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-percentage me-2 text-primary-custom"></i>Taux de recouvrement
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-center">
                            <div class="position-relative d-inline-block">
                                <canvas id="recoveryRateChart" width="200" height="200"></canvas>
                                <div class="position-absolute top-50 start-50 translate-middle text-center">
                                    <h2 class="mb-0 fw-bold">{{ number_format($recoveryRate, 1) }}%</h2>
                                    <p class="mb-0 small">Recouvrement</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 fw-bold">Montant total attendu</h6>
                                    <span class="badge bg-primary-custom text-on-primary">{{ number_format($totalExpectedFees, 0, ',', ' ') }} FCFA</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 fw-bold">Montant recouvré</h6>
                                    <span class="badge bg-success text-on-primary">{{ number_format($totalPayments, 0, ',', ' ') }} FCFA</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold">Reste à recouvrer</h6>
                                    <span class="badge bg-danger text-on-primary">{{ number_format($outstandingFees, 0, ',', ' ') }} FCFA</span>
                                </div>
                            </div>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $recoveryRate }}%" aria-valuenow="{{ $recoveryRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Derniers paiements -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-money-check-alt me-2 text-primary-custom"></i>Derniers paiements
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col">#Référence</th>
                                    <th scope="col">Étudiant</th>
                                    <th scope="col">Montant</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">État</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($recentPayments) && count($recentPayments) > 0)
                                    @foreach($recentPayments as $payment)
                                    <tr>
                                        <th scope="row">{{ $payment->reference_no }}</th>
                                        <td>{{ $payment->student->fullName }}</td>
                                        <td>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                                        <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge bg-success text-on-primary">Confirmé</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Aucun paiement récent trouvé</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 text-end py-3">
                    <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-primary">
                        Voir tous les paiements <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Activités récentes -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-history me-2 text-primary-custom"></i>Activités récentes
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @if(isset($recentActivities) && count($recentActivities) > 0)
                            @foreach($recentActivities as $activity)
                            <div class="list-group-item border-0 py-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary-custom bg-opacity-10 text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas {{ $activity->icon }}"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-1 fw-medium">{{ $activity->message }}</p>
                                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="list-group-item border-0 py-4 text-center">
                                Aucune activité récente trouvée
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et statistiques -->
    <div class="row g-4 mt-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-bar me-2 text-primary-custom"></i>Répartition des étudiants par filière
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="studentsByFieldChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-line me-2 text-primary-custom"></i>Évolution des paiements
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="paymentsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script pour les graphiques -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Graphique de répartition des étudiants par filière
        var ctxFields = document.getElementById('studentsByFieldChart').getContext('2d');
        var studentsByFieldChart = new Chart(ctxFields, {
            type: 'doughnut',
            data: {
                labels: ['Informatique', 'Gestion', 'Droit', 'Communication', 'Médecine'],
                datasets: [{
                    data: [45, 25, 20, 15, 30],
                    backgroundColor: [
                        '#0d47a1', // Bleu présidentiel
                        '#1976d2',
                        '#2196f3',
                        '#42a5f5',
                        '#90caf9'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Graphique d'évolution des paiements
        var ctxPayments = document.getElementById('paymentsChart').getContext('2d');
        var paymentsChart = new Chart(ctxPayments, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mars', 'Avr', 'Mai', 'Juin'],
                datasets: [{
                    label: 'Paiements (en millions FCFA)',
                    data: [12, 19, 15, 17, 22, 25],
                    backgroundColor: 'rgba(13, 71, 161, 0.1)',
                    borderColor: '#0d47a1',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Nouveau graphique pour le statut des paiements
        var ctxPaymentStatus = document.getElementById('paymentStatusChart').getContext('2d');
        var paymentStatusChart = new Chart(ctxPaymentStatus, {
            type: 'doughnut',
            data: {
                labels: ['Payé intégralement', 'Partiellement payé', 'Aucun paiement'],
                datasets: [{
                    data: [
                        {{ $paymentStatus['fully_paid'] }},
                        {{ $paymentStatus['partial_paid'] }},
                        {{ $paymentStatus['no_payment'] }}
                    ],
                    backgroundColor: [
                        '#28a745', // vert pour payé intégralement
                        '#ffc107', // jaune pour partiellement payé
                        '#dc3545'  // rouge pour aucun paiement
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '70%'
            }
        });

        // Graphique pour le taux de recouvrement
        var ctxRecoveryRate = document.getElementById('recoveryRateChart').getContext('2d');
        var recoveryRateChart = new Chart(ctxRecoveryRate, {
            type: 'doughnut',
            data: {
                labels: ['Recouvré', 'Restant'],
                datasets: [{
                    data: [
                        {{ $recoveryRate }},
                        {{ 100 - $recoveryRate }}
                    ],
                    backgroundColor: [
                        '#28a745', // vert pour recouvré
                        '#dc3545'  // rouge pour restant
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                cutout: '80%'
            }
        });
    });
</script>
@endsection

