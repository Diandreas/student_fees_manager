@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <h5 class="font-bold text-primary-600">
                    <i class="fas fa-edit mr-2"></i>{{ $school->term('edit_payment', 'Modifier le paiement') }}
                </h5>
                <div class="flex space-x-2">
                    <a href="{{ route('payments.index') }}" class="btn-outline">
                        <i class="fas fa-arrow-left mr-1"></i>{{ $school->term('back', 'Retour') }}
                    </a>
                    <a href="{{ route('payments.show', $payment->id) }}" class="btn-secondary">
                        <i class="fas fa-eye mr-1"></i>{{ $school->term('view_details', 'Voir détails') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('payments.update', $payment->id) }}" method="POST" id="paymentForm">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ $school->term('student', 'Étudiant') }} <span class="text-red-500">*</span>
                                </label>
                                <select id="student_id" name="student_id" class="form-select" required>
                                    <option value="">{{ $school->term('select_student', 'Sélectionnez un étudiant') }}</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" 
                                            data-remaining="{{ $student->remainingAmount }}"
                                            data-fees="{{ $student->field->fees }}"
                                            data-paid="{{ $student->field->fees - $student->remainingAmount }}"
                                            {{ $payment->student_id == $student->id ? 'selected' : '' }}>
                                            {{ $student->fullName }} ({{ $student->registration_number }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ $school->term('amount', 'Montant') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="amount" id="amount" class="form-input pr-12" 
                                           placeholder="0" value="{{ old('amount', $payment->amount) }}" required>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">{{ $school->term('currency', 'FCFA') }}</span>
                                    </div>
                                </div>
                                @error('amount')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div id="remaining-amount-info" class="mt-2 text-sm text-gray-600 hidden">
                                    {{ $school->term('remaining_to_pay', 'Reste à payer') }}: <span id="remaining-amount" class="font-medium"></span> {{ $school->term('currency', 'FCFA') }}
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ $school->term('description', 'Description') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="description" id="description" class="form-input" 
                                       value="{{ old('description', $payment->description) }}" required>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <div class="mb-4">
                                <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ $school->term('payment_date', 'Date de paiement') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="payment_date" id="payment_date" class="form-input" 
                                       value="{{ old('payment_date', \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d')) }}" required>
                                @error('payment_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ $school->term('payment_method', 'Méthode de paiement') }}
                                </label>
                                <select name="payment_method" id="payment_method" class="form-select">
                                    <option value="cash" {{ $payment->payment_method == 'cash' ? 'selected' : '' }}>
                                        {{ $school->term('cash', 'Espèces') }}
                                    </option>
                                    <option value="bank" {{ $payment->payment_method == 'bank' ? 'selected' : '' }}>
                                        {{ $school->term('bank', 'Banque') }}
                                    </option>
                                    <option value="mobile" {{ $payment->payment_method == 'mobile' ? 'selected' : '' }}>
                                        {{ $school->term('mobile', 'Mobile Money') }}
                                    </option>
                                    <option value="other" {{ $payment->payment_method == 'other' ? 'selected' : '' }}>
                                        {{ $school->term('other', 'Autre') }}
                                    </option>
                                </select>
                                @error('payment_method')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ $school->term('notes', 'Notes') }}
                                </label>
                                <textarea name="notes" id="notes" rows="3" class="form-textarea">{{ old('notes', $payment->notes) }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg mb-6 mt-4">
                        <div class="flex items-center text-blue-600">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span class="text-sm">{{ $school->term('receipt_number', 'Numéro de reçu') }}: <strong>{{ $payment->receipt_number }}</strong></span>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-1"></i> {{ $school->term('save_changes', 'Enregistrer les modifications') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const studentSelect = document.getElementById('student_id');
        const remainingAmountInfo = document.getElementById('remaining-amount-info');
        const remainingAmountSpan = document.getElementById('remaining-amount');
        
        function updateRemainingAmount() {
            if (studentSelect.value) {
                const selectedOption = studentSelect.options[studentSelect.selectedIndex];
                const remainingAmount = selectedOption.getAttribute('data-remaining');
                const formattedAmount = parseFloat(remainingAmount).toLocaleString('fr-FR');
                
                remainingAmountSpan.textContent = formattedAmount;
                remainingAmountInfo.classList.remove('hidden');
            } else {
                remainingAmountInfo.classList.add('hidden');
            }
        }
        
        // Initial update if student is selected
        updateRemainingAmount();
        
        // Update on student change
        studentSelect.addEventListener('change', updateRemainingAmount);
    });
</script>
@endpush
@endsection 