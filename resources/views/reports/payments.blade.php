@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 flex flex-col sm:flex-row justify-between items-center gap-4">
                <h1 class="text-xl font-bold text-primary-600 flex items-center">
                    <i class="fas fa-money-bill-wave mr-2"></i>Rapport des Paiements
                </h1>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('payments.export') }}" class="inline-flex items-center px-3 py-2 border border-green-500 rounded-lg text-green-600 bg-white hover:bg-green-50 font-medium transition-colors duration-150">
                        <i class="fas fa-file-excel mr-1.5"></i>Exporter Excel
                    </a>
                    <a href="{{ route('reports.payments.pdf') }}" class="inline-flex items-center px-3 py-2 border border-red-500 rounded-lg text-red-600 bg-white hover:bg-red-50 font-medium transition-colors duration-150">
                        <i class="fas fa-file-pdf mr-1.5"></i>Exporter PDF
                    </a>
                    <a href="{{ route('payments.print-list') }}" class="inline-flex items-center px-3 py-2 border border-gray-500 rounded-lg text-gray-600 bg-white hover:bg-gray-50 font-medium transition-colors duration-150" target="_blank">
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
                            <i class="fas fa-money-bill-wave text-xl text-primary-600"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Total des paiements</div>
                        <div class="text-lg font-bold text-gray-800">{{ number_format($totalPayments, 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border-l-4 border-green-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-4">
                        <div class="rounded-full bg-green-100 p-3">
                            <i class="fas fa-receipt text-xl text-green-600"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Nombre de transactions</div>
                        <div class="text-lg font-bold text-gray-800">{{ number_format($paymentsCount, 0, ',', ' ') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border-l-4 border-blue-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-4">
                        <div class="rounded-full bg-blue-100 p-3">
                            <i class="fas fa-user-graduate text-xl text-blue-600"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Étudiants ayant payé</div>
                        <div class="text-lg font-bold text-gray-800">{{ number_format($studentsWithPayments, 0, ',', ' ') }} / {{ number_format($studentsCount, 0, ',', ' ') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border-l-4 border-yellow-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-4">
                        <div class="rounded-full bg-yellow-100 p-3">
                            <i class="fas fa-calculator text-xl text-yellow-600"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Moyenne par transaction</div>
                        <div class="text-lg font-bold text-gray-800">{{ $paymentsCount > 0 ? number_format($totalPayments / $paymentsCount, 0, ',', ' ') : 0 }} FCFA</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6">
        <!-- Graphique d'évolution des paiements -->
        <div class="md:col-span-8">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden h-full">
                <div class="border-b border-gray-100 p-5">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-chart-line mr-2"></i>Évolution des paiements (12 derniers mois)
                    </h5>
                </div>
                <div class="p-5">
                    <div class="h-[300px]">
                        <canvas id="paymentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Paiements par filière -->
        <div class="md:col-span-4">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden h-full">
                <div class="border-b border-gray-100 p-5">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-graduation-cap mr-2"></i>Paiements par filière
                    </h5>
                </div>
                <div class="p-5">
                    @if($paymentsByField->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Filière</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($paymentsByField as $field)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $field->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-800">{{ number_format($field->total, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="rounded-full bg-gray-100 p-4 mx-auto w-16 h-16 flex items-center justify-center mb-3">
                                <i class="fas fa-exclamation-circle text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-gray-500">Aucune donnée disponible</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Derniers paiements -->
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 p-5">
                <h5 class="font-bold text-primary-600 flex items-center">
                    <i class="fas fa-list mr-2"></i>Derniers paiements
                </h5>
            </div>
            <div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Étudiant</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Filière</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentPayments as $payment)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">{{ $payment->receipt_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $payment->payment_date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $payment->student->fullName }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $payment->student->field->name ?? 'Non assigné' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <a href="{{ route('payments.print', $payment) }}" class="inline-flex items-center px-2.5 py-1.5 border border-primary-500 rounded-lg text-primary-600 bg-white hover:bg-primary-50 text-sm font-medium transition-colors duration-150" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="rounded-full bg-gray-100 p-6 mb-4">
                                            <i class="fas fa-money-bill-wave text-5xl text-gray-400"></i>
                                        </div>
                                        <h5 class="text-lg font-bold mb-1 text-gray-800">Aucun paiement trouvé</h5>
                                        <p class="text-gray-500">Aucune transaction n'a été enregistrée</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Préparation des données pour le graphique d'évolution des paiements
        const ctx = document.getElementById('paymentChart').getContext('2d');
        
        // Création des labels pour les mois (on utilise les données du contrôleur)
        const months = [];
        const data = [];
        
        @foreach($monthlyPayments as $payment)
            months.push("{{ $payment['month'] }}");
            data.push({{ $payment['amount'] }});
        @endforeach
        
        const paymentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Montant des paiements (FCFA)',
                    data: data,
                    fill: true,
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    tension: 0.3,
                    borderWidth: 2,
                    pointBackgroundColor: '#4f46e5',
                    pointBorderColor: '#fff',
                    pointRadius: 4
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
                                return value.toLocaleString() + ' FCFA';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                let value = context.parsed.y;
                                return label + ': ' + value.toLocaleString() + ' FCFA';
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
