@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm">
            <div class="flex justify-between items-center p-5">
                <h1 class="text-xl font-bold text-primary-600 flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i>Rapports et Statistiques
                </h1>
                <div>
                    <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-primary-600 text-primary-600 hover:bg-primary-600 hover:text-white rounded-md transition-colors">
                        <i class="fas fa-print mr-2"></i>Imprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <!-- Carte Étudiants -->
        <div class="bg-white rounded-xl shadow-sm hover-lift">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="rounded-full bg-primary-100 p-3 mr-4">
                        <i class="fas fa-user-graduate text-primary-600 text-xl"></i>
                    </div>
                    <div>
                        <h6 class="text-gray-500 text-xs mb-1">Étudiants</h6>
                        <h4 class="font-bold text-gray-800 text-xl">{{ \App\Models\Student::count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Carte Paiements -->
        <div class="bg-white rounded-xl shadow-sm hover-lift">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="rounded-full bg-green-100 p-3 mr-4">
                        <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h6 class="text-gray-500 text-xs mb-1">Paiements</h6>
                        <h4 class="font-bold text-gray-800 text-xl">{{ number_format(\App\Models\Payment::sum('amount'), 0, ',', ' ') }} FCFA</h4>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Carte Campus -->
        <div class="bg-white rounded-xl shadow-sm hover-lift">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="rounded-full bg-blue-100 p-3 mr-4">
                        <i class="fas fa-school text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h6 class="text-gray-500 text-xs mb-1">Campus</h6>
                        <h4 class="font-bold text-gray-800 text-xl">{{ \App\Models\Campus::count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Carte Filières -->
        <div class="bg-white rounded-xl shadow-sm hover-lift">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="rounded-full bg-yellow-100 p-3 mr-4">
                        <i class="fas fa-graduation-cap text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <h6 class="text-gray-500 text-xs mb-1">Filières</h6>
                        <h4 class="font-bold text-gray-800 text-xl">{{ \App\Models\Field::count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Rapports sur les paiements -->
        <div class="bg-white rounded-xl shadow-sm hover-lift h-full">
            <div class="border-b border-gray-100 p-5">
                <h5 class="font-bold text-primary-600 flex items-center">
                    <i class="fas fa-money-bill-wave mr-2"></i>Rapports de Paiements
                </h5>
            </div>
            <div class="p-5">
                <p class="text-gray-500 mb-6">
                    Visualisez et analysez toutes les transactions financières, suivez le taux de recouvrement et identifiez les tendances de paiement.
                </p>
                <div class="space-y-4">
                    <a href="{{ route('reports.payments') }}" class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                        <div class="flex items-center">
                            <div class="rounded-full bg-primary-100 p-2 mr-3 group-hover:bg-primary-200 transition-colors">
                                <i class="fas fa-chart-line text-primary-600"></i>
                            </div>
                            <div>
                                <h6 class="font-medium mb-1">Rapport global des paiements</h6>
                                <p class="text-gray-500 text-sm">Aperçu complet de tous les paiements reçus</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-primary-600 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>
                    <a href="{{ route('payments.export-excel') }}" class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                        <div class="flex items-center">
                            <div class="rounded-full bg-green-100 p-2 mr-3 group-hover:bg-green-200 transition-colors">
                                <i class="fas fa-file-excel text-green-600"></i>
                            </div>
                            <div>
                                <h6 class="font-medium mb-1">Exporter les paiements (Excel)</h6>
                                <p class="text-gray-500 text-sm">Télécharger les données de paiement au format Excel</p>
                            </div>
                        </div>
                        <i class="fas fa-download text-green-600 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>
                    <a href="{{ route('payments.print-list') }}" target="_blank" class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                        <div class="flex items-center">
                            <div class="rounded-full bg-gray-100 p-2 mr-3 group-hover:bg-gray-200 transition-colors">
                                <i class="fas fa-print text-gray-600"></i>
                            </div>
                            <div>
                                <h6 class="font-medium mb-1">Imprimer la liste des paiements</h6>
                                <p class="text-gray-500 text-sm">Version imprimable de tous les paiements</p>
                            </div>
                        </div>
                        <i class="fas fa-external-link-alt text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Rapports sur les étudiants -->
        <div class="bg-white rounded-xl shadow-sm hover-lift h-full">
            <div class="border-b border-gray-100 p-5">
                <h5 class="font-bold text-primary-600 flex items-center">
                    <i class="fas fa-user-graduate mr-2"></i>Rapports d'Étudiants
                </h5>
            </div>
            <div class="p-5">
                <p class="text-gray-500 mb-6">
                    Obtenez des informations détaillées sur les étudiants, leur progression dans les paiements et leurs informations démographiques.
                </p>
                <div class="space-y-4">
                    <a href="{{ route('reports.students') }}" class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                        <div class="flex items-center">
                            <div class="rounded-full bg-primary-100 p-2 mr-3 group-hover:bg-primary-200 transition-colors">
                                <i class="fas fa-users text-primary-600"></i>
                            </div>
                            <div>
                                <h6 class="font-medium mb-1">Rapport global des étudiants</h6>
                                <p class="text-gray-500 text-sm">Statistiques sur tous les étudiants inscrits</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-primary-600 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>
                    <a href="#" class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                        <div class="flex items-center">
                            <div class="rounded-full bg-yellow-100 p-2 mr-3 group-hover:bg-yellow-200 transition-colors">
                                <i class="fas fa-tags text-yellow-600"></i>
                            </div>
                            <div>
                                <h6 class="font-medium mb-1">Étudiants par statut de paiement</h6>
                                <p class="text-gray-500 text-sm">Liste des étudiants classés par statut de paiement</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-primary-600 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Taux de recouvrement -->
        <div class="bg-white rounded-xl shadow-sm hover-lift h-full">
            <div class="border-b border-gray-100 p-5">
                <h5 class="font-bold text-primary-600 flex items-center">
                    <i class="fas fa-chart-pie mr-2"></i>Taux de Recouvrement
                </h5>
            </div>
            <div class="p-5">
                <div class="flex flex-col items-center mb-6">
                    <div class="relative mb-4">
                        <div class="w-48 h-48">
                            <canvas id="recoveryRateChart"></canvas>
                        </div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <h3 class="font-bold text-xl">{{ number_format(\App\Models\Payment::sum('amount'), 0, ',', ' ') }}</h3>
                            <p class="text-sm text-gray-500">FCFA perçus</p>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="rounded-full bg-primary-600 p-2 mr-3">
                                <i class="fas fa-money-check-alt text-white"></i>
                            </div>
                            <div>
                                <h6 class="text-gray-500 text-xs mb-1">Total attendu</h6>
                                <h5 class="font-bold">{{ number_format(\App\Models\Field::join('students', 'fields.id', '=', 'students.field_id')->sum('fields.fees'), 0, ',', ' ') }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="rounded-full bg-red-500 p-2 mr-3">
                                <i class="fas fa-exclamation-circle text-white"></i>
                            </div>
                            <div>
                                <h6 class="text-gray-500 text-xs mb-1">Reste à percevoir</h6>
                                <h5 class="font-bold">{{ number_format(\App\Models\Field::join('students', 'fields.id', '=', 'students.field_id')->sum('fields.fees') - \App\Models\Payment::sum('amount'), 0, ',', ' ') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rapports sur les filières -->
        <div class="bg-white rounded-xl shadow-sm hover-lift h-full">
            <div class="border-b border-gray-100 p-5">
                <h5 class="font-bold text-primary-600 flex items-center">
                    <i class="fas fa-graduation-cap mr-2"></i>Rapports de Filières
                </h5>
            </div>
            <div class="p-5">
                <p class="text-gray-500 mb-6">
                    Analysez les performances de chaque filière en termes d'inscriptions, de paiements et de taux de recouvrement.
                </p>
                <div class="space-y-4">
                    @forelse(\App\Models\Field::limit(5)->get() as $field)
                    <a href="{{ route('fields.report', $field) }}" class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                        <div class="flex items-center">
                            <div class="rounded-full bg-primary-100 p-2 mr-3 group-hover:bg-primary-200 transition-colors">
                                <span class="font-bold text-primary-600">{{ substr($field->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h6 class="font-medium mb-1">{{ $field->name }}</h6>
                                <p class="text-gray-500 text-sm">{{ $field->campus->name }}</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-primary-600 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>
                    @empty
                    <div class="py-12 flex flex-col items-center">
                        <i class="fas fa-graduation-cap text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-500">Aucune filière trouvée</p>
                    </div>
                    @endforelse
                    
                    @if(\App\Models\Field::count() > 5)
                    <div class="text-center mt-6">
                        <a href="{{ route('fields.index') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors">
                            <i class="fas fa-list mr-2"></i>Voir toutes les filières
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Taux de recouvrement
    const totalExpected = {{ \App\Models\Field::join('students', 'fields.id', '=', 'students.field_id')->sum('fields.fees') ?: 1 }};
    const totalPaid = {{ \App\Models\Payment::sum('amount') ?: 0 }};
    const recoveryRate = totalExpected > 0 ? (totalPaid / totalExpected) * 100 : 0;
    
    // Graphique circulaire du taux de recouvrement
    const recoveryRateChart = new Chart(
        document.getElementById('recoveryRateChart'),
        {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [recoveryRate, 100 - recoveryRate],
                    backgroundColor: [
                        'rgba(26, 86, 219, 0.8)',
                        'rgba(242, 242, 242, 0.5)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '75%',
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
@endsection 