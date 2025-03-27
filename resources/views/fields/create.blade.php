@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="mb-6">
        <div class="card">
            <div class="card-body flex justify-between items-center">
                <h1 class="text-xl font-bold text-primary-600">
                    <i class="fas fa-graduation-cap mr-2"></i>Ajouter une nouvelle filière
                </h1>
                <a href="{{ route('fields.index') }}" class="flex items-center text-gray-600 hover:text-primary-600">
                    <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <div class="card">
            <div class="card-header">
                <h5 class="font-bold text-primary-600">Informations de la filière</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('fields.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2 md:col-span-1">
                            <div class="mb-4">
                                <label for="name" class="form-label">Nom de la filière <span class="text-red-500">*</span></label>
                                <input type="text" class="form-input @error('name') border-red-500 @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-span-2 md:col-span-1">
                            <div class="mb-4">
                                <label for="campus_id" class="form-label">Campus <span class="text-red-500">*</span></label>
                                <select class="form-select @error('campus_id') border-red-500 @enderror" 
                                        id="campus_id" name="campus_id" required>
                                    <option value="">Sélectionner un campus</option>
                                    @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}" {{ old('campus_id') == $campus->id ? 'selected' : '' }}>
                                        {{ $campus->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('campus_id')
                                <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <div class="mb-4">
                                <label for="education_level_id" class="form-label">Niveau d'études</label>
                                <select class="form-select @error('education_level_id') border-red-500 @enderror" 
                                        id="education_level_id" name="education_level_id">
                                    <option value="">Sélectionner un niveau</option>
                                    @foreach($educationLevels as $level)
                                    <option value="{{ $level->id }}" {{ old('education_level_id') == $level->id ? 'selected' : '' }}>
                                        {{ $level->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('education_level_id')
                                <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <div class="mb-4">
                                <label for="fees" class="form-label">Frais de scolarité (FCFA) <span class="text-red-500">*</span></label>
                                <input type="number" class="form-input @error('fees') border-red-500 @enderror" 
                                       id="fees" name="fees" value="{{ old('fees') }}" required min="0" step="1000">
                                @error('fees')
                                <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <div class="mb-4">
                                <label for="duration" class="form-label">Durée (années) <span class="text-red-500">*</span></label>
                                <input type="number" class="form-input @error('duration') border-red-500 @enderror" 
                                       id="duration" name="duration" value="{{ old('duration', 3) }}" required min="1" max="10">
                                @error('duration')
                                <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <div class="mb-4">
                                <label for="max_installments" class="form-label">Nombre maximal de versements <span class="text-red-500">*</span></label>
                                <input type="number" class="form-input @error('max_installments') border-red-500 @enderror" 
                                       id="max_installments" name="max_installments" value="{{ old('max_installments', 3) }}" required min="1" max="12">
                                @error('max_installments')
                                <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-span-2">
                            <div class="mb-4">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-textarea @error('description') border-red-500 @enderror" 
                                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
