@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm">
            <div class="flex justify-between items-center p-5">
                <h1 class="text-xl font-bold text-primary-600 flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i>Rapports et Statistiques
                </h1>
                <div class="flex space-x-2">
                    <a href="{{ route('statistics.index') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors">
                        <i class="fas fa-chart-line mr-2"></i>Statistiques avancées
                    </a>
                    <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-primary-600 text-primary-600 hover:bg-primary-600 hover:text-white rounded-md transition-colors">
                        <i class="fas fa-print mr-2"></i>Imprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    @php
        $school = session('current_school');
        
        // Obtenir les campus de l'école actuelle
        $campusIds = $school->campuses()->pluck('id')->toArray();
        
        // Obtenir les filières de ces campus
        $fieldIds = \App\Models\Field::whereIn('campus_id', $campusIds)->pluck('id')->toArray();
        
        // Obtenir les étudiants associés à ces filières
        $studentIds = \App\Models\Student::whereIn('field_id', $fieldIds)->pluck('id')->toArray();
        
        // Statistiques filtrées par école
        $studentsCount = count($studentIds);
        $totalPayments = \App\Models\Payment::whereIn('student_id', $studentIds)->sum('amount');
        $campusCount = count($campusIds);
        $fieldsCount = count($fieldIds);
        
        // Calcul des frais totaux et du reste à percevoir
        $totalFees = \App\Models\Field::whereIn('fields.id', $fieldIds)
            ->join('students', 'fields.id', '=', 'students.field_id')
            ->sum('fields.fees');
        
        $remainingAmount = max(0, $totalFees - $totalPayments);
        
        // Taux de recouvrement en pourcentage
        $recoveryRate = $totalFees > 0 ? round(($totalPayments / $totalFees) * 100) : 0;
        
        // Calcul des effectifs par statut de paiement 
        $fullyPaidCount = 0;
        $partiallyPaidCount = 0;
        $unpaidCount = 0;
        
        $students = \App\Models\Student::whereIn('field_id', $fieldIds)->get();
        
        foreach ($students as $student) {
            $field = \App\Models\Field::find($student->field_id);
            $totalFee = $field ? $field->fees : 0;
            $paid = \App\Models\Payment::where('student_id', $student->id)->sum('amount');
            
            if ($paid >= $totalFee && $totalFee > 0) {
                $fullyPaidCount++;
            } elseif ($paid > 0) {
                $partiallyPaidCount++;
            } else {
                $unpaidCount++;
            }
        }
        
        // Obtenir les données pour les graphiques
        $paymentsByMonth = \App\Models\Payment::whereIn('student_id', $studentIds)
            ->selectRaw("strftime('%m', payment_date) as month, SUM(amount) as total")
            ->whereRaw("strftime('%Y', payment_date) = ?", [date('Y')])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->toArray();
            
        $monthLabels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        $paymentsByMonthData = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $paymentsByMonthData[] = isset($paymentsByMonth[$i]) ? $paymentsByMonth[$i]['total'] : 0;
        }
        
        // Top 5 filières par effectif
        $topFields = \App\Models\Field::whereIn('id', $fieldIds)
            ->withCount('students')
            ->orderByDesc('students_count')
            ->limit(5)
            ->get();
    @endphp

    <!-- Vue d'ensemble -->
    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Vue d'ensemble</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <!-- Carte Étudiants -->
            <div class="bg-white rounded-xl shadow-sm hover-lift">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="rounded-full bg-primary-100 p-3 mr-4">
                            <i class="fas fa-user-graduate text-primary-600 text-xl"></i>
                        </div>
                        <div>
                            <h6 class="text-gray-500 text-xs mb-1">Étudiants</h6>
                            <h4 class="font-bold text-gray-800 text-xl">{{ $studentsCount }}</h4>
                            <div class="flex flex-wrap gap-1 mt-1">
                                <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs">
                                    <i class="fas fa-check-circle mr-1"></i>{{ $fullyPaidCount }} payés
                                </span>
                                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $partiallyPaidCount }} partiels
                                </span>
                                <span class="px-2 py-0.5 bg-red-100 text-red-800 rounded-full text-xs">
                                    <i class="fas fa-times-circle mr-1"></i>{{ $unpaidCount }} impayés
                                </span>
                            </div>
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
                            <h4 class="font-bold text-gray-800 text-xl">{{ number_format($totalPayments, 0, ',', ' ') }} FCFA</h4>
                            <p class="text-xs text-gray-500 mt-1">
                                Reste: {{ number_format($remainingAmount, 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Carte Taux de recouvrement -->
            <div class="bg-white rounded-xl shadow-sm hover-lift">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="rounded-full bg-blue-100 p-3 mr-4">
                            <i class="fas fa-percentage text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h6 class="text-gray-500 text-xs mb-1">Taux de recouvrement</h6>
                            <h4 class="font-bold text-gray-800 text-xl">{{ $recoveryRate }}%</h4>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $recoveryRate }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Carte Structure -->
            <div class="bg-white rounded-xl shadow-sm hover-lift">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="rounded-full bg-yellow-100 p-3 mr-4">
                            <i class="fas fa-sitemap text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <h6 class="text-gray-500 text-xs mb-1">Structure</h6>
                            <div class="grid grid-cols-2 gap-1 mt-1">
                                <div>
                                    <span class="flex items-center text-gray-600">
                                        <i class="fas fa-school mr-1 text-xs"></i>
                                        <span class="font-semibold">{{ $campusCount }}</span>
                                    </span>
                                    <span class="text-xs text-gray-500">Campus</span>
                                </div>
                                <div>
                                    <span class="flex items-center text-gray-600">
                                        <i class="fas fa-graduation-cap mr-1 text-xs"></i>
                                        <span class="font-semibold">{{ $fieldsCount }}</span>
                                    </span>
                                    <span class="text-xs text-gray-500">Filières</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
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
                    <a href="{{ route('reports.payment-summary') }}" class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                        <div class="flex items-center">
                            <div class="rounded-full bg-purple-100 p-2 mr-3 group-hover:bg-purple-200 transition-colors">
                                <i class="fas fa-file-alt text-purple-600"></i>
                            </div>
                            <div>
                                <h6 class="font-medium mb-1">Synthèse des paiements</h6>
                                <p class="text-gray-500 text-sm">Rapport synthétique par période et filière</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-primary-600 opacity-0 group-hover:opacity-100 transition-opacity"></i>
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
                    <a href="{{ route('students.index', ['payment_status' => 'not_paid']) }}" class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-lg transition-colors group">
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
                    <a href="{{ route('reports.student-distribution') }}" class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                        <div class="flex items-center">
                            <div class="rounded-full bg-blue-100 p-2 mr-3 group-hover:bg-blue-200 transition-colors">
                                <i class="fas fa-chart-pie text-blue-600"></i>
                            </div>
                            <div>
                                <h6 class="font-medium mb-1">Répartition des étudiants</h6>
                                <p class="text-gray-500 text-sm">Distribution par campus, filière et niveau</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-primary-600 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>
                    <a href="{{ route('students.export-excel') }}" class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                        <div class="flex items-center">
                            <div class="rounded-full bg-green-100 p-2 mr-3 group-hover:bg-green-200 transition-colors">
                                <i class="fas fa-file-excel text-green-600"></i>
                            </div>
                            <div>
                                <h6 class="font-medium mb-1">Exporter les étudiants (Excel)</h6>
                                <p class="text-gray-500 text-sm">Télécharger la liste complète des étudiants</p>
                            </div>
                        </div>
                        <i class="fas fa-download text-green-600 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Tendance des paiements -->
        <div class="bg-white rounded-xl shadow-sm hover-lift h-full">
            <div class="border-b border-gray-100 p-5">
                <h5 class="font-bold text-primary-600 flex items-center">
                    <i class="fas fa-chart-line mr-2"></i>Tendance des paiements ({{ date('Y') }})
                </h5>
            </div>
            <div class="p-5">
                <div class="h-64">
                    <canvas id="paymentsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top 5 filières par effectif -->
        <div class="bg-white rounded-xl shadow-sm hover-lift h-full">
            <div class="border-b border-gray-100 p-5">
                <h5 class="font-bold text-primary-600 flex items-center">
                    <i class="fas fa-graduation-cap mr-2"></i>Top 5 des filières
                </h5>
            </div>
            <div class="p-5">
                <div class="space-y-4">
                    @foreach ($topFields as $field)
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium">{{ $field->name }}</span>
                            <span class="text-sm text-gray-600">{{ $field->students_count }} étudiants</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-primary-600 h-2 rounded-full" style="width: {{ ($field->students_count / $studentsCount) * 100 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Rapports institutionnels -->
    <div class="bg-white rounded-xl shadow-sm hover-lift mb-6">
        <div class="border-b border-gray-100 p-5">
            <h5 class="font-bold text-primary-600 flex items-center">
                <i class="fas fa-building mr-2"></i>Rapports institutionnels
            </h5>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('reports.campus-performance') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center mb-2">
                        <div class="rounded-full bg-primary-100 p-2 mr-3">
                            <i class="fas fa-school text-primary-600"></i>
                        </div>
                        <h6 class="font-medium">Performance des campus</h6>
                    </div>
                    <p class="text-gray-500 text-sm">Analyse comparative des performances entre campus</p>
                </a>
                
                <a href="{{ route('reports.field-analysis') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center mb-2">
                        <div class="rounded-full bg-green-100 p-2 mr-3">
                            <i class="fas fa-graduation-cap text-green-600"></i>
                        </div>
                        <h6 class="font-medium">Analyse des filières</h6>
                    </div>
                    <p class="text-gray-500 text-sm">Indicateurs de performance par filière</p>
                </a>
                
                <a href="{{ route('reports.annual') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center mb-2">
                        <div class="rounded-full bg-blue-100 p-2 mr-3">
                            <i class="fas fa-calendar-alt text-blue-600"></i>
                        </div>
                        <h6 class="font-medium">Rapport annuel</h6>
                    </div>
                    <p class="text-gray-500 text-sm">Bilan complet de l'année académique</p>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Graphique des tendances de paiement
        const ctx = document.getElementById('paymentsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($monthLabels),
                datasets: [{
                    label: 'Paiements mensuels (FCFA)',
                    data: @json($paymentsByMonthData),
                    backgroundColor: 'rgba(79, 70, 229, 0.2)',
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
                                let value = context.parsed.y;
                                return 'Paiements: ' + value.toLocaleString() + ' FCFA';
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