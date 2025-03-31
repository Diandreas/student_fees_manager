@extends('layouts.app')

@section('content')
<style>
    /* Style pour les menus déroulants */
    .dropdown-menu {
        position: absolute;
        right: 0;
        margin-top: 0.5rem;
        width: 16rem;
        background-color: white;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border-radius: 0.375rem;
        z-index: 50;
        display: none;
    }
    
    .dropdown-menu.active {
        display: block;
    }
    
    /* Style pour les sections de filières */
    .field-section-content {
        display: none;
    }
    
    .field-section-content.active {
        display: block;
    }
    
    /* Transition pour les icônes */
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
                        
                        <!-- Menu déroulant d'exportation -->
                        <div class="relative dropdown-container">
                            <button id="exportBtn" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 flex items-center">
                                <i class="fas fa-file-export mr-2"></i> Exporter <i class="fas fa-chevron-down ml-2" id="exportIcon"></i>
                            </button>
                            <div id="exportMenu" class="dropdown-menu">
                                <div class="py-1">
                                    <a href="{{ route('campuses.solvable', $campus) }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        <i class="fas fa-check-circle mr-2 text-green-500"></i> Étudiants solvables
                                    </a>
                                    <a href="{{ route('campuses.insolvable', $campus) }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        <i class="fas fa-exclamation-circle mr-2 text-yellow-500"></i> Étudiants insolvables
                                    </a>
                                </div>
                            </div>
                        </div>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion du menu d'exportation
        const exportBtn = document.getElementById('exportBtn');
        const exportMenu = document.getElementById('exportMenu');
        const exportIcon = document.getElementById('exportIcon');
        
        if (exportBtn && exportMenu) {
            exportBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                exportMenu.classList.toggle('active');
                exportIcon.classList.toggle('rotate-icon');
            });
            
            // Fermer le menu quand on clique ailleurs
            document.addEventListener('click', function(e) {
                if (!exportBtn.contains(e.target) && !exportMenu.contains(e.target)) {
                    exportMenu.classList.remove('active');
                    exportIcon.classList.remove('rotate-icon');
                }
            });
            
            // Empêcher la propagation des clics sur les liens du menu
            const exportLinks = exportMenu.querySelectorAll('a');
            exportLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        }
        
        // Gestion des sections de filières
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
@endpush

@endsection