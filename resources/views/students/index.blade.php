@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Students</h5>
            <a href="{{ route('students.create') }}" class="btn btn-primary">Add Student</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Field</th>
                        <th>Campus</th>
                        <th>Contact</th>
                        <th>Outstanding Fees</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>{{ $student->fullName }}</td>
                            <td>{{ $student->field->name }}</td>
                            <td>{{ $student->field->campus->name }}</td>
                            <td>
                                <small>Email: {{ $student->email }}<br>
                                    Phone: {{ $student->phone }}</small>
                            </td>
                            <td>
                                @php
                                    $totalFees = $student->field->fees;
                                    $paidAmount = $student->payments->sum('amount');
                                    $outstanding = $totalFees - $paidAmount;
                                @endphp

                                {{ number_format($outstanding, 0, ',', ' ') }} FCFA
                            </td>
                            <td>
                                <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline">
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
            {{ $students->links() }}
        </div>
    </div>
@endsection
