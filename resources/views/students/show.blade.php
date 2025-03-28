@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="w-full">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="flex flex-col sm:flex-row justify-between items-center p-5 gap-4">
                    <h1 class="text-xl font-bold text-primary-600 flex items-center">
                        <i class="fas fa-user-graduate mr-2"></i>Détails de l'étudiant
                    </h1>
                    <div class="flex flex-wrap sm:flex-nowrap gap-2">
                        <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-medium transition-colors duration-150">
                            <i class="fas fa-arrow-left mr-1.5"></i> Retour
                        </a>
                        <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center px-4 py-2 border border-primary-500 rounded-lg text-primary-600 bg-white hover:bg-primary-50 font-medium transition-colors duration-150">
                            <i class="fas fa-edit mr-1.5"></i> Modifier
                        </a>
                        <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors duration-150">
                            <i class="fas fa-plus mr-1.5"></i> Ajouter un paiement
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Profil de l'étudiant -->
        <div class="lg:col-span-4">
            <div class="bg-white rounded-xl shadow-sm h-full">
                <div class="border-b border-gray-100 py-4 px-5 flex justify-between items-center">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>Informations
                    </h5>
                    <span class="px-3 py-1 rounded-full text-xs font-medium 
                           {{ $statusColor === 'success' ? 'bg-green-100 text-green-800' : 
                             ($statusColor === 'warning' ? 'bg-yellow-100 text-yellow-800' : 
                             'bg-red-100 text-red-800') }}">
                        {{ $paymentStatus }}
                    </span>
                </div>
                <div class="p-5">
                    <div class="text-center mb-6">
                        <div class="mx-auto rounded-full bg-primary-100 flex items-center justify-center mb-4 w-28 h-28 overflow-hidden shadow-md">
                            @if($student->photo)
                                <img src="{{ Storage::url('students/' . $student->photo) }}" alt="{{ $student->fullName }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-4xl text-primary-600 font-bold">{{ substr($student->fullName ?? 'U', 0, 1) }}</span>
                            @endif
                        </div>
                        <h4 class="font-bold text-gray-800 text-lg mb-1">{{ $student->fullName }}</h4>
                        <p class="text-gray-500 mb-3 text-sm">ID: {{ $student->id ?? 'N/A' }}</p>
                        <div class="flex justify-center gap-2">
                            @if($student->email)
                                <a href="mailto:{{ $student->email }}" class="w-9 h-9 rounded-full border border-primary-500 flex items-center justify-center text-primary-500 hover:bg-primary-500 hover:text-white transition-colors duration-150" title="Envoyer un email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            @endif
                            @if($student->phone)
                                <a href="tel:{{ $student->phone }}" class="w-9 h-9 rounded-full border border-green-500 flex items-center justify-center text-green-500 hover:bg-green-500 hover:text-white transition-colors duration-150" title="Appeler">
                                    <i class="fas fa-phone"></i>
                                </a>
                            @endif
                            <button class="w-9 h-9 rounded-full border border-blue-400 flex items-center justify-center text-blue-400 hover:bg-blue-400 hover:text-white transition-colors duration-150" title="Imprimer la fiche">
                                <i class="fas fa-print"></i>
                            </button>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 space-y-4">
                        <div class="flex">
                            <div class="flex-shrink-0 w-9 h-9 rounded-full bg-primary-50 flex items-center justify-center">
                                <i class="fas fa-graduation-cap text-primary-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-gray-500 text-xs mb-0.5">Filière</p>
                                <p class="font-medium text-gray-800 mb-0">{{ $student->field->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex-shrink-0 w-9 h-9 rounded-full bg-primary-50 flex items-center justify-center">
                                <i class="fas fa-school text-primary-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-gray-500 text-xs mb-0.5">Campus</p>
                                <p class="font-medium text-gray-800 mb-0">{{ $student->field->campus->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex-shrink-0 w-9 h-9 rounded-full bg-primary-50 flex items-center justify-center">
                                <i class="fas fa-envelope text-primary-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-gray-500 text-xs mb-0.5">Email</p>
                                <p class="font-medium text-gray-800 mb-0">{{ $student->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex-shrink-0 w-9 h-9 rounded-full bg-primary-50 flex items-center justify-center">
                                <i class="fas fa-phone text-primary-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-gray-500 text-xs mb-0.5">Téléphone</p>
                                <p class="font-medium text-gray-800 mb-0">{{ $student->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex-shrink-0 w-9 h-9 rounded-full bg-primary-50 flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-primary-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-gray-500 text-xs mb-0.5">Adresse</p>
                                <p class="font-medium text-gray-800 mb-0">{{ $student->address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statut et paiements -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-xl shadow-sm mb-6">
                <div class="border-b border-gray-100 py-4 px-5 flex flex-col sm:flex-row justify-between items-center gap-3">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-money-bill-wave mr-2"></i>Statut de paiement
                    </h5>
                    <div class="flex space-x-2">
                        <a href="{{ route('payments.print-list', ['student_id' => $student->id]) }}" class="inline-flex items-center px-3 py-1.5 border border-primary-500 text-primary-500 hover:bg-primary-500 hover:text-white text-sm rounded-lg transition-colors duration-150" target="_blank">
                            <i class="fas fa-print mr-1.5"></i> Imprimer
                        </a>
                        <a href="{{ route('payments.export-excel', ['student_id' => $student->id]) }}" class="inline-flex items-center px-3 py-1.5 border border-green-500 text-green-500 hover:bg-green-500 hover:text-white text-sm rounded-lg transition-colors duration-150">
                            <i class="fas fa-file-excel mr-1.5"></i> Excel
                        </a>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center">
                                <div class="rounded-xl bg-blue-50 p-3.5 mr-4">
                                    <i class="fas fa-money-check text-blue-500 text-lg"></i>
                                </div>
                                <div>
                                    <h6 class="text-gray-500 text-xs mb-1">Frais total</h6>
                                    <h4 class="font-bold text-gray-800 text-lg">{{ number_format($totalFees, 0, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center">
                                <div class="rounded-xl bg-green-50 p-3.5 mr-4">
                                    <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                </div>
                                <div>
                                    <h6 class="text-gray-500 text-xs mb-1">Montant payé</h6>
                                    <h4 class="font-bold text-green-600 text-lg">{{ number_format($totalPaid, 0, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center">
                                <div class="rounded-xl {{ $remainingAmount > 0 ? 'bg-yellow-50' : 'bg-green-50' }} p-3.5 mr-4">
                                    <i class="fas fa-{{ $remainingAmount > 0 ? 'exclamation-triangle' : 'check-double' }} {{ $remainingAmount > 0 ? 'text-yellow-500' : 'text-green-500' }} text-lg"></i>
                                </div>
                                <div>
                                    <h6 class="text-gray-500 text-xs mb-1">Reste à payer</h6>
                                    <h4 class="font-bold {{ $remainingAmount > 0 ? 'text-yellow-600' : 'text-green-600' }} text-lg">{{ number_format($remainingAmount, 0, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <h6 class="font-bold text-gray-700">Progrès de paiement</h6>
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                {{ $statusColor === 'success' ? 'bg-green-100 text-green-800' : 
                                ($statusColor === 'warning' ? 'bg-yellow-100 text-yellow-800' : 
                                'bg-red-100 text-red-800') }}">
                                {{ $paymentPercentage }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                            <div class="h-2.5 rounded-full 
                                {{ $statusColor === 'success' ? 'bg-green-500' : 
                                ($statusColor === 'warning' ? 'bg-yellow-500' : 
                                'bg-red-500') }}" 
                                style="width: {{ $paymentPercentage }}%"></div>
                        </div>
                    </div>

                    <div>
                        <h6 class="font-bold text-gray-700 mb-3">Historique des paiements</h6>
                        
                        @if($student->payments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reçu №</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($student->payments as $payment)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 font-mono">
                                                {{ $payment->receipt_number }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-800">
                                            {{ $payment->description }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-gray-800">
                                            {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                            <div class="flex justify-center space-x-1">
                                                <a href="{{ route('payments.show', $payment->id) }}" target="_blank" class="p-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150" title="Voir le reçu">
                                                    <i class="fas fa-receipt"></i>
                                                </a>
                                                <a href="{{ route('payments.edit', $payment->id) }}" class="p-1.5 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-150" title="Modifier">
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
                        <div class="text-center py-6 bg-gray-50 rounded-xl">
                            <div class="bg-white rounded-full mx-auto w-16 h-16 flex items-center justify-center mb-3 shadow-sm">
                                <i class="fas fa-receipt text-gray-400 text-xl"></i>
                            </div>
                            <h5 class="font-bold text-gray-800 mb-1">Aucun paiement</h5>
                            <p class="text-gray-500 mb-4 text-sm">Cet étudiant n'a effectué aucun paiement</p>
                            <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors duration-150">
                                <i class="fas fa-plus mr-1.5"></i> Ajouter un paiement
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
