@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Fields</h5>
            <a href="{{ route('fields.create') }}" class="btn btn-primary">Add Field</a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Campus</th>
                    <th>Fees</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($fields as $field)
                    <tr>
                        <td>{{ $field->name }}</td>
                        <td>{{ $field->campus->name }}</td>
                        <td>{{ number_format($field->fees, 0, ',', ' ') }} FCFA</td>
                        <td>
                            <a href="{{ route('fields.edit', $field) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('fields.destroy', $field) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $fields->links() }}
        </div>
    </div>
@endsection
