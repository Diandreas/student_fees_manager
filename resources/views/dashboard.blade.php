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
<div class="container mx-auto px-4">
    <div class="mb-6">
        <div class="card">
            <div class="card-header">
                <h1 class="text-2xl font-bold text-primary-600">
                    <i class="fas fa-tachometer-alt mr-2"></i>Tableau de Bord
                </h1>
            </div>
        </div>
    </div>

    <!-- Statistiques générales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Étudiants -->
        <div class="card hover-lift">
            <div class="card-body">
                <div class="flex justify-between items-center mb-3">
                    <div class="text-primary-600">
                        <i class="fas fa-user-graduate text-3xl"></i>
                    </div>
                    <div class="bg-primary-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                        <div class="font-bold text-primary-600">{{ isset($studentsCount) ? $studentsCount : 0 }}</div>
                    </div>
                </div>
                <h5 class="text-lg font-bold mb-1">Étudiants</h5>
                <p class="text-gray-500 mb-3">Total d'étudiants inscrits</p>
                <a href="{{ route('students.index') }}" class="text-primary-600 font-medium hover:underline inline-flex items-center">
                    <i class="fas fa-arrow-right mr-1"></i> Voir tous les étudiants
                </a>
            </div>
        </div>

        <!-- Paiements -->
        <div class="card hover-lift">
            <div class="card-body">
                <div class="flex justify-between items-center mb-3">
                    <div class="text-success">
                        <i class="fas fa-money-bill-wave text-3xl"></i>
                    </div>
                    <div class="bg-green-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                        <div class="font-bold text-green-600">{{ isset($paymentsCount) ? $paymentsCount : 0 }}</div>
                    </div>
                </div>
                <h5 class="text-lg font-bold mb-1">Paiements</h5>
                <p class="text-gray-500 mb-3">Total des paiements reçus</p>
                <a href="{{ route('payments.index') }}" class="text-green-600 font-medium hover:underline inline-flex items-center">
                    <i class="fas fa-arrow-right mr-1"></i> Gérer les paiements
                </a>
            </div>
        </div>

        <!-- Campus -->
        <div class="card hover-lift">
            <div class="card-body">
                <div class="flex justify-between items-center mb-3">
                    <div class="text-blue-500">
                        <i class="fas fa-school text-3xl"></i>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                        <div class="font-bold text-blue-600">{{ isset($campusesCount) ? $campusesCount : 0 }}</div>
                    </div>
                </div>
                <h5 class="text-lg font-bold mb-1">Campus</h5>
                <p class="text-gray-500 mb-3">Nombre de campus gérés</p>
                <a href="{{ route('campuses.index') }}" class="text-blue-600 font-medium hover:underline inline-flex items-center">
                    <i class="fas fa-arrow-right mr-1"></i> Voir les campus
                </a>
            </div>
        </div>

        <!-- Filières -->
        <div class="card hover-lift">
            <div class="card-body">
                <div class="flex justify-between items-center mb-3">
                    <div class="text-yellow-500">
                        <i class="fas fa-graduation-cap text-3xl"></i>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-3 w-12 h-12 flex items-center justify-center">
                        <div class="font-bold text-yellow-600">{{ isset($fieldsCount) ? $fieldsCount : 0 }}</div>
                    </div>
                </div>
                <h5 class="text-lg font-bold mb-1">Filières</h5>
                <p class="text-gray-500 mb-3">Total des filières proposées</p>
                <a href="{{ route('fields.index') }}" class="text-yellow-600 font-medium hover:underline inline-flex items-center">
                    <i class="fas fa-arrow-right mr-1"></i> Gérer les filières
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques de recouvrement -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- État des paiements -->
        <div class="card">
            <div class="card-header">
                <h5 class="font-bold flex items-center">
                    <i class="fas fa-chart-pie mr-2 text-primary-600"></i>État des paiements
                </h5>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="chart-container" style="height: 200px;">
                            <canvas id="paymentStatusChart"></canvas>
                        </div>
                    </div>
                    <div>
                        <div class="mb-4">
                            <h6 class="font-bold mb-2">Payé intégralement</h6>
                            <div class="flex justify-between mb-1">
                                <span>{{ $paymentStatus['fully_paid'] }} étudiants</span>
                                <span>{{ $totalStudents > 0 ? number_format(($paymentStatus['fully_paid'] / $totalStudents) * 100, 1) : 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $totalStudents > 0 ? ($paymentStatus['fully_paid'] / $totalStudents) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h6 class="font-bold mb-2">Partiellement payé</h6>
                            <div class="flex justify-between mb-1">
                                <span>{{ $paymentStatus['partial_paid'] }} étudiants</span>
                                <span>{{ $totalStudents > 0 ? number_format(($paymentStatus['partial_paid'] / $totalStudents) * 100, 1) : 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $totalStudents > 0 ? ($paymentStatus['partial_paid'] / $totalStudents) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <h6 class="font-bold mb-2">Aucun paiement</h6>
                            <div class="flex justify-between mb-1">
                                <span>{{ $paymentStatus['no_payment'] }} étudiants</span>
                                <span>{{ $totalStudents > 0 ? number_format(($paymentStatus['no_payment'] / $totalStudents) * 100, 1) : 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: {{ $totalStudents > 0 ? ($paymentStatus['no_payment'] / $totalStudents) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Taux de recouvrement -->
        <div class="card">
            <div class="card-header">
                <h5 class="font-bold flex items-center">
                    <i class="fas fa-percentage mr-2 text-primary-600"></i>Taux de recouvrement
                </h5>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    <div class="text-center">
                        <div class="relative inline-block">
                            <canvas id="recoveryRateChart" width="200" height="200"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <h2 class="text-2xl font-bold mb-0">{{ number_format($recoveryRate, 1) }}%</h2>
                                <p class="text-sm">Recouvrement</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <h6 class="font-bold">Montant total attendu</h6>
                                    <span class="badge bg-primary-100 text-primary-800">{{ number_format($totalExpectedFees, 0, ',', ' ') }} FCFA</span>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <h6 class="font-bold">Montant recouvré</h6>
                                    <span class="badge bg-green-100 text-green-800">{{ number_format($totalPayments, 0, ',', ' ') }} FCFA</span>
                                </div>
                                <div class="flex justify-between items-center mb-3">
                                    <h6 class="font-bold">Reste à recouvrer</h6>
                                    <span class="badge bg-red-100 text-red-800">{{ number_format($outstandingFees, 0, ',', ' ') }} FCFA</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-5">
                                <div class="bg-green-500 h-5 rounded-full" style="width: {{ $recoveryRate }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Derniers paiements et activité récente -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Derniers paiements -->
        <div class="lg:col-span-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="font-bold flex items-center">
                        <i class="fas fa-money-check-alt mr-2 text-primary-600"></i>Derniers paiements
                    </h5>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#Référence</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Étudiant</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">État</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if(isset($recentPayments) && count($recentPayments) > 0)
                                @foreach($recentPayments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $payment->reference_no }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $payment->student->fullName }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $payment->payment_date->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Confirmé</span>
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
                <div class="card-footer bg-transparent border-0 text-end py-3">
                    <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-primary">
                        Voir tous les paiements <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Activités récentes -->
        <div class="lg:col-span-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="font-bold flex items-center">
                        <i class="fas fa-history mr-2 text-primary-600"></i>Activités récentes
                    </h5>
                </div>
                <div class="overflow-x-auto">
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

