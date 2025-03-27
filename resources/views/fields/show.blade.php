@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="card">
            <div class="card-body">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <nav class="mb-1 text-sm">
                            <ol class="flex flex-wrap">
                                <li class="flex items-center">
                                    <a href="{{ route('campuses.index') }}" class="text-primary-600 hover:text-primary-800">Campus</a>
                                    <svg class="h-4 w-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </li>
                                <li class="flex items-center">
                                    <a href="{{ route('campuses.show', $field->campus) }}" class="text-primary-600 hover:text-primary-800">{{ $field->campus->name }}</a>
                                    <svg class="h-4 w-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </li>
                                <li class="text-gray-500">{{ $field->name }}</li>
                            </ol>
                        </nav>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $field->name }}</h2>
                        <div class="flex flex-wrap mt-2">
                            @if($field->code)
                                <span class="px-2 py-1 bg-gray-200 text-gray-700 rounded-full text-xs mr-2 mb-2">Code: {{ $field->code }}</span>
                            @endif
                            @if($field->educationLevel)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs mb-2">Niveau: {{ $field->educationLevel->name }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-wrap mt-3 md:mt-0 space-x-0 space-y-2 md:space-y-0 md:space-x-2">
                        <a href="{{ route('campuses.show', $field->campus) }}" class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded text-gray-700 bg-white hover:bg-gray-50 flex items-center justify-center">
                            <i class="fas fa-arrow-left mr-2"></i> Retour au campus
                        </a>
                        <a href="{{ route('fields.edit', $field) }}" class="w-full md:w-auto px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 flex items-center justify-center">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                        <a href="{{ route('students.create', ['field_id' => $field->id]) }}" class="w-full md:w-auto px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 flex items-center justify-center">
                            <i class="fas fa-user-plus mr-2"></i> Ajouter un étudiant
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Afficher les classes similaires s'il y en a -->
    @if($similarFields->count() > 0)
    <div class="mb-6">
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500 text-xl mr-3"></i>
                </div>
                <div>
                    <h5 class="text-lg font-semibold text-blue-800 mb-1">Classes similaires</h5>
                    <p class="text-blue-700 mb-2">
                        Il existe {{ $similarFields->count() }} autre(s) classe(s) "{{ $field->name }}" dans ce campus. 
                        Vous pouvez les consulter ici :
                    </p>
                    <div class="flex flex-wrap">
                        @foreach($similarFields as $similarField)
                            <a href="{{ route('fields.show', $similarField) }}" class="px-3 py-1 bg-white text-blue-700 border border-blue-300 rounded hover:bg-blue-50 mr-2 mb-2 text-sm">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="card h-full">
                <div class="card-header">
                    <h5 class="font-bold">Informations</h5>
                </div>
                <div class="card-body">
                    <div class="flex flex-col items-center mb-6">
                        <div class="w-24 h-24 rounded-full bg-blue-500 bg-opacity-80 flex items-center justify-center mb-3">
                            <span class="text-4xl text-white">{{ substr($field->name, 0, 1) }}</span>
                        </div>
                        <h4 class="text-lg font-bold">{{ $field->name }}</h4>
                        <p class="text-gray-500">{{ $field->campus->name }}</p>
                    </div>

                    <div class="mb-3 space-y-2">
                        @if($field->educationLevel)
                            <p><span class="font-semibold">Niveau d'éducation:</span> {{ $field->educationLevel->name }}</p>
                        @endif
                        <p><span class="font-semibold">Frais d'inscription:</span> {{ number_format($field->fees, 0, ',', ' ') }} FCFA</p>
                        <p><span class="font-semibold">Nombre d'étudiants:</span> {{ $studentStats['total'] }}</p>
                        
                        @if($field->code)
                            <p><span class="font-semibold">Code:</span> {{ $field->code }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Statistiques de paiement -->
            <div class="card mt-6">
                <div class="card-header">
                    <h5 class="font-bold">Statistiques de paiement</h5>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 mb-4">
                        <div class="border-r border-gray-200 pr-3">
                            <h6 class="text-gray-500 text-sm mb-1">Total frais</h6>
                            <h4 class="text-lg font-bold">{{ number_format($studentStats['totalFees'], 0, ',', ' ') }} FCFA</h4>
                        </div>
                        <div class="pl-3">
                            <h6 class="text-gray-500 text-sm mb-1">Payé</h6>
                            <h4 class="text-lg font-bold {{ $studentStats['paymentPercentage'] == 100 ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ number_format($studentStats['totalPaid'], 0, ',', ' ') }} FCFA
                            </h4>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-1">
                            <h6 class="font-medium">Progression</h6>
                            <span>{{ $studentStats['paymentPercentage'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full {{ $studentStats['paymentPercentage'] == 100 ? 'bg-green-600' : 'bg-yellow-500' }}" 
                                 style="width: {{ $studentStats['paymentPercentage'] }}%;"></div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 text-center gap-2">
                        <div>
                            <div class="p-2 rounded bg-green-100 mb-2">
                                <h2 class="text-xl font-bold text-green-600">{{ $studentStats['paid'] }}</h2>
                            </div>
                            <h6 class="text-xs text-gray-500">Payé intégralement</h6>
                        </div>
                        <div>
                            <div class="p-2 rounded bg-yellow-100 mb-2">
                                <h2 class="text-xl font-bold text-yellow-600">{{ $studentStats['partial'] }}</h2>
                            </div>
                            <h6 class="text-xs text-gray-500">Partiellement</h6>
                        </div>
                        <div>
                            <div class="p-2 rounded bg-red-100 mb-2">
                                <h2 class="text-xl font-bold text-red-600">{{ $studentStats['unpaid'] }}</h2>
                            </div>
                            <h6 class="text-xs text-gray-500">Aucun paiement</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <h5 class="font-bold mb-3 sm:mb-0">Liste des étudiants</h5>
                    <div class="flex space-x-2">
                        <a href="{{ route('students.create', ['field_id' => $field->id]) }}" class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">
                            <i class="fas fa-user-plus mr-1"></i> Nouvel étudiant
                        </a>
                        <a href="{{ route('fields.report', $field) }}" class="px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">
                            <i class="fas fa-file-export mr-1"></i> Exporter
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($field->students->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom complet</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut paiement</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant payé</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($field->students as $student)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('students.show', $student) }}" class="font-medium text-gray-900 hover:text-primary-600">
                                                    {{ $student->fullName }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $student->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $student->phone ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($student->payment_status === 'paid')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Payé</span>
                                                @elseif($student->payment_status === 'partial')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Partiel</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Non payé</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col">
                                                    <span>{{ number_format($student->paid_amount, 0, ',', ' ') }} FCFA</span>
                                                    <div class="w-20 bg-gray-200 rounded-full h-1 mt-1">
                                                        <div class="h-1 rounded-full {{ $student->payment_status === 'paid' ? 'bg-green-600' : ($student->payment_status === 'partial' ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                                             style="width: {{ $field->fees > 0 ? round(($student->paid_amount / $field->fees) * 100) : 0 }}%;"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex space-x-1">
                                                    <a href="{{ route('students.show', $student) }}" class="px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200" title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" class="px-2 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200" title="Ajouter un paiement">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </a>
                                                    <a href="{{ route('students.edit', $student) }}" class="px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                            <i class="fas fa-user-graduate text-5xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 mb-4">Aucun étudiant n'a été inscrit dans cette filière.</p>
                            <a href="{{ route('students.create', ['field_id' => $field->id]) }}" class="btn-primary">
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