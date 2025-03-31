@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section with Breadcrumbs and Actions -->
    <div class="mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <!-- Breadcrumb with improved styling -->
                        <nav class="mb-2 text-sm">
                            <ol class="flex flex-wrap items-center">
                                <li class="flex items-center">
                                    <a href="{{ route('campuses.index') }}" class="text-primary-600 hover:text-primary-800 transition">Campus</a>
                                    <svg class="h-4 w-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </li>
                                <li class="flex items-center">
                                    <a href="{{ route('campuses.show', $field->campus) }}" class="text-primary-600 hover:text-primary-800 transition">{{ $field->campus->name }}</a>
                                    <svg class="h-4 w-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </li>
                                <li class="text-gray-600 font-medium">{{ $field->name }}</li>
                            </ol>
                        </nav>
                        <!-- Title with improved typography -->
                        <h2 class="text-2xl font-bold text-gray-800 mb-3">{{ $field->name }}</h2>
                        <div class="flex flex-wrap gap-2">
                            @if($field->code)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">Code: {{ $field->code }}</span>
                            @endif
                            @if($field->educationLevel)
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">Niveau: {{ $field->educationLevel->name }}</span>
                            @endif
                        </div>
                    </div>
                    <!-- Action buttons with improved styling -->
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('campuses.show', $field->campus) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition flex items-center justify-center text-sm font-medium">
                            <i class="fas fa-arrow-left mr-2"></i> Retour au campus
                        </a>
                        <a href="{{ route('fields.edit', $field) }}" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 shadow-sm transition flex items-center justify-center text-sm font-medium">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                        <a href="{{ route('students.create', ['field_id' => $field->id]) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 shadow-sm transition flex items-center justify-center text-sm font-medium">
                            <i class="fas fa-user-plus mr-2"></i> Ajouter un étudiant
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Similar Fields Section (improved styling) -->
    @if($similarFields->count() > 0)
    <div class="mb-8">
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500 text-xl mr-4"></i>
                </div>
                <div>
                    <h5 class="text-lg font-semibold text-blue-800 mb-2">Classes similaires</h5>
                    <p class="text-blue-700 mb-4">
                        Il existe {{ $similarFields->count() }} autre(s) classe(s) "{{ $field->name }}" dans ce campus. 
                        Vous pouvez les consulter ici :
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($similarFields as $similarField)
                            <a href="{{ route('fields.show', $similarField) }}" class="px-4 py-2 bg-white text-blue-700 border border-blue-200 rounded-md hover:bg-blue-50 transition shadow-sm text-sm font-medium">
                                {{ $similarField->name }} 
                                @if($similarField->code)
                                    ({{ $similarField->code }})
                                @endif
                                - {{ $similarField->students->count() }} étudiants
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Information Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h5 class="font-bold text-gray-800">Informations</h5>
                </div>
                <div class="p-6">
                    <div class="flex flex-col items-center mb-6">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mb-4 shadow-md">
                            <span class="text-4xl font-bold text-white">{{ substr($field->name, 0, 1) }}</span>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800">{{ $field->name }}</h4>
                        <p class="text-gray-500 mt-1">{{ $field->campus->name }}</p>
                    </div>

                    <div class="space-y-3">
                        @if($field->educationLevel)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Niveau d'éducation:</span>
                                <span class="font-medium text-gray-800">{{ $field->educationLevel->name }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Frais d'inscription:</span>
                            <span class="font-medium text-gray-800">{{ number_format($field->fees, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Nombre d'étudiants:</span>
                            <span class="font-medium text-gray-800">{{ $studentStats['total'] }}</span>
                        </div>
                        @if($field->code)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Code:</span>
                                <span class="font-medium text-gray-800">{{ $field->code }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Payment Statistics Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h5 class="font-bold text-gray-800">Statistiques de paiement</h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h6 class="text-gray-500 text-sm mb-1">Total frais</h6>
                            <h4 class="text-lg font-bold text-gray-800">{{ number_format($studentStats['totalFees'], 0, ',', ' ') }} FCFA</h4>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h6 class="text-gray-500 text-sm mb-1">Payé</h6>
                            <h4 class="text-lg font-bold {{ $studentStats['paymentPercentage'] == 100 ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ number_format($studentStats['totalPaid'], 0, ',', ' ') }} FCFA
                            </h4>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <h6 class="font-medium text-gray-700">Progression</h6>
                            <span class="text-sm font-bold {{ $studentStats['paymentPercentage'] == 100 ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $studentStats['paymentPercentage'] }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-3 rounded-full {{ $studentStats['paymentPercentage'] == 100 ? 'bg-green-600' : 'bg-yellow-500' }}" 
                                 style="width: {{ $studentStats['paymentPercentage'] }}%;"></div>
                        </div>
                    </div>
                    
                    <!-- Payment Status Statistics -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-green-50 rounded-lg p-3 border border-green-100">
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-green-600">{{ $studentStats['paid'] }}</h2>
                                <h6 class="text-xs text-green-800 mt-1">Payé intégralement</h6>
                            </div>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-100">
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-yellow-600">{{ $studentStats['partial'] }}</h2>
                                <h6 class="text-xs text-yellow-800 mt-1">Partiellement</h6>
                            </div>
                        </div>
                        <div class="bg-red-50 rounded-lg p-3 border border-red-100">
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-red-600">{{ $studentStats['unpaid'] }}</h2>
                                <h6 class="text-xs text-red-800 mt-1">Aucun paiement</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <h5 class="font-bold text-gray-800 mb-3 sm:mb-0">Liste des étudiants</h5>
                    <div class="flex gap-3">
                        <a href="{{ route('students.create', ['field_id' => $field->id]) }}" 
                           class="px-4 py-2 bg-green-600 text-white rounded-md shadow-sm hover:bg-green-700 transition text-sm font-medium flex items-center">
                            <i class="fas fa-user-plus mr-2"></i> Nouvel étudiant
                        </a>
                        <div class="dropdown relative">
                            <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-600 transition text-sm font-medium flex items-center">
                                <i class="fas fa-file-export mr-2"></i> Exporter <i class="fas fa-chevron-down ml-2"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 hidden mt-2 w-64 bg-white shadow-lg rounded-md z-10">
                                <div class="py-1">
                                    <a href="{{ route('fields.report', $field) }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        <i class="fas fa-file-pdf mr-2 text-red-500"></i> Rapport complet
                                    </a>
                                    <a href="{{ route('fields.solvable', $field) }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        <i class="fas fa-check-circle mr-2 text-green-500"></i> Étudiants solvables
                                    </a>
                                    <a href="{{ route('fields.insolvable', $field) }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                        <i class="fas fa-exclamation-circle mr-2 text-yellow-500"></i> Étudiants insolvables
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-0">
                    @if($students->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nom complet</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Téléphone</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut paiement</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Montant payé</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($students as $student)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('students.show', $student) }}" class="font-medium text-gray-900 hover:text-primary-600 transition">
                                                    {{ $student->fullName }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $student->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $student->phone ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($student->payment_status === 'paid')
                                                    <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 font-medium">Payé</span>
                                                @elseif($student->payment_status === 'partial')
                                                    <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 font-medium">Partiel</span>
                                                @else
                                                    <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800 font-medium">Non payé</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col">
                                                    <span class="text-gray-700 font-medium">{{ number_format($student->paid_amount, 0, ',', ' ') }} FCFA</span>
                                                    <div class="w-24 bg-gray-200 rounded-full h-1.5 mt-1.5">
                                                        <div class="h-1.5 rounded-full {{ $student->payment_status === 'paid' ? 'bg-green-600' : ($student->payment_status === 'partial' ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                                             style="width: {{ $field->fees > 0 ? round(($student->paid_amount / $field->fees) * 100) : 0 }}%;"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex gap-2">
                                                    <a href="{{ route('students.show', $student) }}" 
                                                       class="p-2 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 transition shadow-sm" 
                                                       title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" 
                                                       class="p-2 bg-green-50 text-green-700 rounded-md hover:bg-green-100 transition shadow-sm" 
                                                       title="Ajouter un paiement">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </a>
                                                    <a href="{{ route('students.edit', $student) }}" 
                                                       class="p-2 bg-gray-50 text-gray-700 rounded-md hover:bg-gray-100 transition shadow-sm" 
                                                       title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $students->links() }}
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
                            <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                <i class="fas fa-user-graduate text-4xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Aucun étudiant inscrit</h3>
                            <p class="text-gray-500 mb-6 max-w-md">Aucun étudiant n'a été inscrit dans cette filière pour le moment.</p>
                            <a href="{{ route('students.create', ['field_id' => $field->id]) }}" 
                               class="px-5 py-2 bg-green-600 text-white rounded-md shadow-sm hover:bg-green-700 transition text-sm font-medium flex items-center">
                                <i class="fas fa-user-plus mr-2"></i> Ajouter un étudiant
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion du dropdown pour les exports
        const dropdownButton = document.querySelector('.dropdown button');
        const dropdownMenu = document.querySelector('.dropdown-menu');
        
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
</script>
@endpush