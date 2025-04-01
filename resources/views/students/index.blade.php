@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 flex flex-col sm:flex-row justify-between items-center gap-4">
                <h1 class="text-xl font-bold text-primary-600 flex items-center">
                    <i class="fas fa-user-graduate mr-2"></i>{{ session('current_school') ? session('current_school')->term('students') : 'Gestion des étudiants' }}
                </h1>
                <a href="{{ route('students.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center whitespace-nowrap">
                    <i class="fas fa-plus mr-2"></i>Ajouter un {{ session('current_school') ? strtolower(session('current_school')->term('student')) : 'étudiant' }}
                </a>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 p-5">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-3 md:space-y-0">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-list mr-2"></i>Liste des {{ session('current_school') ? strtolower(session('current_school')->term('students')) : 'étudiants' }}
                    </h5>
                    <div class="flex flex-col md:flex-row md:space-x-2 space-y-2 md:space-y-0">
                        <div class="flex space-x-2">
                            <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 {{ !request()->has('payment_status') ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-lg hover:opacity-90 transition-colors">
                                <i class="fas fa-users mr-1"></i> Tous
                            </a>
                            <a href="{{ route('students.index', ['payment_status' => 'fully_paid']) }}" class="inline-flex items-center px-4 py-2 {{ request()->payment_status === 'fully_paid' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-lg hover:opacity-90 transition-colors">
                                <i class="fas fa-check-circle mr-1"></i> En règle
                            </a>
                            <a href="{{ route('students.index', ['payment_status' => 'not_paid']) }}" class="inline-flex items-center px-4 py-2 {{ request()->payment_status === 'not_paid' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-lg hover:opacity-90 transition-colors">
                                <i class="fas fa-exclamation-circle mr-1"></i> Pas en règle
                            </a>
                        </div>
                        <div class="flex">
                            @if(request()->has('payment_status'))
                                <a href="{{ route('students.print', ['payment_status' => request()->payment_status]) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-print mr-1"></i> Imprimer cette liste
                                </a>
                            @else
                                <div class="dropdown relative">
                                    <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" id="printDropdown">
                                        <i class="fas fa-print mr-1"></i> Imprimer
                                        <i class="fas fa-chevron-down ml-1"></i>
                                    </button>
                                    <div class="dropdown-menu hidden absolute right-0 mt-2 py-2 bg-white rounded-lg shadow-lg z-10 min-w-[200px]" id="printMenu">
                                        <a href="{{ route('students.print') }}" class="block px-4 py-2 hover:bg-gray-100 text-gray-700" target="_blank">
                                            <i class="fas fa-users mr-1"></i> Tous les étudiants
                                        </a>
                                        <a href="{{ route('students.print', ['payment_status' => 'fully_paid']) }}" class="block px-4 py-2 hover:bg-gray-100 text-gray-700" target="_blank">
                                            <i class="fas fa-check-circle mr-1"></i> Étudiants en règle
                                        </a>
                                        <a href="{{ route('students.print', ['payment_status' => 'not_paid']) }}" class="block px-4 py-2 hover:bg-gray-100 text-gray-700" target="_blank">
                                            <i class="fas fa-exclamation-circle mr-1"></i> Étudiants pas en règle
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-4 px-6 py-3 border-b border-gray-100">
                <form class="flex flex-wrap gap-2" action="{{ route('students.index') }}" method="GET">
                    @if(request()->has('payment_status'))
                        <input type="hidden" name="payment_status" value="{{ request()->payment_status }}">
                    @endif
                    <div class="flex-grow md:flex-grow-0 relative">
                        <input type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 pr-10" 
                               name="search" value="{{ request()->search }}" 
                               placeholder="Rechercher par nom, email, téléphone...">
                        <button class="absolute inset-y-0 right-0 px-3 flex items-center" type="submit">
                            <i class="fas fa-search text-gray-400"></i>
                        </button>
                    </div>
                    @if(request()->has('search') && !empty(request()->search))
                    <a href="{{ route('students.index', request()->has('payment_status') ? ['payment_status' => request()->payment_status] : []) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                        <i class="fas fa-times mr-1"></i> Effacer
                    </a>
                    @endif
                </form>
            </div>
            
            <div id="studentsContainer">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
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
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-600">{{ $student->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="rounded-full bg-primary-100 flex items-center justify-center mr-3 w-10 h-10 overflow-hidden shadow-sm">
                                            @if($student->photo)
                                                <img src="{{ Storage::url('students/' . $student->photo) }}" class="w-full h-full object-cover" alt="{{ $student->fullName }}">
                                            @else
                                                <span class="font-bold text-primary-600">{{ substr($student->fullName, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800 mb-0">{{ $student->fullName }}</p>
                                            <p class="text-gray-500 text-xs mb-0">inscrit le {{ $student->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $student->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $student->phone ?? 'Non spécifié' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($student->parent_name || $student->parent_tel)
                                        <span class="block font-medium text-gray-800">{{ $student->parent_name ?? 'Parent' }}</span>
                                        <span class="text-xs text-gray-500">{{ $student->parent_tel ?? 'Aucun numéro' }}</span>
                                    @else
                                        <span class="text-gray-500">Non spécifié</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $student->field->name ?? 'Non assigné' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $student->field->campus->name ?? 'Non assigné' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex justify-end space-x-1">
                                        <a href="{{ route('students.show', $student->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-150" title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('students.edit', $student->id) }}" class="p-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-150" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors duration-150" 
                                                onclick="document.getElementById('deleteModal{{ $student->id }}').classList.remove('hidden')" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Modal de suppression -->
                                    <div id="deleteModal{{ $student->id }}" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
                                        <div class="relative bg-white rounded-xl max-w-md w-full mx-auto p-6 shadow-xl">
                                            <div class="flex justify-between items-center mb-4">
                                                <h5 class="text-lg font-bold text-gray-800">Confirmation de suppression</h5>
                                                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" 
                                                        onclick="document.getElementById('deleteModal{{ $student->id }}').classList.add('hidden')">
                                                    <i class="fas fa-times text-xl"></i>
                                                </button>
                                            </div>
                                            <div class="text-left mb-6">
                                                <div class="flex items-center mb-3 text-gray-700">
                                                    <div class="rounded-full bg-red-100 text-red-600 p-3 mr-3">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                    </div>
                                                    <p>Êtes-vous sûr de vouloir supprimer l'{{ session('current_school') ? strtolower(session('current_school')->term('student')) : 'étudiant' }} <strong>{{ $student->fullName }}</strong> ?</p>
                                                </div>
                                                <p class="text-red-600 pl-12"><small>Cette action est irréversible.</small></p>
                                            </div>
                                            <div class="flex justify-end space-x-2">
                                                <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-medium transition-colors duration-150"
                                                        onclick="document.getElementById('deleteModal{{ $student->id }}').classList.add('hidden')">
                                                    Annuler
                                                </button>
                                                <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-150">
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
                                        <div class="rounded-full bg-gray-100 p-6 mb-4">
                                            <i class="fas fa-user-graduate text-5xl text-gray-400"></i>
                                        </div>
                                        <h5 class="text-lg font-bold mb-1 text-gray-800">Aucun {{ session('current_school') ? strtolower(session('current_school')->term('student')) : 'étudiant' }} trouvé</h5>
                                        <p class="text-gray-500 mb-4">
                                            @if(request()->has('search') && !empty(request()->search))
                                                Aucun résultat pour la recherche "{{ request()->search }}"
                                                <br><a href="{{ route('students.index') }}" class="text-primary-600 hover:text-primary-700 hover:underline">Voir tous les {{ session('current_school') ? strtolower(session('current_school')->term('students')) : 'étudiants' }}</a>
                                            @else
                                                Commencez par ajouter un {{ session('current_school') ? strtolower(session('current_school')->term('student')) : 'étudiant' }}
                                            @endif
                                        </p>
                                        @if(!request()->has('search'))
                                        <a href="{{ route('students.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
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
            <div class="border-t border-gray-100 p-5">
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

    <div class="mt-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 p-5">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-3 md:space-y-0">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-list mr-2"></i>Actions
                    </h5>
                    <div class="flex justify-end space-x-2 mt-4">
                        <a href="{{ route('students.print', request()->query()) }}" target="_blank" class="py-2 px-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg flex items-center">
                            <i class="fas fa-print mr-2"></i> Imprimer
                        </a>
                        <a href="{{ route('students.export-excel', request()->query()) }}" class="py-2 px-3 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center">
                            <i class="fas fa-file-excel mr-2"></i> Excel
                        </a>
                        <a href="{{ route('students.export-csv', request()->query()) }}" class="py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center">
                            <i class="fas fa-file-csv mr-2"></i> CSV
                        </a>
                        <a href="{{ route('students.generate-pdf', request()->query()) }}" target="_blank" class="py-2 px-3 bg-red-600 hover:bg-red-700 text-white rounded-lg flex items-center">
                            <i class="fas fa-file-pdf mr-2"></i> PDF Groupé
                        </a>
                    </div>
                </div>
            </div>
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

@push('scripts')
<script>
    // Toggle dropdown
    document.addEventListener('DOMContentLoaded', function() {
        const printDropdown = document.getElementById('printDropdown');
        const printMenu = document.getElementById('printMenu');
        
        if (printDropdown && printMenu) {
            printDropdown.addEventListener('click', function() {
                printMenu.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!printDropdown.contains(event.target) && !printMenu.contains(event.target)) {
                    printMenu.classList.add('hidden');
                }
            });
        }
    });
</script>
@endpush
@endsection
