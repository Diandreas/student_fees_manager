@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">
                    <i class="fas fa-chart-line mr-2 text-primary-600"></i>{{ __('Statistiques pluriannuelles') }}
                </h1>
                <div class="flex space-x-4">
                    <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                        <i class="fas fa-file-alt mr-2"></i>
                        Voir les rapports
                    </a>
                    <a href="{{ route('statistics.compare') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Comparer les années
                    </a>
                </div>
            </div>
            
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if($yearlyStats->count() > 0)
                <!-- Résumé des tendances -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Tendance des étudiants -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <h3 class="text-sm font-medium text-gray-500 uppercase mb-1">Total Étudiants</h3>
                            @php
                                $lastYearStat = $yearlyStats->first();
                                $previousYearStat = $yearlyStats->skip(1)->first();
                                $studentGrowth = 0;
                                
                                if ($previousYearStat && $previousYearStat->total_students > 0) {
                                    $studentGrowth = (($lastYearStat->total_students - $previousYearStat->total_students) / $previousYearStat->total_students) * 100;
                                }
                            @endphp
                            <div class="flex items-center">
                                <h2 class="text-2xl font-bold text-gray-900">{{ $lastYearStat->total_students }}</h2>
                                <span class="ml-2 px-2 py-0.5 rounded-full text-xs {{ $studentGrowth >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas {{ $studentGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>{{ number_format(abs($studentGrowth), 1) }}%
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Taux de recouvrement -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <h3 class="text-sm font-medium text-gray-500 uppercase mb-1">Taux de recouvrement</h3>
                            @php
                                $recoveryRateGrowth = 0;
                                
                                if ($previousYearStat && $previousYearStat->recovery_rate > 0) {
                                    $recoveryRateGrowth = $lastYearStat->recovery_rate - $previousYearStat->recovery_rate;
                                }
                            @endphp
                            <div class="flex items-center">
                                <h2 class="text-2xl font-bold text-gray-900">{{ number_format($lastYearStat->recovery_rate, 1) }}%</h2>
                                <span class="ml-2 px-2 py-0.5 rounded-full text-xs {{ $recoveryRateGrowth >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas {{ $recoveryRateGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>{{ number_format(abs($recoveryRateGrowth), 1) }}%
                                </span>
                            </div>
                            <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                                <div class="bg-primary-600 h-1.5 rounded-full" style="width: {{ min(100, $lastYearStat->recovery_rate) }}%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total payé -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <h3 class="text-sm font-medium text-gray-500 uppercase mb-1">Paiements (Dernière année)</h3>
                            @php
                                $paymentGrowth = 0;
                                
                                if ($previousYearStat && $previousYearStat->total_paid > 0) {
                                    $paymentGrowth = (($lastYearStat->total_paid - $previousYearStat->total_paid) / $previousYearStat->total_paid) * 100;
                                }
                            @endphp
                            <div class="flex items-center">
                                <h2 class="text-2xl font-bold text-gray-900">{{ number_format($lastYearStat->total_paid / 1000000, 1) }}M</h2>
                                <span class="ml-2 px-2 py-0.5 rounded-full text-xs {{ $paymentGrowth >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas {{ $paymentGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>{{ number_format(abs($paymentGrowth), 1) }}%
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ number_format($lastYearStat->total_paid, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                    
                    <!-- Nouveaux étudiants -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <h3 class="text-sm font-medium text-gray-500 uppercase mb-1">Nouveaux étudiants</h3>
                            @php
                                $newStudentGrowth = 0;
                                
                                if ($previousYearStat && $previousYearStat->new_students > 0) {
                                    $newStudentGrowth = (($lastYearStat->new_students - $previousYearStat->new_students) / $previousYearStat->new_students) * 100;
                                }
                            @endphp
                            <div class="flex items-center">
                                <h2 class="text-2xl font-bold text-gray-900">{{ $lastYearStat->new_students }}</h2>
                                <span class="ml-2 px-2 py-0.5 rounded-full text-xs {{ $newStudentGrowth >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas {{ $newStudentGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>{{ number_format(abs($newStudentGrowth), 1) }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Graphiques pluriannuels -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Graphique des paiements par année -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-money-bill-wave mr-2 text-primary-600"></i>Évolution des paiements et facturations
                            </h3>
                            <div class="relative h-80">
                                <canvas id="paymentsChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Graphique des taux de recouvrement par année -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-percentage mr-2 text-primary-600"></i>Évolution du taux de recouvrement
                            </h3>
                            <div class="relative h-80">
                                <canvas id="recoveryRateChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Graphiques comparatifs supplémentaires -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Évolution des effectifs étudiants -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-user-graduate mr-2 text-primary-600"></i>Évolution des effectifs étudiants
                            </h3>
                            <div class="relative h-80">
                                <canvas id="studentsChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Distribution nouveaux vs anciens étudiants -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-users mr-2 text-primary-600"></i>Tendance des inscriptions
                            </h3>
                            <div class="relative h-80">
                                <canvas id="newStudentsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des années académiques avec leurs statistiques -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-archive mr-2 text-primary-600"></i>Archives des années académiques
                        </h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Année académique
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Étudiants
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nouveaux inscrits
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total facturé
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total payé
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Taux de recouvrement
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($yearlyStats as $stat)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $stat->academic_year }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $stat->total_students }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $stat->new_students }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($stat->total_invoiced, 0, ',', ' ') }} FCFA
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($stat->total_paid, 0, ',', ' ') }} FCFA
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class="flex items-center">
                                                    <span class="mr-2">{{ number_format($stat->recovery_rate, 1) }}%</span>
                                                    <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                                        <div class="h-2.5 rounded-full {{ $stat->recovery_rate >= 90 ? 'bg-green-500' : ($stat->recovery_rate >= 70 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ min(100, $stat->recovery_rate) }}%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex justify-end space-x-2">
                                                    <a href="{{ route('statistics.year', $stat->academic_year) }}" class="text-primary-600 hover:text-primary-900" title="Voir les détails">
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    @if($archives->where('academic_year', $stat->academic_year)->first())
                                                        <a href="{{ route('archives.download', $archives->where('academic_year', $stat->academic_year)->first()) }}" class="text-primary-600 hover:text-primary-900" title="Télécharger l'archive">
                                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                            </svg>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune statistique disponible</h3>
                            <p class="mt-1 text-sm text-gray-500">Générez d'abord une archive de fin d'année pour voir apparaître des statistiques ici.</p>
                            <div class="mt-6">
                                <a href="{{ route('archives.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Générer une archive
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($yearlyStats->count() > 0)
    @push('styles')
        <style>
            canvas {
                max-height: 100%;
                width: 100% !important;
            }
        </style>
    @endpush
    
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const years = @json($chartData['years']);
                
                // Graphique des paiements et facturations
                const paymentsCtx = document.getElementById('paymentsChart').getContext('2d');
                new Chart(paymentsCtx, {
                    type: 'bar',
                    data: {
                        labels: years,
                        datasets: [
                            {
                                label: 'Total facturé',
                                data: @json($chartData['totalInvoiced']),
                                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Total payé',
                                data: @json($chartData['totalPaid']),
                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
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
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let value = context.raw;
                                        return context.dataset.label + ': ' + value.toLocaleString() + ' FCFA';
                                    }
                                }
                            }
                        }
                    }
                });
                
                // Graphique du taux de recouvrement
                const recoveryRateCtx = document.getElementById('recoveryRateChart').getContext('2d');
                new Chart(recoveryRateCtx, {
                    type: 'line',
                    data: {
                        labels: years,
                        datasets: [
                            {
                                label: 'Taux de recouvrement (%)',
                                data: @json($chartData['recoveryRates']),
                                fill: false,
                                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 2,
                                tension: 0.1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                min: 0,
                                max: 100,
                                ticks: {
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Taux de recouvrement: ' + context.raw.toFixed(1) + '%';
                                    }
                                }
                            }
                        }
                    }
                });
                
                // Graphique des effectifs étudiants
                const studentsCtx = document.getElementById('studentsChart').getContext('2d');
                new Chart(studentsCtx, {
                    type: 'line',
                    data: {
                        labels: years,
                        datasets: [
                            {
                                label: 'Nombre total d\'étudiants',
                                data: @json($yearlyStats->map(function($stat) { return $stat->total_students; })->reverse()->values()),
                                fill: true,
                                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                borderColor: 'rgba(79, 70, 229, 1)',
                                borderWidth: 2,
                                tension: 0.3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
                
                // Graphique nouveaux vs anciens étudiants
                const newStudentsCtx = document.getElementById('newStudentsChart').getContext('2d');
                new Chart(newStudentsCtx, {
                    type: 'bar',
                    data: {
                        labels: years,
                        datasets: [
                            {
                                label: 'Nouveaux étudiants',
                                data: @json($yearlyStats->map(function($stat) { return $stat->new_students; })->reverse()->values()),
                                backgroundColor: 'rgba(16, 185, 129, 0.5)',
                                borderColor: 'rgba(16, 185, 129, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Anciens étudiants',
                                data: @json($yearlyStats->map(function($stat) { return $stat->total_students - $stat->new_students; })->reverse()->values()),
                                backgroundColor: 'rgba(245, 158, 11, 0.5)',
                                borderColor: 'rgba(245, 158, 11, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                stacked: false,
                                ticks: {
                                    precision: 0
                                }
                            },
                            x: {
                                stacked: false
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.raw;
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
    @endif
@endsection