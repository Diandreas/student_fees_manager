@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 flex flex-col md:flex-row justify-between items-center gap-4">
                <h1 class="text-xl font-bold text-primary-600 flex items-center">
                    <i class="fas fa-school mr-2"></i> Performance par Campus
                </h1>
                <div class="flex space-x-2">
                    <a href="{{ route('reports.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Retour aux rapports
                    </a>
                    <a href="{{ route('reports.pdf.performance') }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                        <i class="fas fa-file-pdf mr-2"></i> Exporter en PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique comparatif -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="border-b border-gray-100 p-5">
            <h5 class="font-bold text-primary-600 flex items-center">
                <i class="fas fa-chart-bar mr-2"></i> Comparaison des Campus
            </h5>
        </div>
        <div class="p-5">
            <canvas id="campusComparisonChart" height="100"></canvas>
        </div>
    </div>

    <!-- Tableau détaillé des campus -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="border-b border-gray-100 p-5">
            <h5 class="font-bold text-primary-600 flex items-center">
                <i class="fas fa-table mr-2"></i> Détails par Campus
            </h5>
        </div>
        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campus</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Étudiants</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Frais attendus</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Montant payé</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Reste à payer</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Taux de recouvrement</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $totalStudents = 0;
                            $totalFees = 0;
                            $totalPaid = 0;
                            $totalRemaining = 0;
                        @endphp
                        
                        @foreach($campusData as $data)
                            @php
                                $totalStudents += $data['studentCount'];
                                $totalFees += $data['totalFees'];
                                $totalPaid += $data['totalPaid'];
                                $totalRemaining += ($data['totalFees'] - $data['totalPaid']);
                                
                                // Déterminer la classe pour le taux de recouvrement
                                $rateClass = '';
                                if ($data['recoveryRate'] >= 80) {
                                    $rateClass = 'bg-green-100 text-green-800';
                                } elseif ($data['recoveryRate'] >= 50) {
                                    $rateClass = 'bg-yellow-100 text-yellow-800';
                                } else {
                                    $rateClass = 'bg-red-100 text-red-800';
                                }
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-800">{{ $data['campus']->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $data['campus']->address ?? 'Adresse non définie' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-gray-700">{{ number_format($data['studentCount'], 0, ',', ' ') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-gray-700">{{ number_format($data['totalFees'], 0, ',', ' ') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-gray-700">{{ number_format($data['totalPaid'], 0, ',', ' ') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-gray-700">{{ number_format($data['totalFees'] - $data['totalPaid'], 0, ',', ' ') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $rateClass }}">
                                        {{ number_format($data['recoveryRate'], 1, ',', ' ') }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a href="{{ route('campuses.show', $data['campus']->id) }}" class="text-indigo-600 hover:text-indigo-900 mx-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        
                        @if(count($campusData) > 0)
                            <tr class="bg-gray-50 font-medium">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-800">Total</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-gray-800">{{ number_format($totalStudents, 0, ',', ' ') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-gray-800">{{ number_format($totalFees, 0, ',', ' ') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-gray-800">{{ number_format($totalPaid, 0, ',', ' ') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-gray-800">{{ number_format($totalRemaining, 0, ',', ' ') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-gray-800">
                                    @if($totalFees > 0)
                                        {{ number_format(($totalPaid / $totalFees) * 100, 1, ',', ' ') }}%
                                    @else
                                        0,0%
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center"></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Cartes de performance individuelle -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        @foreach($campusData as $data)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="border-b border-gray-100 p-4">
                    <h5 class="font-bold text-gray-800">{{ $data['campus']->name }}</h5>
                </div>
                <div class="p-4">
                    <div class="flex mb-4">
                        <div class="w-1/2 border-r border-gray-200 pr-3">
                            <p class="text-sm text-gray-500 mb-1">Étudiants</p>
                            <p class="text-lg font-bold text-gray-800">{{ number_format($data['studentCount'], 0, ',', ' ') }}</p>
                        </div>
                        <div class="w-1/2 pl-3">
                            <p class="text-sm text-gray-500 mb-1">Recouvrement</p>
                            @php
                                $rateTextColor = '';
                                if ($data['recoveryRate'] >= 80) {
                                    $rateTextColor = 'text-green-600';
                                } elseif ($data['recoveryRate'] >= 50) {
                                    $rateTextColor = 'text-yellow-600';
                                } else {
                                    $rateTextColor = 'text-red-600';
                                }
                            @endphp
                            <p class="text-lg font-bold {{ $rateTextColor }}">{{ number_format($data['recoveryRate'], 1, ',', ' ') }}%</p>
                        </div>
                    </div>
                    
                    <!-- Progression des paiements -->
                    <div class="mt-3">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-medium text-gray-500">
                                {{ number_format($data['totalPaid'], 0, ',', ' ') }} / {{ number_format($data['totalFees'], 0, ',', ' ') }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full
                                {{$data['recoveryRate'] >= 80 ? 'bg-green-600' : ($data['recoveryRate'] >= 50 ? 'bg-yellow-500' : 'bg-red-600')}}" 
                                style="width: {{min($data['recoveryRate'], 100)}}%">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('campuses.show', $data['campus']->id) }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-800">
                            <span>Voir les détails</span>
                            <svg class="ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour le graphique
    const campusNames = @json($campusData->pluck('campus.name'));
    const recoveryRates = @json($campusData->pluck('recoveryRate'));
    const studentCounts = @json($campusData->pluck('studentCount'));
    const totalFees = @json($campusData->pluck('totalFees'));
    const totalPaid = @json($campusData->pluck('totalPaid'));
    
    // Créer le graphique de comparaison
    const ctx = document.getElementById('campusComparisonChart').getContext('2d');
    const campusChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: campusNames,
            datasets: [
                {
                    label: 'Frais attendus',
                    data: totalFees,
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Montant payé',
                    data: totalPaid,
                    backgroundColor: 'rgba(16, 185, 129, 0.5)',
                    borderColor: 'rgba(16, 185, 129, 1)',
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
                            return context.dataset.label + ': ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                        }
                    }
                },
                legend: {
                    position: 'top',
                }
            }
        }
    });
});
</script>
@endpush
@endsection 