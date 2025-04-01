<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }
        /* Assurer une meilleure lisibilité des textes sur fond coloré */
        .text-on-primary {
            color: white !important;
        }
        /* Style pour la progress bar */
        .progress-thin {
            height: 6px;
        }
        /* Style pour les cartes compactes */
        .compact-card {
            height: 100%;
        }
        .compact-card .card-body {
            padding: 1rem;
        }
        /* Style pour les activités */
        .activity-item {
            display: flex;
            align-items: flex-start;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .activity-item:last-child {
            border-bottom: none;
        }
        .activity-icon {
            margin-right: 0.75rem;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .activity-details {
            flex: 1;
        }
        .activity-user {
            font-weight: 600;
            color: #4a5568;
        }
        .activity-model {
            font-weight: 600;
            color: #2d3748;
        }
        .activity-time {
            font-size: 0.75rem;
            color: #718096;
        }
        /* Style pour la carte des rapports */
        .reports-card {
            background: linear-gradient(135deg, #4338ca, #6366f1);
            color: white;
        }
        .reports-card .report-icon {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .reports-btn {
            background-color: rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        .reports-btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }
    </style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-4">
    <div class="mb-4">
        <div class="card">
            <div class="card-header py-2">
                <h1 class="text-xl font-bold text-primary-600">
                    <i class="fas fa-tachometer-alt mr-2"></i>Tableau de Bord
                </h1>
            </div>
        </div>
    </div>

    <!-- Statistiques générales (version compacte) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
        <!-- Étudiants -->
        <div class="card compact-card hover-lift">
            <div class="card-body p-3">
                <div class="flex justify-between items-center mb-2">
                    <div class="text-primary-600">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                    <div class="bg-primary-100 rounded-full p-2 w-10 h-10 flex items-center justify-center">
                        <div class="font-bold text-primary-600 text-sm">{{ isset($studentsCount) ? $studentsCount : 0 }}</div>
                    </div>
                </div>
                <h5 class="text-base font-bold mb-0">Étudiants</h5>
                <p class="text-gray-500 text-sm mb-2">Total inscrits</p>
                <a href="{{ route('students.index') }}" class="text-primary-600 text-xs font-medium hover:underline inline-flex items-center">
                    <i class="fas fa-arrow-right mr-1"></i> Détails
                </a>
            </div>
        </div>

        <!-- Paiements -->
        <div class="card compact-card hover-lift">
            <div class="card-body p-3">
                <div class="flex justify-between items-center mb-2">
                    <div class="text-success">
                        <i class="fas fa-money-bill-wave text-2xl"></i>
                    </div>
                    <div class="bg-green-100 rounded-full p-2 w-10 h-10 flex items-center justify-center">
                        <div class="font-bold text-green-600 text-sm">{{ $paymentsCount }}</div>
                    </div>
                </div>
                <h5 class="text-base font-bold mb-0">Paiements</h5>
                <p class="text-gray-500 text-sm mb-2">{{ number_format($totalPayments, 0, ',', ' ') }} FCFA</p>
                <a href="{{ route('payments.index') }}" class="text-green-600 text-xs font-medium hover:underline inline-flex items-center">
                    <i class="fas fa-arrow-right mr-1"></i> Détails
                </a>
            </div>
        </div>

        <!-- Campus -->
        <div class="card compact-card hover-lift">
            <div class="card-body p-3">
                <div class="flex justify-between items-center mb-2">
                    <div class="text-blue-500">
                        <i class="fas fa-school text-2xl"></i>
                    </div>
                    <div class="bg-blue-100 rounded-full p-2 w-10 h-10 flex items-center justify-center">
                        <div class="font-bold text-blue-600 text-sm">{{ $totalCampuses }}</div>
                    </div>
                </div>
                <h5 class="text-base font-bold mb-0">Campus</h5>
                <p class="text-gray-500 text-sm mb-2">Total campus</p>
                <a href="{{ route('campuses.index') }}" class="text-blue-600 text-xs font-medium hover:underline inline-flex items-center">
                    <i class="fas fa-arrow-right mr-1"></i> Détails
                </a>
            </div>
        </div>

        <!-- Filières -->
        <div class="card compact-card hover-lift">
            <div class="card-body p-3">
                <div class="flex justify-between items-center mb-2">
                    <div class="text-yellow-500">
                        <i class="fas fa-graduation-cap text-2xl"></i>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-2 w-10 h-10 flex items-center justify-center">
                        <div class="font-bold text-yellow-600 text-sm">{{ $totalFields }}</div>
                    </div>
                </div>
                <h5 class="text-base font-bold mb-0">Filières</h5>
                <p class="text-gray-500 text-sm mb-2">Total filières</p>
                <a href="{{ route('fields.index') }}" class="text-yellow-600 text-xs font-medium hover:underline inline-flex items-center">
                    <i class="fas fa-arrow-right mr-1"></i> Détails
                </a>
            </div>
        </div>
    </div>

    <!-- Carte de rapports analytiques -->
    <div class="reports-card rounded-lg shadow-lg p-4 mb-4">
        <div class="flex justify-between items-center">
            <div class="flex items-start space-x-4">
                <div class="report-icon rounded-full p-3 flex items-center justify-center">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-1">Rapports analytiques</h3>
                    <p class="opacity-80 text-sm mb-0">Accédez à tous les rapports et statistiques détaillés</p>
                </div>
            </div>
            <a href="{{ route('reports.index') }}" class="reports-btn rounded-lg px-4 py-2 font-semibold flex items-center">
                <span>Voir les rapports</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <!-- Statistiques de recouvrement & Activités récentes (version compacte) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
        <!-- État des paiements -->
        <div class="card compact-card">
            <div class="card-header py-2">
                <h5 class="font-bold text-sm flex items-center">
                    <i class="fas fa-chart-pie mr-2 text-primary-600"></i>État des paiements
                </h5>
            </div>
            <div class="card-body p-3">
                <div class="chart-container" style="height: 180px;">
                            <canvas id="paymentStatusChart"></canvas>
                        </div>
                <div class="mt-3">
                    <div class="grid grid-cols-3 gap-2 text-xs">
                        <div class="flex flex-col items-center">
                            <span class="inline-block w-3 h-3 bg-green-500 rounded-full mb-1"></span>
                            <span class="font-semibold">{{ $paymentStatus['fully_paid'] }}</span>
                            <span class="text-gray-500">Payé</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <span class="inline-block w-3 h-3 bg-yellow-500 rounded-full mb-1"></span>
                            <span class="font-semibold">{{ $paymentStatus['partial_paid'] }}</span>
                            <span class="text-gray-500">Partiel</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <span class="inline-block w-3 h-3 bg-red-500 rounded-full mb-1"></span>
                            <span class="font-semibold">{{ $paymentStatus['no_payment'] }}</span>
                            <span class="text-gray-500">Impayé</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Taux de recouvrement -->
        <div class="card compact-card">
            <div class="card-header py-2">
                <h5 class="font-bold text-sm flex items-center">
                    <i class="fas fa-percentage mr-2 text-primary-600"></i>Taux de recouvrement
                </h5>
            </div>
            <div class="card-body p-3">
                <div class="text-center mb-3">
                        <div class="relative inline-block">
                        <canvas id="recoveryRateChart" width="150" height="150"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <h2 class="text-xl font-bold mb-0">{{ number_format($recoveryRate, 1) }}%</h2>
                            <p class="text-xs">Recouvrement</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold">Attendu:</span>
                        <span class="badge bg-primary-100 text-primary-800">{{ number_format($totalExpectedFees, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-semibold">Recouvré:</span>
                        <span class="badge bg-green-100 text-green-800">{{ number_format($totalPayments, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-semibold">Reste:</span>
                        <span class="badge bg-red-100 text-red-800">{{ number_format($outstandingFees, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activités récentes (version améliorée) -->
        <div class="card compact-card">
            <div class="card-header py-2">
                <h5 class="font-bold text-sm flex items-center justify-between">
                    <div>
                        <i class="fas fa-history mr-2 text-primary-600"></i>Activités récentes
                    </div>
                    <a href="{{ route('activity-logs.index') }}" class="text-xs text-primary-600 hover:underline">Voir tout</a>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="p-3 overflow-y-auto" style="max-height: 250px;">
                    @if(isset($recentActivities) && count($recentActivities) > 0)
                        @foreach($recentActivities as $activity)
                            <div class="activity-item">
                                <div class="activity-icon bg-{{ $activity->action === 'created' ? 'green' : ($activity->action === 'updated' ? 'blue' : 'red') }}-100 text-{{ $activity->action === 'created' ? 'green' : ($activity->action === 'updated' ? 'blue' : 'red') }}-600">
                                    <i class="fas fa-{{ $activity->action === 'created' ? 'plus' : ($activity->action === 'updated' ? 'edit' : 'trash') }} text-xs"></i>
                                </div>
                                <div class="activity-details">
                                    <div class="flex justify-between">
                                        <p class="text-xs mb-1">
                                            <span class="activity-user">{{ $activity->user ? $activity->user->name : 'Système' }}</span> 
                                            {{ $activity->action === 'created' ? 'a créé' : ($activity->action === 'updated' ? 'a modifié' : 'a supprimé') }} 
                                            <span class="activity-model">{{ class_basename($activity->model_type) }}</span>
                                            @if($activity->model && method_exists($activity->model, 'getName'))
                                                "{{ $activity->model->getName() }}"
                                            @elseif($activity->model && isset($activity->model->name))
                                                "{{ $activity->model->name }}"
                                            @endif
                                        </p>
                                    </div>
                                    <p class="activity-time">{{ $activity->created_at->diffForHumans() }}</p>
                                    @if($activity->action === 'updated' && !empty($activity->old_values))
                                        <div class="text-xs mt-1 text-gray-600">
                                            <span class="font-semibold">Modifications:</span> 
                                            @php
                                                $changes = [];
                                                foreach($activity->old_values as $key => $value) {
                                                    if(!in_array($key, ['updated_at', 'created_at'])) {
                                                        $changes[] = $key;
                                                    }
                                                }
                                            @endphp
                                            {{ implode(', ', $changes) }}
                                </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3 text-gray-500 text-sm">
                            Aucune activité récente trouvée
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Derniers paiements et Statistiques par campus (version compacte) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        <!-- Derniers paiements -->
        <div class="lg:col-span-8">
            <div class="card compact-card">
                <div class="card-header py-2">
                    <h5 class="font-bold text-sm flex items-center justify-between">
                        <div>
                        <i class="fas fa-money-check-alt mr-2 text-primary-600"></i>Derniers paiements
                        </div>
                        <a href="{{ route('payments.index') }}" class="text-xs text-primary-600 hover:underline">Voir tout</a>
                    </h5>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#Réf</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Étudiant</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">État</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if(isset($recentPayments) && count($recentPayments) > 0)
                                @foreach($recentPayments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 whitespace-nowrap font-medium text-xs">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                            {{ $payment->reference_no }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-xs">{{ $payment->student->fullName }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-xs">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-xs">{{ $payment->payment_date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-xs">
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Confirmé</span>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-sm">Aucun paiement récent trouvé</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Statistiques par campus -->
        <div class="lg:col-span-4">
            <div class="card compact-card">
                <div class="card-header py-2">
                    <h5 class="font-bold text-sm flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-primary-600"></i>Paiements par campus
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="chart-container" style="height: 200px;">
                        <canvas id="campusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques supplémentaires -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 mt-4">
        <!-- Top 5 des filières populaires -->
        <div class="lg:col-span-12">
            <div class="card compact-card">
                <div class="card-header py-2">
                    <h5 class="font-bold text-sm flex items-center">
                        <i class="fas fa-star mr-2 text-primary-600"></i>Top 5 des filières
                    </h5>
                </div>
                <div class="card-body p-3">
                    @php
                        $topFields = isset($popularFields) ? $popularFields->take(5) : collect([]);
                    @endphp
                    
                    @if($topFields->count() > 0)
                        <div class="space-y-3">
                            @foreach($topFields as $field)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center mr-2 text-xs font-bold">
                                            {{ $loop->iteration }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium">{{ $field->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $field->campus->name }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-semibold">{{ $field->students_count }}</span>
                                        <p class="text-xs text-gray-500">étudiants</p>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="bg-primary-600 h-1.5 rounded-full" style="width: {{ $topFields->max('students_count') > 0 ? ($field->students_count / $topFields->max('students_count') * 100) : 0 }}%"></div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-6 text-gray-500">
                            Aucune donnée disponible
                            </div>
                        @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Calendrier des échéances et alertes -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 mt-4">
        <!-- Étudiants insolvables (alertes) -->
        <div class="lg:col-span-6">
            <div class="card compact-card">
                <div class="card-header py-2">
                    <h5 class="font-bold text-sm flex items-center justify-between">
                        <div>
                            <i class="fas fa-exclamation-triangle mr-2 text-yellow-500"></i>Alertes de paiement
                        </div>
                        <a href="{{ route('students.index', ['status' => 'insolvable']) }}" class="text-xs text-primary-600 hover:underline">Voir tout</a>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Étudiant</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Filière</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reste à payer</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    // On simule des données pour les étudiants insolvables en attendant les vraies données
                                    $insolvableStudents = isset($insolvableStudents) ? $insolvableStudents : collect([]);
                                @endphp
                                
                                @if($insolvableStudents->count() > 0)
                                    @foreach($insolvableStudents->take(5) as $student)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-2 whitespace-nowrap text-xs">
                                            <a href="{{ route('students.show', $student) }}" class="text-primary-600 hover:underline font-medium">
                                                {{ $student->full_name }}
                                            </a>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs">{{ $student->field->name }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-xs">
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                                                {{ number_format($student->outstanding_fees, 0, ',', ' ') }} FCFA
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center py-3 text-sm">Aucun étudiant insolvable trouvé</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tendance des paiements (par jour du mois) -->
        <div class="lg:col-span-6">
            <div class="card compact-card">
                <div class="card-header py-2">
                    <h5 class="font-bold text-sm flex items-center">
                        <i class="fas fa-calendar-alt mr-2 text-primary-600"></i>Tendance des paiements
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="chart-container" style="height: 200px;">
                        <canvas id="paymentTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reste du code JavaScript inchangé -->
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Graphique État des paiements
        var paymentStatusChart = new Chart(
            document.getElementById('paymentStatusChart'),
            {
            type: 'doughnut',
            data: {
                    labels: {!! json_encode($chartData['paymentStatus']['labels']) !!},
                datasets: [{
                        data: {!! json_encode($chartData['paymentStatus']['data']) !!},
                        backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                    cutout: '70%',
                plugins: {
                    legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var label = context.label || '';
                                    var value = context.formattedValue || '';
                                    var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    var percentage = Math.round((context.raw / total) * 100);
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            }
        );

        // Graphique Taux de recouvrement
        var recoveryRateChart = new Chart(
            document.getElementById('recoveryRateChart'),
            {
                type: 'doughnut',
            data: {
                    labels: ['Recouvré', 'À recouvrer'],
                datasets: [{
                        data: [{{ $recoveryRate }}, {{ 100 - $recoveryRate }}],
                        backgroundColor: ['#10B981', '#F3F4F6'],
                        borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                    cutout: '80%',
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

        // Graphique Campus
        @if(isset($chartData['campusData']))
        var campusChart = new Chart(
            document.getElementById('campusChart'),
            {
                type: 'bar',
            data: {
                    labels: {!! json_encode($chartData['campusData']['labels']) !!},
                datasets: [{
                        label: 'Montant recouvré (FCFA)',
                        data: {!! json_encode($chartData['campusData']['data']) !!},
                        backgroundColor: '#60A5FA',
                        borderWidth: 0,
                        borderRadius: 4
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
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 10
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [2, 2]
                            },
                            ticks: {
                                font: {
                                    size: 10
                                },
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return (value / 1000000).toFixed(1) + 'M';
                                    } else if (value >= 1000) {
                                        return (value / 1000).toFixed(0) + 'k';
                                    }
                                    return value;
                                }
                            }
                        }
                    }
                }
            }
        );
        @endif

        // Graphique tendance des paiements (par jour du mois)
        @if(isset($chartData['paymentTrends']))
        var paymentTrendChart = new Chart(
            document.getElementById('paymentTrendChart'),
            {
                type: 'line',
            data: {
                    labels: {!! json_encode($chartData['paymentTrends']['labels']) !!},
                datasets: [{
                        label: 'Paiements reçus',
                        data: {!! json_encode($chartData['paymentTrends']['data']) !!},
                        borderColor: '#4F46E5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        fill: true,
                        tension: 0.3,
                        borderWidth: 2
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
                        x: {
                            title: {
                                display: true,
                                text: 'Jour du mois',
                                font: {
                                    size: 10
                                }
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [2, 2]
                            },
                            title: {
                                display: true,
                                text: 'Nombre de paiements',
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            }
        );
        @endif
    });
</script>
@endpush

