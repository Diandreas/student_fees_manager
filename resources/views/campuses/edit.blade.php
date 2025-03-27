@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="card">
            <div class="card-body flex justify-between items-center">
                <h1 class="text-xl font-bold text-primary-600">
                    <i class="fas fa-edit mr-2"></i>Modifier le campus
                </h1>
                <a href="{{ route('campuses.index') }}" class="flex items-center text-gray-600 hover:text-primary-600">
                    <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <div class="card">
            <div class="card-header">
                <h5 class="font-bold text-primary-600">Informations du campus</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('campuses.update', $campus) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div class="mb-4">
                            <label for="name" class="form-label">Nom du campus <span class="text-red-500">*</span></label>
                            <input type="text" class="form-input @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name', $campus->name) }}" required>
                            @error('name')
                            <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-textarea @error('description') border-red-500 @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $campus->description) }}</textarea>
                            @error('description')
                            <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-3">
                        <a href="{{ route('campuses.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            Annuler
                        </a>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
