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
                    <label for="student_id" class="form-label">Student</label>
                    <select class="form-control @error('student_id') is-invalid @enderror"
                            id="student_id" name="student_id" required>
                        <option value="">Select Student</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}"
                                    data-remaining="{{ $student->remainingAmount }}"
                                {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->fullName }}
                                ({{ $student->field->name }} - Remaining: {{ number_format($student->remainingAmount, 3) }}FCFA)
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

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
            document.getElementById('student_id').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const remainingAmount = selectedOption.dataset.remaining;
                const amountInput = document.getElementById('amount');
                const paymentInfo = document.getElementById('paymentInfo');

                if (this.value) {
                    // Mise à jour des informations de paiement via AJAX
                    fetch(`/payments/student-remaining/${this.value}`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('totalFees').textContent = `$${data.totalFees.toFixed(2)}`;
                            document.getElementById('paidAmount').textContent = `$${data.totalPaid.toFixed(2)}`;
                            document.getElementById('remainingAmount').textContent = `$${data.remainingAmount.toFixed(2)}`;
                            paymentInfo.style.display = 'block';

                            // Mettre à jour le max de l'input amount
                            amountInput.max = data.remainingAmount;
                        });
                } else {
                    paymentInfo.style.display = 'none';
                }
            });

            document.getElementById('amount').addEventListener('input', function() {
                const max = parseFloat(this.max);
                const value = parseFloat(this.value);

                if (value > max) {
                    this.value = max;
                }
            });
        </script>
    @endpush
@endsection
