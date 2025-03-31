@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="mb-6">
        <div class="card">
            <div class="card-header flex flex-col md:flex-row justify-between items-center gap-4">
                <h5 class="font-bold text-primary-600 flex items-center">
                    <i class="fas fa-money-bill-wave mr-2"></i>{{ session('current_school')->term('payments', 'Paiements') }}
                </h5>
                
                <div class="flex space-x-2">
                    <a href="{{ route('payments.create') }}" class="btn-primary text-sm">
                        <i class="fas fa-plus-circle mr-1"></i> {{ session('current_school')->term('new_payment', 'Nouveau paiement') }}
                    </a>
                    <a href="{{ route('payments.quick') }}" class="btn-success text-sm">
                        <i class="fas fa-bolt mr-1"></i> {{ __('Paiement rapide') }}
                    </a>
                    <a href="{{ route('payments.export') }}" class="btn-outline text-sm">
                        <i class="fas fa-file-excel mr-1"></i> {{ session('current_school')->term('export', 'Exporter tout') }}
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if (session('status'))
                    <div class="mb-4 bg-green-100 border-l-4 border-green-600 text-green-700 p-4 rounded relative" role="alert">
                        <div class="flex">
                            <div class="py-1"><i class="fas fa-check-circle mr-2"></i></div>
                            <div>{{ session('status') }}</div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="card bg-primary-50 border border-primary-100">
                        <div class="card-body flex items-center p-4">
                            <div class="rounded-full bg-primary-600 p-3 mr-3 text-white">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div>
                                <h6 class="text-sm text-gray-500 mb-0">{{ session('current_school')->term('total_payments', 'Total des paiements') }}</h6>
                                <h4 class="text-lg font-bold text-primary-600 mb-0">{{ number_format($totalAmount, 0, ',', ' ') }} {{ session('current_school')->term('currency', 'FCFA') }}</h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card bg-green-50 border border-green-100">
                        <div class="card-body flex items-center p-4">
                            <div class="rounded-full bg-green-600 p-3 mr-3 text-white">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div>
                                <h6 class="text-sm text-gray-500 mb-0">{{ session('current_school')->term('number_of_payments', 'Nombre de paiements') }}</h6>
                                <h4 class="text-lg font-bold text-green-600 mb-0">{{ $payments->count() }}</h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card bg-blue-50 border border-blue-100">
                        <div class="card-body flex items-center p-4">
                            <div class="rounded-full bg-blue-600 p-3 mr-3 text-white">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <h6 class="text-sm text-gray-500 mb-0">{{ session('current_school')->term('last_payment', 'Dernier paiement') }}</h6>
                                <h4 class="text-lg font-bold text-blue-600 mb-0">
                                    @if($payments->count() > 0)
                                    {{ \Carbon\Carbon::parse($payments->first()->payment_date)->format('d/m/Y') }}
                                    @else
                                    -
                                    @endif
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <form action="{{ route('payments.index') }}" method="GET" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ session('current_school')->term('search', 'Recherche') }}</label>
                                <input type="text" name="search" id="search" value="{{ request()->search }}" 
                                    class="form-input w-full" placeholder="{{ session('current_school')->term('search_placeholder', 'Reçu, nom, description...') }}">
                            </div>
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">{{ session('current_school')->term('date_from', 'Date début') }}</label>
                                <input type="date" name="date_from" id="date_from" value="{{ request()->date_from }}" class="form-input w-full">
                            </div>
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">{{ session('current_school')->term('date_to', 'Date fin') }}</label>
                                <input type="date" name="date_to" id="date_to" value="{{ request()->date_to }}" class="form-input w-full">
                            </div>
                            <div>
                                <label for="amount_min" class="block text-sm font-medium text-gray-700 mb-1">{{ session('current_school')->term('amount_min', 'Montant min') }}</label>
                                <input type="number" name="amount_min" id="amount_min" value="{{ request()->amount_min }}" class="form-input w-full" min="0">
                            </div>
                            <div>
                                <label for="amount_max" class="block text-sm font-medium text-gray-700 mb-1">{{ session('current_school')->term('amount_max', 'Montant max') }}</label>
                                <input type="number" name="amount_max" id="amount_max" value="{{ request()->amount_max }}" class="form-input w-full" min="0">
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            @if(request()->has('search') || request()->has('date_from') || request()->has('date_to') || request()->has('amount_min') || request()->has('amount_max'))
                                <a href="{{ route('payments.index') }}" class="btn-outline mr-2">
                                    <i class="fas fa-times mr-1"></i> {{ session('current_school')->term('reset', 'Réinitialiser') }}
                                </a>
                            @endif
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-search mr-1"></i> {{ session('current_school')->term('search', 'Rechercher') }}
                            </button>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ session('current_school')->term('receipt_number', 'Reçu N°') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ session('current_school')->term('student', 'Étudiant') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ session('current_school')->term('field', 'Filière') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ session('current_school')->term('amount', 'Montant') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ session('current_school')->term('payment_date', 'Date') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ session('current_school')->term('payment_method', 'Méthode') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ session('current_school')->term('actions', 'Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($payments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-200 text-gray-800 rounded-full">
                                            {{ $payment->receipt_number }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 mr-2 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center font-bold">
                                                {{ strtoupper(substr($payment->student->fullName, 0, 1)) }}
                                            </div>
                                            <span>{{ $payment->student->fullName }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $payment->student->field->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-green-600">
                                        {{ number_format($payment->amount, 0, ',', ' ') }} {{ session('current_school')->term('currency', 'FCFA') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                            @if($payment->payment_method == 'cash')
                                            <i class="fas fa-money-bill-wave mr-1"></i>
                                            @elseif($payment->payment_method == 'bank')
                                            <i class="fas fa-university mr-1"></i>
                                            @elseif($payment->payment_method == 'mobile')
                                            <i class="fas fa-mobile-alt mr-1"></i>
                                            @else
                                            <i class="fas fa-credit-card mr-1"></i>
                                            @endif
                                            {{ ucfirst($payment->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex justify-end space-x-1">
                                            <a href="{{ route('students.show', $payment->student_id) }}" class="px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200" title="Voir l'étudiant">
                                                <i class="fas fa-user-graduate"></i>
                                            </a>
                                            <a href="{{ route('payments.show', $payment->id) }}" class="px-2 py-1 bg-primary-100 text-primary-700 rounded hover:bg-primary-200" title="Voir le reçu">
                                                <i class="fas fa-receipt"></i>
                                            </a>
                                            <a href="{{ route('payments.edit', $payment->id) }}" class="px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('payments.print', $payment->id) }}" class="px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200" title="Imprimer reçu">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-money-bill-wave text-5xl text-gray-400 mb-4"></i>
                                            <h6 class="text-gray-500 mb-4">{{ session('current_school')->term('no_payments', 'Aucun paiement trouvé') }}</h6>
                                            <a href="{{ route('payments.create') }}" class="btn-primary">
                                                <i class="fas fa-plus-circle mr-1"></i> {{ session('current_school')->term('new_payment', 'Nouveau paiement') }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Utiliser Tippy.js pour les tooltips (déjà configuré dans app.js)
    document.addEventListener('DOMContentLoaded', function() {
        tippy('[title]', {
            placement: 'top',
            arrow: true
        });
    });
</script>
@endpush
@endsection
