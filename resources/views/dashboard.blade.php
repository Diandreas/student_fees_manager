<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
    </style>
@endpush

@section('content')
<div class="container">
    @if($currentSchool)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>École actuelle: {{ $currentSchool->name }}</span>
                    <div>
                        <a href="{{ route('schools.show', $currentSchool) }}" class="btn btn-sm btn-light">Voir détails</a>
                        <a href="{{ route('schools.index') }}" class="btn btn-sm btn-light ml-2">Changer d'école</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 text-center">
                            @if($currentSchool->logo)
                                <img src="{{ asset('storage/' . $currentSchool->logo) }}" alt="{{ $currentSchool->name }}" class="img-fluid" style="max-height: 80px;">
                            @else
                                <div class="display-4 text-muted font-weight-bold">{{ substr($currentSchool->name, 0, 1) }}</div>
                            @endif
                        </div>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="mb-1 text-muted">Email de contact:</p>
                                    <p class="font-weight-bold">{{ $currentSchool->contact_email }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1 text-muted">Téléphone:</p>
                                    <p class="font-weight-bold">{{ $currentSchool->contact_phone ?? 'Non renseigné' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1 text-muted">Couleurs:</p>
                                    <div class="d-flex">
                                        <div class="mr-2" style="width: 20px; height: 20px; background-color: {{ $currentSchool->primary_color }}; border-radius: 4px;"></div>
                                        <div style="width: 20px; height: 20px; background-color: {{ $currentSchool->secondary_color }}; border-radius: 4px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="display-4">{{ $totalStudents }}</h3>
                    <p class="mb-0">Étudiants</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="display-4">{{ $totalFields }}</h3>
                    <p class="mb-0">Filières</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="display-4">{{ $totalCampuses }}</h3>
                    <p class="mb-0">Campus</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3 class="display-4">{{ number_format($totalPayments, 0, ',', ' ') }}</h3>
                    <p class="mb-0">Total des paiements ({{ DashboardController::CURRENCY_SYMBOL }})</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Statistiques des paiements
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p>Taux de recouvrement: <span class="font-weight-bold">{{ $recoveryRate }}%</span></p>
                            <p>Frais impayés: <span class="font-weight-bold">{{ number_format($outstandingFees, 0, ',', ' ') }} {{ DashboardController::CURRENCY_SYMBOL }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p>Payé intégralement: <span class="font-weight-bold">{{ $paymentStatus['fully_paid'] }} étudiants</span></p>
                            <p>Payé partiellement: <span class="font-weight-bold">{{ $paymentStatus['partial_paid'] }} étudiants</span></p>
                            <p>Aucun paiement: <span class="font-weight-bold">{{ $paymentStatus['no_payment'] }} étudiants</span></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <canvas id="paymentStatusChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Évolution mensuelle des paiements
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Paiements par campus
                </div>
                <div class="card-body">
                    <canvas id="campusChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Filières les plus populaires
                </div>
                <div class="card-body">
                    @if($popularFields->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Filière</th>
                                        <th>Campus</th>
                                        <th class="text-right">Étudiants</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($popularFields as $field)
                                    <tr>
                                        <td>{{ $field->name }}</td>
                                        <td>{{ $field->campus->name }}</td>
                                        <td class="text-right">{{ $field->students_count }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted my-4">Aucune filière trouvée</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Paiements récents
                </div>
                <div class="card-body">
                    @if($recentPayments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Étudiant</th>
                                        <th>Filière</th>
                                        <th>Montant</th>
                                        <th>Date</th>
                                        <th>Mode</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->student->full_name }}</td>
                                        <td>{{ $payment->student->field->name }}</td>
                                        <td>{{ number_format($payment->amount, 0, ',', ' ') }} {{ DashboardController::CURRENCY_SYMBOL }}</td>
                                        <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                        <td>{{ $payment->payment_method }}</td>
                                        <td>
                                            <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-info mr-1">Voir</a>
                                            <a href="{{ route('payments.print', $payment) }}" class="btn btn-sm btn-secondary" target="_blank">Imprimer</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted my-4">Aucun paiement récent</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique statut de paiement
    var ctxPayment = document.getElementById('paymentStatusChart').getContext('2d');
    var paymentStatusChart = new Chart(ctxPayment, {
        type: 'pie',
        data: {
            labels: {!! json_encode($chartData['paymentStatus']['labels']) !!},
            datasets: [{
                data: {!! json_encode($chartData['paymentStatus']['data']) !!},
                backgroundColor: [
                    'rgba(52, 152, 219, 0.8)',
                    'rgba(243, 156, 18, 0.8)',
                    'rgba(231, 76, 60, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            legend: {
                position: 'bottom'
            }
        }
    });

    // Graphique mensuel
    var ctxMonthly = document.getElementById('monthlyChart').getContext('2d');
    var monthlyChart = new Chart(ctxMonthly, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['monthly']['labels']) !!},
            datasets: [{
                label: 'Montant des paiements',
                data: {!! json_encode($chartData['monthly']['data']) !!},
                backgroundColor: 'rgba(26, 188, 156, 0.2)',
                borderColor: 'rgba(26, 188, 156, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(26, 188, 156, 1)',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + " FCFA";
                        }
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        return data.datasets[tooltipItem.datasetIndex].label + ': ' + 
                            tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + ' FCFA';
                    }
                }
            }
        }
    });

    // Graphique par campus
    var ctxCampus = document.getElementById('campusChart').getContext('2d');
    var campusChart = new Chart(ctxCampus, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['campusData']['labels']) !!},
            datasets: [{
                label: 'Montant des paiements',
                data: {!! json_encode($chartData['campusData']['data']) !!},
                backgroundColor: 'rgba(155, 89, 182, 0.6)',
                borderColor: 'rgba(155, 89, 182, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + " FCFA";
                        }
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        return data.datasets[tooltipItem.datasetIndex].label + ': ' + 
                            tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + ' FCFA';
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection

