@extends('layouts.app')
@section('content')
<style>
    /* Styles pour le menu déroulant d'exportation */
    .export-dropdown {
        position: relative !important;
    }
    
    .export-menu {
        display: none !important;
        position: absolute !important;
        right: 0 !important;
        top: 100% !important;
        margin-top: 5px !important;
        min-width: 200px !important;
        background-color: white !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.375rem !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        z-index: 9999 !important;
    }
    
    .export-menu.visible {
        display: block !important;
    }
    
    .export-menu a {
        display: block !important;
        padding: 10px 15px !important;
        color: #4a5568 !important;
        text-decoration: none !important;
        font-size: 0.875rem !important;
    }
    
    .export-menu a:hover {
        background-color: #f7fafc !important;
    }
    
    /* Styles pour les sections de filières */
    .field-section-content {
        display: none;
    }
    
    .field-section-content.active {
        display: block;
    }
    
    .rotate-icon {
        transform: rotate(180deg);
        transition: transform 0.2s ease;
    }
</style>

<div class="container mx-auto px-4 py-6">
    <!-- En-tête avec actions -->
    <div class="mb-6">
        <div class="card">
            <div class="card-body">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">{{ $campus->name }}</h2>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('campuses.index') }}" class="px-4 py-2 border border-gray-300 rounded text-gray-700 bg-white hover:bg-gray-50 flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Retour
                        </a>
                        <a href="{{ route('campuses.edit', $campus) }}" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 flex items-center">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                        <a href="{{ route('fields.create', ['campus_id' => $campus->id]) }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 flex items-center">
                            <i class="fas fa-plus mr-2"></i> Ajouter une filière
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Statistiques générales -->
        <div class="card hover-lift">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h5 class="font-bold text-primary-700">Étudiants</h5>
                    <div class="p-2 bg-primary-100 text-primary-700 rounded-full">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold mb-1">{{ $totalStudents }}</div>
                <div class="text-gray-500 text-sm">Répartis dans {{ $totalFields }} filières</div>
            </div>
        </div>

        <!-- Statistiques de paiement -->
        <div class="card hover-lift">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h5 class="font-bold text-green-700">Paiements</h5>
                    <div class="p-2 bg-green-100 text-green-700 rounded-full">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold mb-1">{{ number_format($totalPayments, 0, ',', ' ') }} FCFA</div>
                <div class="text-gray-500 text-sm">sur {{ number_format($totalExpectedFees, 0, ',', ' ') }} FCFA attendus</div>
            </div>
        </div>

        <!-- Taux de recouvrement -->
        <div class="card hover-lift">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h5 class="font-bold text-blue-700">Taux de recouvrement</h5>
                    <div class="p-2 bg-blue-100 text-blue-700 rounded-full">
                        <i class="fas fa-percentage"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold mb-1">{{ $recoveryRate }}%</div>
                <div class="text-gray-500 text-sm">Reste à percevoir: {{ number_format($outstandingFees, 0, ',', ' ') }} FCFA</div>
            </div>
        </div>

        <!-- Statut des paiements -->
        <div class="card hover-lift">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h5 class="font-bold text-yellow-700">Statut des paiements</h5>
                    <div class="p-2 bg-yellow-100 text-yellow-700 rounded-full">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
                <div class="flex space-x-2 text-sm">
                    <div class="px-2 py-1 bg-green-100 text-green-800 rounded-full">
                        <i class="fas fa-check-circle mr-1"></i> {{ $studentsPaymentStatus['fully_paid'] }} payés
                    </div>
                    <div class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $studentsPaymentStatus['partial_paid'] }} partiels
                    </div>
                    <div class="px-2 py-1 bg-red-100 text-red-800 rounded-full">
                        <i class="fas fa-times-circle mr-1"></i> {{ $studentsPaymentStatus['no_payment'] }} impayés
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Informations du campus -->
        <div class="md:col-span-1">
            <div class="card h-full">
                <div class="card-header">
                    <h5 class="font-bold">Informations</h5>
                </div>
                <div class="card-body">
                    <div class="flex flex-col items-center mb-6">
                        <div class="w-24 h-24 rounded-full bg-primary-600 bg-opacity-80 flex items-center justify-center mb-3">
                            <span class="text-4xl text-white">{{ substr($campus->name, 0, 1) }}</span>
                        </div>
                        <h4 class="text-lg font-bold">{{ $campus->name }}</h4>
                        @if($currentSchool)
                            <p class="text-gray-500">{{ $currentSchool->name }}</p>
                        @endif
                    </div>

                    <div class="space-y-2">
                        @if($campus->description)
                            <p class="text-gray-600">{{ $campus->description }}</p>
                        @endif
                        <p><span class="font-semibold">Nombre de filières:</span> {{ $campus->fields->count() }}</p>
                        <p><span class="font-semibold">Total étudiants:</span> {{ $campus->fields->sum('students_count') }}</p>
                    </div>
                    
                    <!-- Statistiques supplémentaires par niveau -->
                    @if(isset($educationLevelStats) && $educationLevelStats->count() > 0)
                        <div class="mt-6">
                            <h5 class="font-bold mb-3">Répartition par niveau</h5>
                            <div class="space-y-2">
                                @foreach($educationLevelStats as $level)
                                    <div class="flex justify-between items-center">
                                        <span>{{ $level['name'] }}</span>
                                        <span class="px-2 py-1 bg-primary-100 text-primary-800 rounded-full text-xs">{{ $level['count'] }} étudiants</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Top filières -->
                    @if(isset($topFields) && $topFields->count() > 0)
                        <div class="mt-6">
                            <h5 class="font-bold mb-3">Top filières</h5>
                            <div class="space-y-3">
                                @foreach($topFields as $field)
                                    <div>
                                        <div class="flex justify-between items-center">
                                            <a href="{{ route('fields.show', $field) }}" class="text-primary-600 hover:underline">{{ $field->name }}</a>
                                            <span class="px-2 py-1 bg-primary-100 text-primary-800 rounded-full text-xs">{{ $field->students_count }} étudiants</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                            <div class="bg-primary-600 h-1.5 rounded-full" style="width: {{ ($field->students_count / $totalStudents) * 100 }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div class="mt-6">
                        <div class="dropdown relative">
                            <button type="button" class="w-full px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-600 transition text-sm font-medium flex items-center justify-center">
                                <i class="fas fa-file-export mr-2"></i> Exporter <i class="fas fa-chevron-down ml-2"></i>
                            </button>
                            
                            <div class="dropdown-menu absolute right-0 hidden mt-2 w-64 bg-white shadow-lg rounded-md z-10">
                                <a href="{{ route('campuses.solvable', $campus) }}" class="block px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-check-circle mr-2 text-green-500"></i> Étudiants solvables
                                </a>
                                <a href="{{ route('campuses.insolvable', $campus) }}" class="block px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-exclamation-circle mr-2 text-yellow-500"></i> Étudiants insolvables
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des filières -->
        <div class="md:col-span-2">
            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <h5 class="font-bold">Filières</h5>
                    <a href="{{ route('fields.create', ['campus_id' => $campus->id]) }}" class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">
                        <i class="fas fa-plus mr-1"></i> Nouvelle filière
                    </a>
                </div>
                <div class="card-body">
                    @if($groupedFields->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($groupedFields as $fieldName => $fields)
                                @php
                                    $fieldSlug = \Illuminate\Support\Str::slug($fieldName);
                                @endphp
                                <div class="py-3 first:pt-0 last:pb-0">
                                    <div class="field-section-header flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 cursor-pointer rounded-md mb-2" 
                                         data-target="field-section-{{ $fieldSlug }}">
                                        <div class="flex items-center">
                                            <span class="font-bold">{{ $fieldName }}</span>
                                            <span class="ml-2 px-2 py-1 text-xs rounded-full bg-primary-100 text-primary-800">{{ $fields->count() }} classe(s)</span>
                                            <span class="ml-2 px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $fields->sum('students_count') }} étudiants</span>
                                        </div>
                                        <i class="fas fa-chevron-down text-gray-500 section-icon"></i>
                                    </div>
                                    
                                    <div id="field-section-{{ $fieldSlug }}" class="field-section-content">
                                        <div class="divide-y divide-gray-100">
                                            @foreach($fields as $field)
                                                <a href="{{ route('fields.show', $field) }}" class="block p-3 hover:bg-gray-50">
                                                    <div class="flex justify-between items-center">
                                                        <div>
                                                            <h6 class="font-medium text-gray-900">{{ $field->name }}</h6>
                                                            <p class="text-sm text-gray-500">
                                                                @if($field->code)
                                                                    Code: {{ $field->code }} | 
                                                                @endif
                                                                @if(isset($field->education_level) && $field->education_level)
                                                                    Niveau: {{ $field->education_level->name }} | 
                                                                @endif
                                                                Frais: {{ number_format($field->fees, 0, ',', ' ') }} FCFA
                                                            </p>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <span class="px-2 py-1 text-xs rounded-full bg-primary-100 text-primary-800 mr-3">{{ $field->students_count }} étudiants</span>
                                                            <i class="fas fa-chevron-right text-gray-400"></i>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                            <i class="fas fa-school text-5xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 mb-4">Aucune filière n'a été créée pour ce campus.</p>
                            <a href="{{ route('fields.create', ['campus_id' => $campus->id]) }}" class="btn-primary">
                                <i class="fas fa-plus mr-2"></i> Ajouter une filière
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion du dropdown pour les exports
        const dropdownButtons = document.querySelectorAll('.dropdown button');
        
        dropdownButtons.forEach(function(dropdownButton) {
            const dropdownMenu = dropdownButton.nextElementSibling;
            
            if (dropdownButton && dropdownMenu) {
                // Afficher/masquer le dropdown au clic
                dropdownButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('hidden');
                });
                
                // Fermer le dropdown au clic à l'extérieur
                document.addEventListener('click', function() {
                    if (!dropdownMenu.classList.contains('hidden')) {
                        dropdownMenu.classList.add('hidden');
                    }
                });
                
                // Empêcher la fermeture lors du clic sur le menu lui-même
                dropdownMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
        
        // ====== Gestion des sections de filières ======
        const fieldHeaders = document.querySelectorAll('.field-section-header');
        fieldHeaders.forEach(header => {
            header.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const content = document.getElementById(targetId);
                const icon = this.querySelector('.section-icon');
                
                // Toggle de la classe active pour le contenu
                content.classList.toggle('active');
                
                // Rotation de l'icône
                icon.classList.toggle('rotate-icon');
                
                // Changement de l'icône
                if (content.classList.contains('active')) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            });
        });
    });
    </script>

@endsection