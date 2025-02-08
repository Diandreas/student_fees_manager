<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1rem;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }

        .progress {
            background-color: #e9ecef;
        }

        .progress-bar {
            background-color: #0d6efd;
        }

        .table th {
            font-weight: 500;
            border-top: none;
        }

        .opacity-50 {
            opacity: 0.5;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-3">Total Étudiants</h6>
                                <h2 class="mb-0">{{ $totalStudents }}</h2>
                            </div>
                            <i class="fas fa-users fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-3">Total Paiements</h6>
                                <h2 class="mb-0">{{ number_format($totalPayments, 0, ',', ' ') }} FCFA</h2>
                            </div>
                            <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card bg-warning text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-3">Frais non payés</h6>
                                <h2 class="mb-0">{{ number_format($outstandingFees, 0, ',', ' ') }} FCFA</h2>
                            </div>
                            <i class="fas fa-exclamation-triangle fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card bg-info text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-3">Taux de recouvrement</h6>
                                <h2 class="mb-0">{{ $recoveryRate }}%</h2>
                            </div>
                            <i class="fas fa-chart-line fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">État des paiements</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="paymentStatusChart"></canvas>
                        </div>
                        <div class="mt-4">
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span>En règle</span>
                                <strong>{{ $paymentStatus['fully_paid'] }} étudiants</strong>
                            </div>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span>Paiement partiel</span>
                                <strong>{{ $paymentStatus['partial_paid'] }} étudiants</strong>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span>Sans paiement</span>
                                <strong>{{ $paymentStatus['no_payment'] }} étudiants</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Évolution mensuelle des paiements</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="monthlyPaymentsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Statistiques du jour</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>Paiements aujourd'hui</span>
                            <strong>{{ number_format($todayStats['payments'], 0, ',', ' ') }} FCFA</strong>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>Nouveaux étudiants</span>
                            <strong>{{ $todayStats['new_students'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>Nombre de paiements</span>
                            <strong>{{ $todayStats['payment_count'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span>Moyenne des paiements</span>
                            <strong>{{ number_format($todayStats['average_payment'], 0, ',', ' ') }} FCFA</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Paiements récents</h5>
                        <a href="{{ route('payments.index') }}" class="btn btn-sm btn-primary">Voir tout</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Étudiant</th>
                                    <th>Filière</th>
                                    <th>Montant</th>
                                    <th>Description</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($recentPayments as $payment)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                                        <td>{{ $payment->student->fullName }}</td>
                                        <td>{{ $payment->student->field->name }}</td>
                                        <td>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                                        <td>{{ Str::limit($payment->description, 30) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Aucun paiement récent</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Paiements par campus</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="campusPaymentsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Filières les plus populaires</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Filière</th>
                                    <th>Étudiants</th>
                                    <th>Pourcentage</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($popularFields as $field)
                                    <tr>
                                        <td>{{ $field->name }}</td>
                                        <td>{{ $field->students_count }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">{{ round(($field->students_count / $totalStudents) * 100, 1) }}%</span>
                                                <div class="progress flex-grow-1" style="height: 5px;">
                                                    <div class="progress-bar" role="progressbar"
                                                         style="width: {{ ($field->students_count / $totalStudents) * 100 }}%">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Aucune filière trouvée</td>
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
@endsection

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            };

            // Graphique état des paiements
            new Chart(document.getElementById('paymentStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($chartData['paymentStatus']['labels']) !!},
                    datasets: [{
                        data: {!! json_encode($chartData['paymentStatus']['data']) !!},
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                    }]
                },
                options: {
                    ...commonOptions,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });

            // Graphique des paiements mensuels
            new Chart(document.getElementById('monthlyPaymentsChart'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData['monthly']->pluck('month')) !!},
                    datasets: [{
                        label: 'Montant total',
                        data: {!! json_encode($chartData['monthly']->pluck('total')) !!},
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    ...commonOptions,
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

            // Graphique paiements par campus
            new Chart(document.getElementById('campusPaymentsChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartData['campusData']['labels']) !!},
                    datasets: [{
                        label: 'Montant total',
                        data: {!! json_encode($chartData['campusData']['data']) !!},
                        backgroundColor: 'rgba(13, 110, 253, 0.8)'
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString() + ' FCFA';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>

