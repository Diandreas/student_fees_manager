@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Student Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Full Name</dt>
                        <dd class="col-sm-8">{{ $student->fullName }}</dd>

                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $student->email }}</dd>

                        <dt class="col-sm-4">Phone</dt>
                        <dd class="col-sm-8">{{ $student->phone }}</dd>

                        <dt class="col-sm-4">Parent Tel</dt>
                        <dd class="col-sm-8">{{ $student->parent_tel }}</dd>

                        <dt class="col-sm-4">Address</dt>
                        <dd class="col-sm-8">{{ $student->address }}</dd>

                        <dt class="col-sm-4">Field</dt>
                        <dd class="col-sm-8">{{ $student->field->name }}</dd>

                        <dt class="col-sm-4">Campus</dt>
                        <dd class="col-sm-8">{{ $student->field->campus->name }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Payment History</h5>
                    <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" class="btn btn-primary btn-sm">Add Payment</a>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($student->payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date }}</td>
                                <td>${{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->description }}</td>
                                <td>
                                    <a href="{{ route('payments.print', $payment) }}" class="btn btn-sm btn-secondary">Print</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
