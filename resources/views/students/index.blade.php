@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="mb-6">
        <div class="card">
            <div class="card-body flex justify-between items-center">
                <h1 class="text-xl font-bold text-primary-600">
                    <i class="fas fa-user-graduate mr-2"></i>{{ session('current_school') ? session('current_school')->term('students') : 'Gestion des étudiants' }}
                </h1>
                <a href="{{ route('students.create') }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>Ajouter un {{ session('current_school') ? strtolower(session('current_school')->term('student')) : 'étudiant' }}
                </a>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <div class="card">
            <div class="card-header">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-3 md:space-y-0">
                    <h5 class="font-bold text-primary-600">
                        Liste des {{ session('current_school') ? strtolower(session('current_school')->term('students')) : 'étudiants' }}
                    </h5>
                    <form class="flex" action="{{ route('students.index') }}" method="GET">
                        <div class="flex w-full md:w-auto">
                            <input type="text" class="form-input rounded-r-none" name="search" value="{{ request()->search }}" placeholder="Rechercher par nom, email, téléphone ou filière...">
                            <button class="bg-primary-600 hover:bg-primary-700 text-white px-3 rounded-l-none rounded-r-md" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            @if(request()->has('search') && !empty(request()->search))
                            <a href="{{ route('students.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 rounded-md ml-1">
                                <i class="fas fa-times"></i>
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            
            <div id="studentsContainer">
                <!-- Mode Liste uniquement -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom complet</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ session('current_school') ? session('current_school')->term('field') : 'Filière' }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ session('current_school') ? session('current_school')->term('campus') : 'Campus' }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $student->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="rounded-full bg-primary-100 flex items-center justify-center mr-3 w-10 h-10 overflow-hidden">
                                            @if($student->photo)
                                                <img src="{{ Storage::url('students/' . $student->photo) }}" class="w-full h-full object-cover" alt="{{ $student->fullName }}">
                                            @else
                                                <span class="font-bold text-primary-600">{{ substr($student->fullName, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-bold mb-0">{{ $student->fullName }}</p>
                                            <p class="text-gray-500 text-sm mb-0">inscrit le {{ $student->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $student->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $student->phone ?? 'Non spécifié' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($student->parent_name || $student->parent_tel)
                                        <span class="block font-medium">{{ $student->parent_name ?? 'Parent' }}</span>
                                        <span class="text-sm text-gray-500">{{ $student->parent_tel ?? 'Aucun numéro' }}</span>
                                    @else
                                        <span class="text-gray-500">Non spécifié</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="badge-success">
                                        {{ $student->field->name ?? 'Non assigné' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $student->field->campus->name ?? 'Non assigné' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex justify-end space-x-1">
                                        <a href="{{ route('students.show', $student->id) }}" class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('students.edit', $student->id) }}" class="px-2 py-1 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="px-2 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200" 
                                                onclick="document.getElementById('deleteModal{{ $student->id }}').classList.remove('hidden')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Modal de suppression -->
                                    <div id="deleteModal{{ $student->id }}" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
                                        <div class="relative bg-white rounded-lg max-w-md w-full mx-auto p-6">
                                            <div class="flex justify-between items-center mb-4">
                                                <h5 class="text-lg font-bold">Confirmation de suppression</h5>
                                                <button type="button" class="text-gray-400 hover:text-gray-600" 
                                                        onclick="document.getElementById('deleteModal{{ $student->id }}').classList.add('hidden')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            <div class="text-left">
                                                <p>Êtes-vous sûr de vouloir supprimer l'{{ session('current_school') ? strtolower(session('current_school')->term('student')) : 'étudiant' }} <strong>{{ $student->fullName }}</strong> ?</p>
                                                <p class="text-red-600 my-2"><small>Cette action est irréversible.</small></p>
                                            </div>
                                            <div class="flex justify-end space-x-2 mt-4">
                                                <button type="button" class="btn-secondary"
                                                        onclick="document.getElementById('deleteModal{{ $student->id }}').classList.add('hidden')">
                                                    Annuler
                                                </button>
                                                <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-user-graduate text-5xl text-gray-400 mb-4"></i>
                                        <h5 class="text-lg font-bold mb-1">Aucun {{ session('current_school') ? strtolower(session('current_school')->term('student')) : 'étudiant' }} trouvé</h5>
                                        <p class="text-gray-500 mb-4">
                                            @if(request()->has('search') && !empty(request()->search))
                                                Aucun résultat pour la recherche "{{ request()->search }}"
                                                <br><a href="{{ route('students.index') }}" class="text-primary-600 hover:underline">Voir tous les {{ session('current_school') ? strtolower(session('current_school')->term('students')) : 'étudiants' }}</a>
                                            @else
                                                Commencez par ajouter un {{ session('current_school') ? strtolower(session('current_school')->term('student')) : 'étudiant' }}
                                            @endif
                                        </p>
                                        @if(!request()->has('search'))
                                        <a href="{{ route('students.create') }}" class="btn-primary">
                                            <i class="fas fa-plus mr-2"></i>Ajouter un {{ session('current_school') ? strtolower(session('current_school')->term('student')) : 'étudiant' }}
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($students->count() > 0)
            <div class="card-footer">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0">
                    <div>
                        <p class="text-gray-500 mb-0">Affichage de <span class="font-medium">{{ $students->firstItem() ?? 0 }}</span> à <span class="font-medium">{{ $students->lastItem() ?? 0 }}</span> sur <span class="font-medium">{{ $students->total() }}</span> {{ session('current_school') ? strtolower(session('current_school')->term('students')) : 'étudiants' }}</p>
                    </div>
                    <div class="pagination-container">
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .pagination-container nav div:first-child {
        @apply hidden;
    }
    
    .pagination-container nav > div:last-child > span,
    .pagination-container nav > div:last-child a {
        @apply inline-flex items-center px-4 py-2 mx-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50;
    }
    
    .pagination-container nav > div:last-child > span.text-gray-400 {
        @apply text-gray-400 bg-gray-100;
    }
    
    .pagination-container nav > div:last-child > span.text-white {
        @apply bg-primary-600 text-white border-primary-600;
    }
</style>
@endpush
@endsection
