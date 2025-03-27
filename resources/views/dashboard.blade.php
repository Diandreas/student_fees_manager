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
<div class="container-fluid py-4">
    @if($currentSchool)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-lg" style="border-left: 4px solid {{ $currentSchool->theme_color }} !important;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="me-4">
                            @if($currentSchool->logo)
                                <img src="{{ asset('storage/' . $currentSchool->logo) }}" alt="{{ $currentSchool->name }}" class="img-fluid" style="max-height: 60px;">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 60px; height: 60px; background-color: {{ $currentSchool->theme_color }};">
                                    <span class="h3 mb-0">{{ substr($currentSchool->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">{{ $currentSchool->name }}</h4>
                            <p class="text-muted mb-0">{{ $currentSchool->subscription_plan == 'basic' ? 'Plan basique' : ($currentSchool->subscription_plan == 'premium' ? 'Plan premium' : 'Plan entreprise') }}</p>
                        </div>
                        <div class="ms-auto">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="{{ route('schools.show', $currentSchool) }}"><i class="fas fa-info-circle me-2"></i> Détails</a></li>
                                    @can('update', $currentSchool)
                                    <li><a class="dropdown-item" href="{{ route('schools.edit', $currentSchool) }}"><i class="fas fa-edit me-2"></i> Modifier</a></li>
                                    @endcan
                                    <li><a class="dropdown-item" href="{{ route('schools.index') }}"><i class="fas fa-exchange-alt me-2"></i> Changer d'école</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 rounded-lg">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <div class="rounded-circle bg-primary-light p-3 text-center">
                                <i class="fas fa-user-graduate text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="col-9">
                            <h5 class="mb-1 text-muted">Étudiants</h5>
                            <h2 class="mb-0 fw-bold">{{ $totalStudents }}</h2>
                        </div>
                    </div>
                </div>
                <div class="card-footer py-2 bg-light">
                    <a href="{{ route('students.index') }}" class="text-decoration-none d-flex justify-content-between align-items-center">
                        <small class="text-primary">Voir tous les étudiants</small>
                        <i class="fas fa-chevron-right small text-primary"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 rounded-lg">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <div class="rounded-circle bg-success-light p-3 text-center">
                                <i class="fas fa-money-bill-wave text-success fa-2x"></i>
                            </div>
                        </div>
                        <div class="col-9">
                            <h5 class="mb-1 text-muted">Paiements</h5>
                            <h2 class="mb-0 fw-bold">{{ number_format($totalPayments, 0, ',', ' ') }}</h2>
                        </div>
                    </div>
                </div>
                <div class="card-footer py-2 bg-light">
                    <a href="{{ route('payments.index') }}" class="text-decoration-none d-flex justify-content-between align-items-center">
                        <small class="text-success">Voir tous les paiements</small>
                        <i class="fas fa-chevron-right small text-success"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 rounded-lg">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <div class="rounded-circle bg-warning-light p-3 text-center">
                                <i class="fas fa-exclamation-triangle text-warning fa-2x"></i>
                            </div>
                        </div>
                        <div class="col-9">
                            <h5 class="mb-1 text-muted">Non-payés</h5>
                            <h2 class="mb-0 fw-bold">{{ number_format($outstandingFees, 0, ',', ' ') }}</h2>
                        </div>
                    </div>
                </div>
                <div class="card-footer py-2 bg-light">
                    <div class="text-decoration-none d-flex justify-content-between align-items-center">
                        <small class="text-warning">Taux: {{ $recoveryRate }}%</small>
                        <div class="progress" style="width: 60%; height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $recoveryRate }}%" aria-valuenow="{{ $recoveryRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 rounded-lg">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <div class="rounded-circle bg-info-light p-3 text-center">
                                <i class="fas fa-school text-info fa-2x"></i>
                            </div>
                        </div>
                        <div class="col-9">
                            <h5 class="mb-1 text-muted">Campus</h5>
                            <h2 class="mb-0 fw-bold">{{ $totalCampuses }}</h2>
                        </div>
                    </div>
                </div>
                <div class="card-footer py-2 bg-light">
                    <a href="{{ route('campuses.index') }}" class="text-decoration-none d-flex justify-content-between align-items-center">
                        <small class="text-info">Voir tous les campus</small>
                        <i class="fas fa-chevron-right small text-info"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100 rounded-lg">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold">Statistiques des paiements</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton2">
                            <li><a class="dropdown-item" href="{{ route('reports.payments') }}"><i class="fas fa-chart-line me-2"></i> Rapports détaillés</a></li>
                            <li><a class="dropdown-item" href="{{ route('reports.payments.pdf') }}"><i class="fas fa-file-pdf me-2"></i> Exporter en PDF</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted d-block">Payé intégralement</small>
                                                <span class="fw-bold">{{ $paymentStatus['fully_paid'] }} étudiants</span>
                                            </div>
                                            <div class="rounded-circle bg-success" style="width: 10px; height: 10px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted d-block">Partiellement payé</small>
                                                <span class="fw-bold">{{ $paymentStatus['partial_paid'] }} étudiants</span>
                                            </div>
                                            <div class="rounded-circle bg-warning" style="width: 10px; height: 10px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted d-block">Aucun paiement</small>
                                                <span class="fw-bold">{{ $paymentStatus['no_payment'] }} étudiants</span>
                                            </div>
                                            <div class="rounded-circle bg-danger" style="width: 10px; height: 10px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="height: 250px;">
                        <canvas id="paymentStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100 rounded-lg">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold">Évolution mensuelle des paiements</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton3">
                            <li><a class="dropdown-item" href="{{ route('reports.payments') }}"><i class="fas fa-chart-line me-2"></i> Rapports détaillés</a></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard.statistics') }}"><i class="fas fa-download me-2"></i> Télécharger les données</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold">Paiements récents</h5>
                    <a href="{{ route('payments.index') }}" class="btn btn-sm btn-primary">
                        Voir tout <i class="fas fa-chevron-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0">Étudiant</th>
                                    <th class="border-0">Filière</th>
                                    <th class="border-0">Montant</th>
                                    <th class="border-0">Date</th>
                                    <th class="border-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayments as $payment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                <span class="text-primary">{{ substr($payment->student->full_name ?? 'U', 0, 1) }}</span>
                                            </div>
                                            <div class="ms-3">
                                                <p class="fw-bold mb-0">{{ $payment->student->full_name ?? 'Inconnu' }}</p>
                                                <p class="text-muted mb-0 small">ID: {{ $payment->student->student_id ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $payment->student->field->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="fw-bold">{{ number_format($payment->amount, 0, ',', ' ') }} {{ DashboardController::CURRENCY_SYMBOL }}</span>
                                    </td>
                                    <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('payments.print', $payment) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">Aucun paiement récent</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold">Filières populaires</h5>
                    <a href="{{ route('fields.index') }}" class="btn btn-sm btn-primary">
                        Voir tout <i class="fas fa-chevron-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body">
                    @if($popularFields->count() > 0)
                        @foreach($popularFields as $field)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div>
                                    <span class="fw-bold">{{ $field->name }}</span>
                                    <small class="text-muted">{{ $field->campus->name ?? 'N/A' }}</small>
                                </div>
                                <span class="badge bg-primary">{{ $field->students_count }} étudiants</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $totalStudents > 0 ? ($field->students_count / $totalStudents) * 100 : 0 }}%;" aria-valuenow="{{ $field->students_count }}" aria-valuemin="0" aria-valuemax="{{ $totalStudents }}"></div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <p>Aucune filière trouvée</p>
                        </div>
                    @endif
                </div>

                <div class="card-footer bg-white p-0 mt-auto">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-top">
                        <h5 class="mb-0 fw-bold">Fonctionnalités</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        @if($currentSchool && $currentSchool->has_online_payments)
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-credit-card text-success me-2"></i> Paiements en ligne
                                </div>
                                <span class="badge bg-success">Activé</span>
                            </div>
                        </a>
                        @endif
                        
                        @if($currentSchool && $currentSchool->has_sms_notifications)
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-sms text-success me-2"></i> Notifications SMS
                                </div>
                                <span class="badge bg-success">Activé</span>
                            </div>
                        </a>
                        @endif
                        
                        @if($currentSchool && $currentSchool->has_parent_portal)
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-users text-success me-2"></i> Portail parents
                                </div>
                                <span class="badge bg-success">Activé</span>
                            </div>
                        </a>
                        @endif
                        
                        @if($currentSchool && ($currentSchool->subscription_plan == 'basic'))
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-rocket text-primary me-2"></i> Passer au plan premium
                                </div>
                                <i class="fas fa-chevron-right text-muted"></i>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-primary-light {
    background-color: rgba(13, 110, 253, 0.1);
}
.bg-success-light {
    background-color: rgba(25, 135, 84, 0.1);
}
.bg-warning-light {
    background-color: rgba(255, 193, 7, 0.1);
}
.bg-info-light {
    background-color: rgba(13, 202, 240, 0.1);
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique statut de paiement
    var ctxPayment = document.getElementById('paymentStatusChart').getContext('2d');
    var paymentStatusChart = new Chart(ctxPayment, {
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
                    'rgba(25, 135, 84, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            },
            cutout: '70%'
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
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(13, 110, 253, 1)',
                pointRadius: 4,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + " FCFA";
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + 
                                context.raw.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + ' FCFA';
                        }
                    }
                },
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush
@endsection

