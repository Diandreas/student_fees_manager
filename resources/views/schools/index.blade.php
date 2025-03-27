@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h5 class="font-medium text-gray-700 text-lg">Écoles</h5>
            @if(auth()->user()->is_superadmin)
            <a href="{{ route('schools.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Ajouter une école
            </a>
            @endif
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($schools as $school)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                        <div class="h-24 flex items-center justify-center p-4" style="background-color: {{ $school->primary_color }}">
                            @if($school->logo)
                                <img src="{{ $school->logo_url }}" alt="{{ $school->name }}" class="max-h-16 max-w-full object-contain">
                            @else
                                <span class="text-white text-2xl font-bold">{{ strtoupper(substr($school->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-lg">{{ $school->name }}</h3>
                            <p class="text-sm text-gray-600 mb-3">{{ $school->contact_email }}</p>
                            
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                    {{ $school->campuses->count() }} campus
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-100 text-secondary-800">
                                    {{ $school->admins->count() }} administrateurs
                                </span>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-3 flex justify-between items-center">
                                <a href="{{ route('schools.show', $school) }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                    Voir les détails
                                </a>
                                
                                <form action="{{ route('schools.switch', $school) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-primary-600 text-white text-sm px-3 py-1 rounded hover:bg-primary-700 transition">
                                        Connecter
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">Aucune école trouvée</h3>
                        <p class="text-gray-500 mb-4">Vous n'avez pas encore d'école associée à votre compte.</p>
                        
                        @if(auth()->user()->is_superadmin)
                            <a href="{{ route('schools.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Créer votre première école
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection 