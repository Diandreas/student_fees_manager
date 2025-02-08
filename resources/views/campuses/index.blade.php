@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Campuses</h5>
            <a href="{{ route('campuses.create') }}" class="btn btn-primary">Add Campus</a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Fields Count</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($campuses as $campus)
                    <tr>
                        <td>{{ $campus->name }}</td>
                        <td>{{ Str::limit($campus->description, 50) }}</td>
                        <td>{{ $campus->fields_count }}</td>
                        <td>
                            <a href="{{ route('campuses.edit', $campus) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('campuses.destroy', $campus) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $campuses->links() }}
        </div>
    </div>
@endsection
