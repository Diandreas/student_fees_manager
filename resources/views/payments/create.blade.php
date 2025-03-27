<!-- resources/views/payments/create.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Record Payment</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('payments.store') }}" method="POST" id="paymentForm">
                @csrf
                <div class="mb-3">
                    <label for="student_id" class="form-label">Étudiant</label>
                    <select id="student_id" name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                        <option value="">Sélectionner un étudiant</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" data-remaining="{{ $student->remainingAmount }}" 
                                {{ (old('student_id') == $student->id || (isset($selectedStudent) && $selectedStudent->id == $student->id)) ? 'selected' : '' }}>
                                {{ $student->full_name }} ({{ $student->field->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if(isset($selectedStudent))
                <div class="card mb-3 student-info-card">
                    <div class="card-body">
                        <h5 class="card-title">Informations de paiement</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Étudiant:</strong> {{ $selectedStudent->full_name }}</p>
                                <p><strong>Filière:</strong> {{ $selectedStudent->field->name }}</p>
                                <p><strong>Campus:</strong> {{ $selectedStudent->field->campus->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Frais totaux:</strong> {{ number_format($selectedStudent->field->fees, 0, ',', ' ') }} FCFA</p>
                                <p><strong>Déjà payé:</strong> {{ number_format($selectedStudent->payments->sum('amount'), 0, ',', ' ') }} FCFA</p>
                                <p class="{{ $selectedStudent->remainingAmount > 0 ? 'text-warning' : 'text-success' }}">
                                    <strong>Reste à payer:</strong> {{ number_format($selectedStudent->remainingAmount, 0, ',', ' ') }} FCFA
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="card mb-3 student-info-card d-none">
                    <div class="card-body">
                        <h5 class="card-title">Informations de paiement</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Étudiant:</strong> <span id="student-name"></span></p>
                                <p><strong>Filière:</strong> <span id="student-field"></span></p>
                                <p><strong>Campus:</strong> <span id="student-campus"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Frais totaux:</strong> <span id="student-fees"></span> FCFA</p>
                                <p><strong>Déjà payé:</strong> <span id="student-paid"></span> FCFA</p>
                                <p id="remaining-amount-text">
                                    <strong>Reste à payer:</strong> <span id="student-remaining"></span> FCFA
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div id="paymentInfo" class="alert alert-info mb-3" style="display: none;">
                    <h6>Payment Information:</h6>
                    <p>Total Fees: <span id="totalFees">$0.00</span></p>
                    <p>Already Paid: <span id="paidAmount">$0.00</span></p>
                    <p>Remaining Amount: <span id="remainingAmount">$0.00</span></p>
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" step="0.01"
                           class="form-control @error('amount') is-invalid @enderror"
                           id="amount" name="amount" value="{{ old('amount') }}" required>
                    @error('amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="payment_date" class="form-label">Payment Date</label>
                    <input type="date" class="form-control @error('payment_date') is-invalid @enderror"
                           id="payment_date" name="payment_date"
                           value="{{ old('payment_date', date('Y-m-d')) }}" required>
                    @error('payment_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" required>{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Record Payment</button>
                <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
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
                                document.querySelector('.student-info-card').classList.remove('d-none');
                                
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
                                    remainingText.className = 'text-warning';
                                } else {
                                    remainingText.className = 'text-success';
                                }
                                
                                // Mettre à jour le montant maximum
                                updateAmountMax(data.remainingAmount);
                                
                                // Afficher le message d'information de paiement
                                const paymentInfo = document.getElementById('paymentInfo');
                                paymentInfo.innerHTML = `Reste à payer : ${formatNumber(data.remainingAmount)} FCFA`;
                                paymentInfo.style.display = 'block';
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    } else {
                        // Masquer la carte d'information
                        document.querySelector('.student-info-card').classList.add('d-none');
                        document.getElementById('paymentInfo').style.display = 'none';
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
