@extends('layouts.app')

@section('content')
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">{{ $school->name }}</h1>
        <div class="flex space-x-2">
            @can('update', $school)
                <a href="{{ route('schools.edit', $school) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    <i class="fas fa-edit mr-1"></i> Modifier
                </a>
            @endcan
            
            @if(session('current_school_id') != $school->id)
                <form action="{{ route('schools.switch', $school) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">
                        <i class="fas fa-exchange-alt mr-1"></i> Connecter
                    </button>
                </form>
            @endif
            
            @can('delete', $school)
                <form action="{{ route('schools.destroy', $school) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette école ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        <i class="fas fa-trash-alt mr-1"></i> Supprimer
                    </button>
                </form>
            @endcan
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations générales -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden lg:col-span-2">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h5 class="font-medium text-gray-700">Informations générales</h5>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col md:flex-row">
                    <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6 flex justify-center">
                        @if($school->logo)
                            <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="h-32 w-32 object-contain">
                        @else
                            <div class="h-32 w-32 rounded-full bg-gray-200 flex items-center justify-center text-4xl font-bold text-gray-600">
                                {{ substr($school->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex-grow">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Email de contact</p>
                                <p class="font-medium">{{ $school->contact_email }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Téléphone</p>
                                <p class="font-medium">{{ $school->contact_phone ?? 'Non renseigné' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Adresse</p>
                                <p class="font-medium whitespace-pre-line">{{ $school->address ?? 'Non renseignée' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Couleurs</p>
                                <div class="flex space-x-2 mt-1">
                                    <div class="h-6 w-6 rounded" style="background-color: {{ $school->primary_color }}"></div>
                                    <div class="h-6 w-6 rounded" style="background-color: {{ $school->secondary_color }}"></div>
                                </div>
                            </div>
                        </div>
                        
                        @if($school->description)
                            <div class="mt-4">
                                <p class="text-sm text-gray-500">Description</p>
                                <p class="whitespace-pre-line">{{ $school->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistiques -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="font-medium text-gray-700">Statistiques</h5>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <p class="text-3xl font-bold text-primary-600">{{ $school->campuses_count ?? 0 }}</p>
                        <p class="text-sm text-gray-500">Campus</p>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <p class="text-3xl font-bold text-primary-600">{{ $school->administrators_count ?? 0 }}</p>
                        <p class="text-sm text-gray-500">Administrateurs</p>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <p class="text-3xl font-bold text-primary-600">{{ $school->students_count ?? 0 }}</p>
                        <p class="text-sm text-gray-500">Étudiants</p>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <p class="text-3xl font-bold text-primary-600">{{ $school->teachers_count ?? 0 }}</p>
                        <p class="text-sm text-gray-500">Enseignants</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Liste des campus -->
    <div class="mt-6 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h5 class="font-medium text-gray-700">Campus</h5>
            @can('create', \App\Models\Campus::class)
                <a href="{{ route('campuses.create', ['school_id' => $school->id]) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">
                    <i class="fas fa-plus mr-1"></i> Ajouter un campus
                </a>
            @endcan
        </div>
        
        <div class="p-6">
            @if($school->campuses && $school->campuses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($school->campuses as $campus)
                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                <h6 class="font-medium">{{ $campus->name }}</h6>
                            </div>
                            <div class="p-4">
                                <p class="text-sm text-gray-600"><i class="fas fa-map-marker-alt text-gray-400 mr-2"></i> {{ $campus->address ?? 'Adresse non renseignée' }}</p>
                                <p class="text-sm text-gray-600 mt-1"><i class="fas fa-phone text-gray-400 mr-2"></i> {{ $campus->phone ?? 'Téléphone non renseigné' }}</p>
                                
                                <div class="mt-4 flex justify-end">
                                    <a href="{{ route('campuses.show', $campus) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Voir détails <i class="fas fa-chevron-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 rounded-lg p-6 text-center">
                    <p class="text-gray-600">Aucun campus n'a été ajouté pour cette école.</p>
                    @can('create', \App\Models\Campus::class)
                        <a href="{{ route('campuses.create', ['school_id' => $school->id]) }}" class="mt-2 inline-block px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">
                            <i class="fas fa-plus mr-1"></i> Ajouter un campus
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
    
    <!-- Liste des administrateurs -->
    <div class="mt-6 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h5 class="font-medium text-gray-700">Administrateurs</h5>
            @can('manageAdmins', $school)
                <a href="{{ route('schools.admins.create', $school) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">
                    <i class="fas fa-plus mr-1"></i> Ajouter un administrateur
                </a>
            @endcan
        </div>
        
        <div class="p-6">
            @if($school->administrators && $school->administrators->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                @can('manageAdmins', $school)
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($school->administrators as $admin)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($admin->avatar)
                                                    <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $admin->avatar) }}" alt="{{ $admin->name }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-medium">
                                                        {{ substr($admin->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $admin->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $admin->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $admin->pivot->role ?? 'Administrateur' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($admin->email_verified_at)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Vérifié
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                En attente
                                            </span>
                                        @endif
                                    </td>
                                    @can('manageAdmins', $school)
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('schools.admins.edit', ['school' => $school, 'admin' => $admin]) }}" class="text-blue-600 hover:text-blue-900 mr-3">Modifier</a>
                                            <form action="{{ route('schools.admins.destroy', ['school' => $school, 'admin' => $admin]) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir retirer cet administrateur ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Retirer</button>
                                            </form>
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-gray-50 rounded-lg p-6 text-center">
                    <p class="text-gray-600">Aucun administrateur n'a été ajouté pour cette école.</p>
                    @can('manageAdmins', $school)
                        <a href="{{ route('schools.admins.create', $school) }}" class="mt-2 inline-block px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">
                            <i class="fas fa-plus mr-1"></i> Ajouter un administrateur
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
@endsection 