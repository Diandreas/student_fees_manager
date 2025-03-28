@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 flex flex-col sm:flex-row justify-between items-center gap-4">
                <h1 class="text-xl font-bold text-primary-600 flex items-center">
                    <i class="fas fa-user-graduate mr-2"></i>Rapport des Étudiants
                </h1>
                <div class="flex flex-wrap gap-2">
                    <a href="#" class="inline-flex items-center px-3 py-2 border border-green-500 rounded-lg text-green-600 bg-white hover:bg-green-50 font-medium transition-colors duration-150" onclick="window.print()">
                        <i class="fas fa-print mr-1.5"></i>Imprimer
                    </a>
                    <a href="{{ route('reports.index') }}" class="inline-flex items-center px-3 py-2 border border-primary-500 rounded-lg text-primary-600 bg-white hover:bg-primary-50 font-medium transition-colors duration-150">
                        <i class="fas fa-arrow-left mr-1.5"></i>Retour
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Indicateurs clés -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border-l-4 border-primary-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-4">
                        <div class="rounded-full bg-primary-100 p-3">
                            <i class="fas fa-users text-xl text-primary-600"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Total des étudiants</div>
                        <div class="text-lg font-bold text-gray-800">{{ number_format($totalStudents, 0, ',', ' ') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border-l-4 border-green-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-4">
                        <div class="rounded-full bg-green-100 p-3">
                            <i class="fas fa-check-circle text-xl text-green-600"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Payé intégralement</div>
                        <div class="text-lg font-bold text-gray-800">{{ number_format($paymentStats['fullyPaid'], 0, ',', ' ') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border-l-4 border-yellow-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-4">
                        <div class="rounded-full bg-yellow-100 p-3">
                            <i class="fas fa-exclamation-circle text-xl text-yellow-600"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Payé partiellement</div>
                        <div class="text-lg font-bold text-gray-800">{{ number_format($paymentStats['partiallyPaid'], 0, ',', ' ') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border-l-4 border-red-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-4">
                        <div class="rounded-full bg-red-100 p-3">
                            <i class="fas fa-times-circle text-xl text-red-600"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Aucun paiement</div>
                        <div class="text-lg font-bold text-gray-800">{{ number_format($paymentStats['notPaid'], 0, ',', ' ') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Graphique des statuts de paiement -->
        <div>
            <div class="bg-white rounded-xl shadow-sm overflow-hidden h-full">
                <div class="border-b border-gray-100 p-5">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-chart-pie mr-2"></i>Statuts de paiement
                    </h5>
                </div>
                <div class="p-5">
                    <div class="flex justify-center">
                        <div class="h-64 w-64">
                            <canvas id="paymentStatusChart"></canvas>
                        </div>
                    </div>
                    <div class="mt-6 space-y-3">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                                <span class="text-sm text-gray-700">Payé intégralement</span>
                            </div>
                            <span class="font-bold text-gray-800">{{ $totalStudents > 0 ? round(($paymentStats['fullyPaid'] / $totalStudents) * 100) : 0 }}%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <span class="inline-block w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
                                <span class="text-sm text-gray-700">Payé partiellement</span>
                            </div>
                            <span class="font-bold text-gray-800">{{ $totalStudents > 0 ? round(($paymentStats['partiallyPaid'] / $totalStudents) * 100) : 0 }}%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <span class="inline-block w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                                <span class="text-sm text-gray-700">Aucun paiement</span>
                            </div>
                            <span class="font-bold text-gray-800">{{ $totalStudents > 0 ? round(($paymentStats['notPaid'] / $totalStudents) * 100) : 0 }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Montants des paiements -->
        <div>
            <div class="bg-white rounded-xl shadow-sm overflow-hidden h-full">
                <div class="border-b border-gray-100 p-5">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-money-bill-wave mr-2"></i>Statut de recouvrement
                    </h5>
                </div>
                <div class="p-5">
                    <div class="text-center mb-6">
                        <div class="relative inline-block">
                            <div class="w-48 h-48 mx-auto recovery-rate-circle">
                                <canvas id="recoveryRateChart"></canvas>
                            </div>
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
                                <h3 class="text-2xl font-bold text-gray-800">{{ $recoveryRate }}%</h3>
                                <p class="text-xs text-gray-500">Recouvrement</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm text-gray-500">Total attendu</span>
                                <span class="font-bold text-gray-800">{{ number_format($paymentStats['totalFees'], 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-primary-600 h-2 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm text-gray-500">Total reçu</span>
                                <span class="font-bold text-gray-800">{{ number_format($paymentStats['totalPaid'], 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $recoveryRate }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm text-gray-500">Reste à percevoir</span>
                                <span class="font-bold text-gray-800">{{ number_format($paymentStats['totalRemaining'], 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: {{ 100 - $recoveryRate }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Répartition par campus -->
        <div>
            <div class="bg-white rounded-xl shadow-sm overflow-hidden h-full">
                <div class="border-b border-gray-100 p-5">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-university mr-2"></i>Répartition par campus
                    </h5>
                </div>
                <div class="p-5">
                    @if($studentsByCampus->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campus</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">%</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($studentsByCampus as $campus)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $campus->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-800">{{ $campus->count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-800">{{ $totalStudents > 0 ? round(($campus->count / $totalStudents) * 100) : 0 }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="rounded-full bg-gray-100 p-4 mx-auto w-16 h-16 flex items-center justify-center mb-3">
                                <i class="fas fa-university text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-gray-500">Aucun campus trouvé</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Étudiants par filière -->
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 p-5">
                <h5 class="font-bold text-primary-600 flex items-center">
                    <i class="fas fa-graduation-cap mr-2"></i>Étudiants par filière
                </h5>
            </div>
            <div class="p-5">
                @if($studentsByField->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($studentsByField as $field)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                            <div class="p-5">
                                <div class="flex justify-between items-center mb-3">
                                    <h6 class="font-bold text-gray-800 truncate">{{ $field->name }}</h6>
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                        {{ $field->students_count }} étudiants
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 mb-4">{{ $field->campus->name }}</p>
                                <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                    <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $totalStudents > 0 ? ($field->students_count / $totalStudents) * 100 : 0 }}%"></div>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-gray-500">% du total</span>
                                    <span class="text-xs font-semibold text-gray-800">{{ $totalStudents > 0 ? round(($field->students_count / $totalStudents) * 100, 1) : 0 }}%</span>
                                </div>
                            </div>
                            <div class="px-5 py-4 bg-gray-50 border-t border-gray-100">
                                <a href="{{ route('fields.show', $field->id) }}" class="inline-flex w-full items-center justify-center px-4 py-2 border border-primary-500 rounded-lg text-primary-600 bg-white hover:bg-primary-50 font-medium transition-colors duration-150">
                                    <i class="fas fa-eye mr-1.5"></i> Voir les détails
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="rounded-full bg-gray-100 p-6 mx-auto w-20 h-20 flex items-center justify-center mb-4">
                            <i class="fas fa-graduation-cap text-gray-400 text-3xl"></i>
                        </div>
                        <h5 class="text-lg font-bold mb-1 text-gray-800">Aucune filière trouvée</h5>
                        <p class="text-gray-500">Vous n'avez pas encore créé de filières dans le système</p>
                    </div>
                @endif
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
                    '#22c55e', // green-500
                    '#eab308', // yellow-500
                    '#ef4444'  // red-500
                ],
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
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    padding: 10,
                    cornerRadius: 6,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    }
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
                        '#22c55e', // green-500
                        '#f3f4f6'  // gray-100
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '80%',
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
}
</style>
@endpush
@endsection 