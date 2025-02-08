@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Student Fee Status Report</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('reports.students') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="field_id" class="form-label">Field</label>
                            <select class="form-control" id="field_id" name="field_id">
                                <option value="">All Fields</option>
                                @foreach($fields as $field)
                                    <option value="{{ $field->id }}" {{ request('field_id') == $field->id ? 'selected' : '' }}>
                                        {{ $field->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="status" class="form-label">Payment Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Fully Paid</option>
                                <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partially Paid</option>
                                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">Generate Report</button>
                                <a href="{{ route('reports.students.pdf') }}?{{ http_build_query(request()->all()) }}"
                                   class="btn btn-secondary">Export PDF</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Student</th>
                        <th>Field</th>
                        <th>Total Fees</th>
                        <th>Paid Amount</th>
                        <th>Outstanding</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $student)
                        @php
                            $totalFees = $student->field->fees;
                            $paidAmount = $student->payments->sum('amount');
                            $outstanding = $totalFees - $paidAmount;
                            $status = $outstanding <= 0 ? 'Paid' : ($paidAmount > 0 ? 'Partial' : 'Unpaid');
                            $statusClass = $status === 'Paid' ? 'text-success' :
                                         ($status === 'Partial' ? 'text-warning' : 'text-danger');
                        @endphp
                        <tr>
                            <td>{{ $student->fullName }}</td>
                            <td>{{ $student->field->name }}</td>
                            <td>${{ number_format($totalFees, 2) }}</td>
                            <td>${{ number_format($paidAmount, 2) }}</td>
                            <td>${{ number_format($outstanding, 2) }}</td>
                            <td class="{{ $statusClass }}">{{ $status }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
