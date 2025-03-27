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
    <div class="w-full">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-primary-600 text-white rounded-lg shadow-md p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h6 class="text-sm font-medium mb-2">Total Étudiants</h6>
                        <h2 class="text-2xl font-bold">{{ $totalStudents }}</h2>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-secondary-600 text-white rounded-lg shadow-md p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h6 class="text-sm font-medium mb-2">Total Paiements</h6>
                        <h2 class="text-2xl font-bold">{{ number_format($totalPayments, 0, ',', ' ') }} FCFA</h2>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-amber-500 text-white rounded-lg shadow-md p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h6 class="text-sm font-medium mb-2">Frais non payés</h6>
                        <h2 class="text-2xl font-bold">{{ number_format($outstandingFees, 0, ',', ' ') }} FCFA</h2>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>

            <div class="bg-accent text-white rounded-lg shadow-md p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h6 class="text-sm font-medium mb-2">Taux de recouvrement</h6>
                        <h2 class="text-2xl font-bold">{{ $recoveryRate }}%</h2>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-6">
            <div class="md:col-span-4">
                <div class="bg-white rounded-lg shadow-md h-full">
                    <div class="border-b border-gray-200 px-4 py-3">
                        <h5 class="font-medium text-gray-700">État des paiements</h5>
                    </div>
                    <div class="p-4">
                        <div class="chart-container">
                            <canvas id="paymentStatusChart"></canvas>
                        </div>
                        <div class="mt-4 space-y-2">
                            <div class="flex justify-between border-b border-gray-200 py-2">
                                <span class="text-gray-600">En règle</span>
                                <strong>{{ $paymentStatus['fully_paid'] }} étudiants</strong>
                            </div>
                            <div class="flex justify-between border-b border-gray-200 py-2">
                                <span class="text-gray-600">Paiement partiel</span>
                                <strong>{{ $paymentStatus['partial_paid'] }} étudiants</strong>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600">Sans paiement</span>
                                <strong>{{ $paymentStatus['no_payment'] }} étudiants</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-8">
                <div class="bg-white rounded-lg shadow-md h-full">
                    <div class="border-b border-gray-200 px-4 py-3">
                        <h5 class="font-medium text-gray-700">Évolution mensuelle des paiements</h5>
                    </div>
                    <div class="p-4">
                        <div class="chart-container">
                            <canvas id="monthlyPaymentsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-6">
            <div class="md:col-span-4">
                <div class="bg-white rounded-lg shadow-md h-full">
                    <div class="border-b border-gray-200 px-4 py-3">
                        <h5 class="font-medium text-gray-700">Statistiques du jour</h5>
                    </div>
                    <div class="p-4 space-y-2">
                        <div class="flex justify-between border-b border-gray-200 py-2">
                            <span class="text-gray-600">Paiements aujourd'hui</span>
                            <strong>{{ number_format($todayStats['payments'], 0, ',', ' ') }} FCFA</strong>
                        </div>
                        <div class="flex justify-between border-b border-gray-200 py-2">
                            <span class="text-gray-600">Nouveaux étudiants</span>
                            <strong>{{ $todayStats['new_students'] }}</strong>
                        </div>
                        <div class="flex justify-between border-b border-gray-200 py-2">
                            <span class="text-gray-600">Nombre de paiements</span>
                            <strong>{{ $todayStats['payment_count'] }}</strong>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600">Moyenne des paiements</span>
                            <strong>{{ number_format($todayStats['average_payment'], 0, ',', ' ') }} FCFA</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-8">
                <div class="bg-white rounded-lg shadow-md h-full">
                    <div class="border-b border-gray-200 px-4 py-3 flex justify-between items-center">
                        <h5 class="font-medium text-gray-700">Paiements récents</h5>
                        <a href="{{ route('payments.index') }}" class="px-3 py-1 bg-primary-600 text-white text-sm rounded hover:bg-primary-700 transition">Voir tout</a>
                    </div>
                    <div class="p-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Étudiant</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Filière</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentPayments as $payment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 whitespace-nowrap">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $payment->student->fullName }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $payment->student->field->name }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ Str::limit($payment->description, 30) }}</td>
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md h-full">
                <div class="border-b border-gray-200 px-4 py-3">
                    <h5 class="font-medium text-gray-700">Paiements par campus</h5>
                </div>
                <div class="p-4">
                    <div class="chart-container">
                        <canvas id="campusPaymentsChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md h-full">
                <div class="border-b border-gray-200 px-4 py-3">
                    <h5 class="font-medium text-gray-700">Filières les plus populaires</h5>
                </div>
                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Filière</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Étudiants</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pourcentage</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($popularFields as $field)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $field->name }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $field->students_count }}</td>
                                        <td class="px-4 py-2">
                                            <div class="flex items-center">
                                                <span class="mr-2">{{ round(($field->students_count / $totalStudents) * 100, 1) }}%</span>
                                                <div class="flex-grow bg-gray-200 rounded-full h-1.5">
                                                    <div class="bg-primary-500 h-1.5 rounded-full" style="width: {{ ($field->students_count / $totalStudents) * 100 }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 text-center text-gray-500">Aucune filière trouvée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                        backgroundColor: ['#22c55e', '#84cc16', '#ef4444']
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
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, 0.1)',
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
                        backgroundColor: 'rgba(22, 163, 74, 0.8)'
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

