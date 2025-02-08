<!-- resources/views/dashboard.blade.php (continued) -->
@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <h2 class="mb-0">{{ $totalStudents }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Payments</h5>
                    <h2 class="mb-0">${{ number_format($totalPayments, 2) }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Outstanding Fees</h5>
                    <h2 class="mb-0">${{ number_format($outstandingFees, 2) }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Fields</h5>
                    <h2 class="mb-0">{{ $totalFields }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Payments</h5>
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
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($recentPayments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date }}</td>
                                    <td>{{ $payment->student->fullName }}</td>
                                    <td>${{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ Str::limit($payment->description, 30) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Payment Statistics</h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentStats" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('paymentStats').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Received', 'Outstanding'],
                datasets: [{
                    data: [{{ $totalPayments }}, {{ $outstandingFees }}],
                    backgroundColor: ['#28a745', '#ffc107']
                }]
            }
        });
    </script>
@endpush
