<!-- resources/views/payments/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <h5 class="font-bold text-primary-600">
                    <i class="fas fa-money-bill-wave mr-2"></i>{{ session('current_school')->term('new_payment', 'Enregistrer un paiement') }}
                </h5>
                <a href="{{ route('payments.index') }}" class="flex items-center text-gray-600 hover:text-primary-600">
                    <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('payments.store') }}" method="POST" id="paymentForm">
                    @csrf
                    <div class="mb-4">
                        <label for="student_id" class="form-label">{{ session('current_school')->term('student', 'Étudiant') }} <span class="text-red-500">*</span></label>
                        <select id="student_id" name="student_id" class="form-select @error('student_id') border-red-500 @enderror" required>
                            <option value="">{{ session('current_school')->term('select_student', 'Sélectionner un étudiant') }}</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" data-remaining="{{ $student->remainingAmount }}" 
                                    {{ (old('student_id') == $student->id || (isset($selectedStudent) && $selectedStudent->id == $student->id)) ? 'selected' : '' }}>
                                    {{ $student->full_name }} ({{ $student->field->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if(isset($selectedStudent))
                    <div class="card bg-blue-50 border border-blue-200 mb-6 student-info-card">
                        <div class="card-body p-4">
                            <h5 class="font-bold text-gray-700 mb-4">{{ session('current_school')->term('payment_info', 'Informations de paiement') }}</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('student', 'Étudiant') }}:</span> {{ $selectedStudent->full_name }}</p>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('field', 'Filière') }}:</span> {{ $selectedStudent->field->name }}</p>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('campus', 'Campus') }}:</span> {{ $selectedStudent->field->campus->name }}</p>
                                </div>
                                <div>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('total_fees', 'Frais totaux') }}:</span> {{ number_format($selectedStudent->field->fees, 0, ',', ' ') }} {{ session('current_school')->term('currency', 'FCFA') }}</p>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('already_paid', 'Déjà payé') }}:</span> {{ number_format($selectedStudent->payments->sum('amount'), 0, ',', ' ') }} {{ session('current_school')->term('currency', 'FCFA') }}</p>
                                    <p class="{{ $selectedStudent->remainingAmount > 0 ? 'text-yellow-600' : 'text-green-600' }} mb-2">
                                        <span class="font-semibold">{{ session('current_school')->term('remaining', 'Reste à payer') }}:</span> {{ number_format($selectedStudent->remainingAmount, 0, ',', ' ') }} {{ session('current_school')->term('currency', 'FCFA') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="card bg-blue-50 border border-blue-200 mb-6 student-info-card hidden">
                        <div class="card-body p-4">
                            <h5 class="font-bold text-gray-700 mb-4">{{ session('current_school')->term('payment_info', 'Informations de paiement') }}</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('student', 'Étudiant') }}:</span> <span id="student-name"></span></p>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('field', 'Filière') }}:</span> <span id="student-field"></span></p>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('campus', 'Campus') }}:</span> <span id="student-campus"></span></p>
                                </div>
                                <div>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('total_fees', 'Frais totaux') }}:</span> <span id="student-fees"></span> {{ session('current_school')->term('currency', 'FCFA') }}</p>
                                    <p class="mb-2"><span class="font-semibold">{{ session('current_school')->term('already_paid', 'Déjà payé') }}:</span> <span id="student-paid"></span> {{ session('current_school')->term('currency', 'FCFA') }}</p>
                                    <p id="remaining-amount-text" class="mb-2">
                                        <span class="font-semibold">{{ session('current_school')->term('remaining', 'Reste à payer') }}:</span> <span id="student-remaining"></span> {{ session('current_school')->term('currency', 'FCFA') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div id="paymentInfo" class="bg-blue-100 text-blue-800 p-4 rounded mb-6 hidden"></div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <label for="amount" class="form-label">{{ session('current_school')->term('amount', 'Montant') }} <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" step="0.01"
                                    class="form-input @error('amount') border-red-500 @enderror"
                                    id="amount" name="amount" value="{{ old('amount') }}" required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">{{ session('current_school')->term('currency', 'FCFA') }}</span>
                                </div>
                            </div>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="payment_date" class="form-label">{{ session('current_school')->term('payment_date', 'Date de paiement') }} <span class="text-red-500">*</span></label>
                            <input type="date" class="form-input @error('payment_date') border-red-500 @enderror"
                                id="payment_date" name="payment_date"
                                value="{{ old('payment_date', date('Y-m-d')) }}" required>
                            @error('payment_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="payment_method" class="form-label">{{ session('current_school')->term('payment_method', 'Méthode de paiement') }} <span class="text-red-500">*</span></label>
                        <select class="form-select @error('payment_method') border-red-500 @enderror" id="payment_method" name="payment_method" required>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>{{ session('current_school')->term('cash', 'Espèces') }}</option>
                            <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : '' }}>{{ session('current_school')->term('bank', 'Banque') }}</option>
                            <option value="mobile" {{ old('payment_method') == 'mobile' ? 'selected' : '' }}>{{ session('current_school')->term('mobile_money', 'Mobile Money') }}</option>
                            <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>{{ session('current_school')->term('other', 'Autre') }}</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">{{ session('current_school')->term('description', 'Description') }}</label>
                        <textarea class="form-textarea @error('description') border-red-500 @enderror"
                                id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('payments.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            {{ session('current_school')->term('cancel', 'Annuler') }}
                        </a>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>{{ session('current_school')->term('save', 'Enregistrer') }}
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
            const studentInfoCard = document.querySelector('.student-info-card');
            
            // Fonction pour formater des nombres avec des séparateurs
            function formatNumber(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
            }
            
            // Fonction pour mettre à jour l'affichage du montant maximum
            function updateAmountMax(remainingAmount) {
                const amountInput = document.getElementById('amount');
                if (amountInput) {
                    amountInput.setAttribute('max', remainingAmount);
                    
                    // Si le montant actuel est supérieur au restant, le mettre à jour
                    if (parseFloat(amountInput.value) > remainingAmount) {
                        amountInput.value = remainingAmount;
                    }
                }
            }
            
            // Handler pour le changement d'étudiant
            studentSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                if (this.value) {
                    // Récupérer les informations de paiement de l'étudiant
                    fetch(`{{ url('/payments/student-remaining') }}/${this.value}`)
                        .then(response => response.json())
                        .then(data => {
                            // Afficher la carte d'information
                            document.querySelector('.student-info-card').classList.remove('hidden');
                            
                            // Mettre à jour les informations
                            document.getElementById('student-name').textContent = data.student.full_name;
                            document.getElementById('student-field').textContent = data.student.field.name;
                            document.getElementById('student-campus').textContent = data.student.field.campus.name;
                            document.getElementById('student-fees').textContent = formatNumber(data.totalFees);
                            document.getElementById('student-paid').textContent = formatNumber(data.totalPaid);
                            document.getElementById('student-remaining').textContent = formatNumber(data.remainingAmount);
                            
                            // Mettre à jour la classe pour la couleur du texte
                            const remainingText = document.getElementById('remaining-amount-text');
                            if (data.remainingAmount > 0) {
                                remainingText.className = 'text-yellow-600 mb-2';
                            } else {
                                remainingText.className = 'text-green-600 mb-2';
                            }
                            
                            // Mettre à jour le montant maximum
                            updateAmountMax(data.remainingAmount);
                            
                            // Désactiver le bouton de soumission si la pension est déjà soldée
                            const submitButton = document.querySelector('button[type="submit"]');
                            if (data.remainingAmount <= 0) {
                                submitButton.disabled = true;
                                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                                document.getElementById('paymentInfo').innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>La pension de cet étudiant est déjà soldée. Impossible d\'effectuer un paiement.';
                                document.getElementById('paymentInfo').classList.remove('hidden', 'bg-blue-100', 'text-blue-800');
                                document.getElementById('paymentInfo').classList.add('bg-red-100', 'text-red-800');
                                document.getElementById('amount').disabled = true;
                            } else {
                                submitButton.disabled = false;
                                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                                document.getElementById('paymentInfo').innerHTML = `<i class="fas fa-info-circle mr-2"></i>Reste à payer : ${formatNumber(data.remainingAmount)} {{ session('current_school')->term('currency', 'FCFA') }}`;
                                document.getElementById('paymentInfo').classList.remove('hidden', 'bg-red-100', 'text-red-800');
                                document.getElementById('paymentInfo').classList.add('bg-blue-100', 'text-blue-800');
                                document.getElementById('amount').disabled = false;
                            }
                            
                            // Afficher le message d'information de paiement
                            const paymentInfo = document.getElementById('paymentInfo');
                            if (data.remainingAmount > 0) {
                                paymentInfo.innerHTML = `<i class="fas fa-info-circle mr-2"></i>Reste à payer : ${formatNumber(data.remainingAmount)} {{ session('current_school')->term('currency', 'FCFA') }}`;
                            }
                            paymentInfo.classList.remove('hidden');
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                } else {
                    // Masquer la carte d'information
                    document.querySelector('.student-info-card').classList.add('hidden');
                    document.getElementById('paymentInfo').classList.add('hidden');
                }
            });
            
            // Exécuter le changement si un étudiant est déjà sélectionné
            if (studentSelect.value) {
                studentSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endpush
@endsection
