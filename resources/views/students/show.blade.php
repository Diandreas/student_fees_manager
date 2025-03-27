@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="w-full">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="flex justify-between items-center p-5">
                    <h1 class="text-xl font-bold text-primary-600 flex items-center">
                        <i class="fas fa-user-graduate mr-2"></i>Détails de l'étudiant
                    </h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('students.index') }}" class="btn-outline">
                            <i class="fas fa-arrow-left mr-1"></i> Retour
                        </a>
                        <a href="{{ route('students.edit', $student) }}" class="btn-outline">
                            <i class="fas fa-edit mr-1"></i> Modifier
                        </a>
                        <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" class="btn-primary">
                            <i class="fas fa-plus mr-1"></i> Ajouter un paiement
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
                        <div class="mx-auto rounded-full bg-primary-100 flex items-center justify-center mb-3 w-28 h-28 overflow-hidden">
                            @if($student->photo)
                                <img src="{{ Storage::url('students/' . $student->photo) }}" alt="{{ $student->fullName }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-4xl text-primary-600">{{ substr($student->fullName ?? 'U', 0, 1) }}</span>
                            @endif
                        </div>
                        <h4 class="font-bold text-gray-800 mb-1">{{ $student->fullName }}</h4>
                        <p class="text-gray-500 mb-2 text-sm">ID: {{ $student->id ?? 'N/A' }}</p>
                        <div class="flex justify-center">
                            @if($student->email)
                                <a href="mailto:{{ $student->email }}" class="w-8 h-8 rounded-full border border-primary-500 flex items-center justify-center mx-1 text-primary-500 hover:bg-primary-500 hover:text-white transition" title="Envoyer un email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            @endif
                            @if($student->phone)
                                <a href="tel:{{ $student->phone }}" class="w-8 h-8 rounded-full border border-green-500 flex items-center justify-center mx-1 text-green-500 hover:bg-green-500 hover:text-white transition" title="Appeler">
                                    <i class="fas fa-phone"></i>
                                </a>
                            @endif
                            <button class="w-8 h-8 rounded-full border border-blue-400 flex items-center justify-center mx-1 text-blue-400 hover:bg-blue-400 hover:text-white transition" title="Imprimer la fiche">
                                <i class="fas fa-print"></i>
                            </button>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <div class="mb-3">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-graduation-cap text-primary-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-500 text-sm mb-0">Filière</p>
                                    <p class="font-medium mb-0">{{ $student->field->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-school text-primary-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-500 text-sm mb-0">Campus</p>
                                    <p class="font-medium mb-0">{{ $student->field->campus->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-envelope text-primary-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-500 text-sm mb-0">Email</p>
                                    <p class="font-medium mb-0">{{ $student->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-phone text-primary-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-500 text-sm mb-0">Téléphone</p>
                                    <p class="font-medium mb-0">{{ $student->phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-0">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-primary-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-500 text-sm mb-0">Adresse</p>
                                    <p class="font-medium mb-0">{{ $student->address ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statut et paiements -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-xl shadow-sm mb-6">
                <div class="border-b border-gray-100 py-4 px-5 flex justify-between items-center">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-money-bill-wave mr-2"></i>Statut de paiement
                    </h5>
                    <div class="flex space-x-2">
                        <a href="{{ route('payments.print-list', ['student_id' => $student->id]) }}" class="px-3 py-1.5 border border-primary-500 text-primary-500 hover:bg-primary-500 hover:text-white text-sm rounded transition-colors duration-200" target="_blank">
                            <i class="fas fa-print mr-1"></i> Imprimer
                        </a>
                        <a href="{{ route('payments.export-excel', ['student_id' => $student->id]) }}" class="px-3 py-1.5 border border-green-500 text-green-500 hover:bg-green-500 hover:text-white text-sm rounded transition-colors duration-200">
                            <i class="fas fa-file-excel mr-1"></i> Excel
                        </a>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="rounded-full bg-primary-600 p-3 mr-3">
                                    <i class="fas fa-money-check text-white"></i>
                                </div>
                                <div>
                                    <h6 class="text-gray-500 text-xs mb-1">Frais total</h6>
                                    <h4 class="font-bold text-gray-800">{{ number_format($totalFees, 0, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="rounded-full bg-green-500 p-3 mr-3">
                                    <i class="fas fa-check-circle text-white"></i>
                                </div>
                                <div>
                                    <h6 class="text-gray-500 text-xs mb-1">Montant payé</h6>
                                    <h4 class="font-bold text-green-600">{{ number_format($totalPaid, 0, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="rounded-full {{ $remainingAmount > 0 ? 'bg-yellow-500' : 'bg-green-500' }} p-3 mr-3">
                                    <i class="fas fa-{{ $remainingAmount > 0 ? 'exclamation-triangle' : 'check-double' }} text-white"></i>
                                </div>
                                <div>
                                    <h6 class="text-gray-500 text-xs mb-1">Reste à payer</h6>
                                    <h4 class="font-bold {{ $remainingAmount > 0 ? 'text-yellow-600' : 'text-green-600' }}">{{ number_format($remainingAmount, 0, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="flex justify-between items-center mb-2">
                            <h6 class="font-bold text-gray-700">Progrès de paiement</h6>
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                {{ $statusColor === 'success' ? 'bg-green-100 text-green-800' : 
                                ($statusColor === 'warning' ? 'bg-yellow-100 text-yellow-800' : 
                                'bg-red-100 text-red-800') }}">
                                {{ $paymentPercentage }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full 
                                {{ $statusColor === 'success' ? 'bg-green-500' : 
                                ($statusColor === 'warning' ? 'bg-yellow-500' : 
                                'bg-red-500') }}" 
                                style="width: {{ $paymentPercentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm">
                <div class="border-b border-gray-100 py-4 px-5 flex justify-between items-center">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-history mr-2"></i>Historique des paiements
                    </h5>
                    <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" class="btn-primary text-sm">
                        <i class="fas fa-plus mr-1"></i> Nouveau paiement
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 text-left">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Reçu N°</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($student->payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-800">
                                        {{ $payment->receipt_number ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $payment->description }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $payment->payment_date ? Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-medium text-green-600">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="inline-flex rounded-md shadow-sm">
                                        <a href="{{ route('payments.show', $payment) }}" class="py-1 px-2 border border-primary-500 text-primary-500 hover:bg-primary-500 hover:text-white rounded-l-md transition-colors" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('payments.print', $payment) }}" class="py-1 px-2 border-t border-b border-gray-300 text-gray-500 hover:bg-gray-200 transition-colors" target="_blank" title="Imprimer">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="py-1 px-2 border border-red-500 text-red-500 hover:bg-red-500 hover:text-white rounded-r-md transition-colors" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement?');">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center">
                                    <div class="py-8">
                                        <i class="fas fa-file-invoice-dollar text-gray-400 text-5xl mb-4"></i>
                                        <h6 class="text-gray-500 mb-4">Aucun paiement enregistré pour cet étudiant</h6>
                                        <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" class="btn-primary text-sm">
                                            <i class="fas fa-plus mr-1"></i> Ajouter un paiement
                                        </a>
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
<script>
    // Initialize tooltips
    tippy('[title]', {
        placement: 'top',
        arrow: true,
        animation: 'scale',
    });
</script>
@endpush
@endsection
