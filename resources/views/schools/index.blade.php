@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h5 class="font-bold text-primary-600 text-xl"><i class="fas fa-school mr-2"></i>Mes écoles</h5>
            <a href="{{ route('schools.create') }}" class="btn-primary">
                <i class="fas fa-plus-circle mr-1"></i> Nouvelle école
            </a>
        </div>

        <div class="mb-6">
            <form action="{{ route('schools.index') }}" method="GET" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                        <input type="text" name="search" id="search" value="{{ request()->search }}" 
                            class="form-input w-full" placeholder="Nom, email, téléphone...">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-search mr-1"></i> Rechercher
                        </button>
                        @if(request()->has('search'))
                            <a href="{{ route('schools.index') }}" class="btn-outline ml-2">
                                <i class="fas fa-times mr-1"></i> Réinitialiser
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse ($schools as $school)
                <div class="card school-card overflow-hidden">
                    <div class="school-header bg-primary-500 text-white p-4 flex justify-between items-center">
                        <h6 class="font-bold text-lg text-white">{{ $school->name }}</h6>
                        <div>
                            @if (session('current_school_id') === $school->id)
                                <span class="inline-block bg-green-500 text-white text-xs px-2 py-1 rounded">
                                    <i class="fas fa-check-circle mr-1"></i> Actif
                                </span>
                            @else
                                <form action="{{ route('schools.select', $school) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-white text-primary-600 text-xs px-2 py-1 rounded hover:bg-primary-100">
                                        <i class="fas fa-sign-in-alt mr-1"></i> Sélectionner
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="text-center bg-blue-50 p-3 rounded">
                                <div class="text-blue-600 text-xl font-bold">{{ $school->students_count ?? 0 }}</div>
                                <div class="text-gray-600 text-sm">Étudiants</div>
                            </div>
                            <div class="text-center bg-green-50 p-3 rounded">
                                <div class="text-green-600 text-xl font-bold">{{ $school->campuses_count ?? 0 }}</div>
                                <div class="text-gray-600 text-sm">Campus</div>
                            </div>
                            <div class="text-center bg-yellow-50 p-3 rounded">
                                <div class="text-yellow-600 text-xl font-bold">{{ $school->fields_count ?? 0 }}</div>
                                <div class="text-gray-600 text-sm">Filières</div>
                            </div>
                            <div class="text-center bg-red-50 p-3 rounded">
                                <div class="text-red-600 text-xl font-bold">{{ $school->users_count ?? 0 }}</div>
                                <div class="text-gray-600 text-sm">Utilisateurs</div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <a href="{{ route('schools.show', $school) }}" class="text-primary-600 hover:text-primary-800">
                                <i class="fas fa-eye mr-1"></i> Détails
                            </a>
                            <div class="flex space-x-1">
                                <a href="{{ route('schools.edit', $school) }}" class="text-gray-600 hover:text-gray-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('schools.destroy', $school) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette école?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3">
                    <div class="card">
                        <div class="card-body text-center py-8">
                            <div class="text-5xl text-gray-300 mb-4">
                                <i class="fas fa-school"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-500 mb-2">Aucune école trouvée</h3>
                            <p class="text-gray-500 mb-4">Vous n'avez pas encore créé d'école.</p>
                            <a href="{{ route('schools.create') }}" class="btn-primary">
                                <i class="fas fa-plus-circle mr-1"></i> Créer une école
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $schools->links() }}
        </div>
    </div>
</div>
@endsection 