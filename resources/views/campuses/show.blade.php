@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="card">
            <div class="card-body">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">{{ $campus->name }}</h2>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('campuses.index') }}" class="px-4 py-2 border border-gray-300 rounded text-gray-700 bg-white hover:bg-gray-50 flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Retour
                        </a>
                        <a href="{{ route('campuses.edit', $campus) }}" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 flex items-center">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                        <a href="{{ route('fields.create', ['campus_id' => $campus->id]) }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 flex items-center">
                            <i class="fas fa-plus mr-2"></i> Ajouter une filière
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1">
            <div class="card h-full">
                <div class="card-header">
                    <h5 class="font-bold">Informations</h5>
                </div>
                <div class="card-body">
                    <div class="flex flex-col items-center mb-6">
                        <div class="w-24 h-24 rounded-full bg-primary-600 bg-opacity-80 flex items-center justify-center mb-3">
                            <span class="text-4xl text-white">{{ substr($campus->name, 0, 1) }}</span>
                        </div>
                        <h4 class="text-lg font-bold">{{ $campus->name }}</h4>
                        @if($currentSchool)
                            <p class="text-gray-500">{{ $currentSchool->name }}</p>
                        @endif
                    </div>

                    <div class="space-y-2">
                        @if($campus->description)
                            <p class="text-gray-600">{{ $campus->description }}</p>
                        @endif
                        <p><span class="font-semibold">Nombre de filières:</span> {{ $campus->fields->count() }}</p>
                        <p><span class="font-semibold">Total étudiants:</span> {{ $campus->fields->sum('students_count') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:col-span-2">
            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <h5 class="font-bold">Filières</h5>
                    <a href="{{ route('fields.create', ['campus_id' => $campus->id]) }}" class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">
                        <i class="fas fa-plus mr-1"></i> Nouvelle filière
                    </a>
                </div>
                <div class="card-body">
                    @if($groupedFields->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($groupedFields as $fieldName => $fields)
                                <div class="py-3 first:pt-0 last:pb-0">
                                    <div class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 cursor-pointer rounded-md mb-2"
                                         onclick="toggleCollapse('collapse{{ \Illuminate\Support\Str::slug($fieldName) }}')">
                                        <div class="flex items-center">
                                            <span class="font-bold">{{ $fieldName }}</span>
                                            <span class="ml-2 px-2 py-1 text-xs rounded-full bg-primary-100 text-primary-800">{{ $fields->count() }} classe(s)</span>
                                            <span class="ml-2 px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $fields->sum('students_count') }} étudiants</span>
                                        </div>
                                        <i id="icon{{ \Illuminate\Support\Str::slug($fieldName) }}" class="fas fa-chevron-down text-gray-500"></i>
                                    </div>
                                    
                                    <div id="collapse{{ \Illuminate\Support\Str::slug($fieldName) }}" class="hidden">
                                        <div class="divide-y divide-gray-100">
                                            @foreach($fields as $field)
                                                <a href="{{ route('fields.show', $field) }}" class="block p-3 hover:bg-gray-50">
                                                    <div class="flex justify-between items-center">
                                                        <div>
                                                            <h6 class="font-medium text-gray-900">{{ $field->name }}</h6>
                                                            <p class="text-sm text-gray-500">
                                                                @if($field->code)
                                                                    Code: {{ $field->code }} | 
                                                                @endif
                                                                @if(isset($field->education_level) && $field->education_level)
                                                                    Niveau: {{ $field->education_level->name }} | 
                                                                @endif
                                                                Frais: {{ number_format($field->fees, 0, ',', ' ') }} FCFA
                                                            </p>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <span class="px-2 py-1 text-xs rounded-full bg-primary-100 text-primary-800 mr-3">{{ $field->students_count }} étudiants</span>
                                                            <i class="fas fa-chevron-right text-gray-400"></i>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                            <i class="fas fa-school text-5xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 mb-4">Aucune filière n'a été créée pour ce campus.</p>
                            <a href="{{ route('fields.create', ['campus_id' => $campus->id]) }}" class="btn-primary">
                                <i class="fas fa-plus mr-2"></i> Ajouter une filière
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleCollapse(id) {
        const content = document.getElementById(id);
        const iconId = 'icon' + id.substring(8); // Extract the slug part and add 'icon' prefix
        const icon = document.getElementById(iconId);
        
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            content.classList.add('hidden');
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }
</script>
@endpush

@endsection 