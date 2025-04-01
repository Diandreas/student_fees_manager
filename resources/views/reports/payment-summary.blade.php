@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 flex flex-col md:flex-row justify-between items-center gap-4">
                <h1 class="text-xl font-bold text-primary-600 flex items-center">
                    <i class="fas fa-chart-line mr-2"></i> Résumé des Paiements
                </h1>
                <div class="flex space-x-2">
                    <a href="{{ route('reports.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Retour aux rapports
                    </a>
                    <a href="{{ route('reports.pdf.payments') }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                        <i class="fas fa-file-pdf mr-2"></i> Exporter en PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total des paiements</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalPayments, 0, ',', ' ') }}</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-money-bill-wave text-blue-500 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Nombre d'étudiants</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalStudents, 0, ',', ' ') }}</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-user-graduate text-green-500 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Étudiants ayant payé</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ number_format($studentsWithPayments, 0, ',', ' ') }}</h3>
                </div>
                <div class="bg-amber-100 p-3 rounded-lg">
                    <i class="fas fa-users text-amber-500 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Taux de paiement</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ number_format($paymentRate, 1, ',', ' ') }}%</h3>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i class="fas fa-percentage text-purple-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Graphique des paiements mensuels -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 p-5">
                <h5 class="font-bold text-primary-600 flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i> Évolution des paiements mensuels
                </h5>
            </div>
            <div class="p-5">
                <canvas id="monthlyPaymentsChart" height="300"></canvas>
            </div>
        </div>

        <!-- Tableau récapitulatif -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 p-5">
                <h5 class="font-bold text-primary-600 flex items-center">
                    <i class="fas fa-table mr-2"></i> Récapitulatif des paiements mensuels
                </h5>
            </div>
            <div class="p-5 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mois</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">% du total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $totalAmount = array_sum(array_column($paymentData, 0)); @endphp
                        @foreach($monthLabels as $index => $month)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $month }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ number_format($paymentData[$index] ?? 0, 0, ',', ' ') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                    @if($totalAmount > 0)
                                        {{ number_format((($paymentData[$index] ?? 0) / $totalAmount) * 100, 1, ',', ' ') }}%
                                    @else
                                        0,0%
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Total</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">{{ number_format($totalAmount, 0, ',', ' ') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Analyse des paiements -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="border-b border-gray-100 p-5">
            <h5 class="font-bold text-primary-600 flex items-center">
                <i class="fas fa-chart-pie mr-2"></i> Analyse des paiements
            </h5>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h6 class="font-semibold text-gray-700 mb-3">Répartition des étudiants par statut de paiement</h6>
                    <div class="bg-gray-50 p-5 rounded-lg">
                        <canvas id="paymentStatusChart" height="250"></canvas>
                    </div>
                </div>
                <div>
                    <h6 class="font-semibold text-gray-700 mb-3">Statistiques de recouvrement</h6>
                    <div class="space-y-4">
                        @php
                            $paidPercentage = $totalStudents > 0 ? ($studentsWithPayments / $totalStudents) * 100 : 0;
                            $unpaidPercentage = 100 - $paidPercentage;
                        @endphp
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Étudiants ayant effectué au moins un paiement</span>
                                <span class="text-sm font-medium text-gray-700">{{ number_format($paidPercentage, 1, ',', ' ') }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ min(100, $paidPercentage) }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Étudiants sans aucun paiement</span>
                                <span class="text-sm font-medium text-gray-700">{{ number_format($unpaidPercentage, 1, ',', ' ') }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-red-600 h-2.5 rounded-full" style="width: {{ min(100, $unpaidPercentage) }}%"></div>
                            </div>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg mt-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm text-blue-700">Le taux de recouvrement est calculé en divisant le montant total payé par le montant total des frais de scolarité attendus.</p>
                                </div>
                            </div>
                        </div>
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
    // Graphique des paiements mensuels
    const monthlyCtx = document.getElementById('monthlyPaymentsChart').getContext('2d');
    const monthlyPaymentsChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: @json($monthLabels),
            datasets: [{
                label: 'Montant des paiements',
                data: @json($paymentData),
                backgroundColor: 'rgba(79, 70, 229, 0.7)',
                borderColor: 'rgba(79, 70, 229, 1)',
                borderWidth: 1
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
                            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.raw;
                            return 'Montant: ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                        }
                    }
                }
            }
        }
    });

    // Graphique du statut de paiement
    const statusCtx = document.getElementById('paymentStatusChart').getContext('2d');
    const paymentStatusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Ont payé', 'N\'ont pas payé'],
            datasets: [{
                data: [{{ $studentsWithPayments }}, {{ $totalStudents - $studentsWithPayments }}],
                backgroundColor: [
                    'rgba(22, 163, 74, 0.7)',
                    'rgba(220, 38, 38, 0.7)'
                ],
                borderColor: [
                    'rgba(22, 163, 74, 1)',
                    'rgba(220, 38, 38, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.raw;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = Math.round((value / total) * 100);
                            return context.label + ': ' + value + ' (' + percentage + '%)';
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