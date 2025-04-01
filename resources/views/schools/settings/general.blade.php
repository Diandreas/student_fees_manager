@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 flex flex-col sm:flex-row justify-between items-center gap-4">
                <h1 class="text-xl font-bold text-primary-600 flex items-center">
                    <i class="fas fa-cog mr-2"></i>Paramètres généraux
                </h1>
                <a href="{{ route('schools.settings.index', $school) }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Retour aux paramètres
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="border-b border-gray-100 p-5">
            <h5 class="font-bold text-primary-600 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>Informations générales
            </h5>
        </div>
        <div class="p-5">
            <form action="{{ route('schools.settings.general', $school) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'établissement</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $school->name) }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subdomain" class="block text-sm font-medium text-gray-700 mb-1">Sous-domaine</label>
                        <div class="flex rounded-md shadow-sm">
                            <input type="text" id="subdomain" name="subdomain" value="{{ old('subdomain', $school->subdomain) }}"
                                class="block w-full min-w-0 flex-1 rounded-none rounded-l-md border-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            <span class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-3 text-gray-500 sm:text-sm">.gestionetudiants.com</span>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">URL d'accès: https://{{ $school->subdomain }}.gestionetudiants.com</p>
                        @error('subdomain')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description brève</label>
                    <textarea id="description" name="description" rows="2"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">{{ old('description', $school->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-1">Couleur principale</label>
                        <div class="flex items-center">
                            <input type="color" id="primary_color" name="primary_color" value="{{ old('primary_color', $school->primary_color) }}"
                                class="h-8 w-8 border-0 cursor-pointer">
                            <input type="text" id="primary_color_text" name="primary_color" value="{{ old('primary_color', $school->primary_color) }}"
                                class="ml-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Couleur principale utilisée dans l'interface et les rapports</p>
                        @error('primary_color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-1">Couleur secondaire</label>
                        <div class="flex items-center">
                            <input type="color" id="secondary_color" name="secondary_color" value="{{ old('secondary_color', $school->secondary_color) }}"
                                class="h-8 w-8 border-0 cursor-pointer">
                            <input type="text" id="secondary_color_text" name="secondary_color" value="{{ old('secondary_color', $school->secondary_color) }}"
                                class="ml-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Couleur secondaire utilisée dans l'interface et les rapports</p>
                        @error('secondary_color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="flex justify-end mt-4">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>Enregistrer les paramètres généraux
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Synchroniser les champs de couleur
    document.getElementById('primary_color').addEventListener('input', function() {
        document.getElementById('primary_color_text').value = this.value;
    });
    document.getElementById('primary_color_text').addEventListener('input', function() {
        document.getElementById('primary_color').value = this.value;
    });
    document.getElementById('secondary_color').addEventListener('input', function() {
        document.getElementById('secondary_color_text').value = this.value;
    });
    document.getElementById('secondary_color_text').addEventListener('input', function() {
        document.getElementById('secondary_color').value = this.value;
    });
</script>
@endpush

@endsection 