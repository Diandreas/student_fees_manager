@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Payments</h5>
            <a href="{{ route('payments.create') }}" class="btn btn-primary">Record Payment</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Student</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date }}</td>
                            <td>
                                {{ $payment->student->fullName }}<br>
                                <small class="text-muted">{{ $payment->student->field->name }}</small>
                            </td>
                            <td>${{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->description }}</td>
                            <td>
                                <a href="{{ route('payments.print', $payment) }}" class="btn btn-sm btn-secondary">Print</a>
                                <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $payments->links() }}
        </div>
    </div>
@endsection
