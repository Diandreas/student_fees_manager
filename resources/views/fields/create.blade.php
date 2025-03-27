@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="font-medium text-gray-700 text-lg">Ajouter une filière</h5>
        </div>
        <div class="p-6">
            <form action="{{ route('fields.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                    <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('name') border-red-500 @enderror" 
                        id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="campus_id" class="block text-sm font-medium text-gray-700 mb-1">Campus</label>
                    <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('campus_id') border-red-500 @enderror" 
                        id="campus_id" name="campus_id" required>
                        <option value="">Sélectionner un campus</option>
                        @foreach($campuses as $campus)
                            <option value="{{ $campus->id }}" {{ old('campus_id') == $campus->id ? 'selected' : '' }}>
                                {{ $campus->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('campus_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="fees" class="block text-sm font-medium text-gray-700 mb-1">Frais de scolarité</label>
                    <div class="flex items-center">
                        <input type="number" step="0.01" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('fees') border-red-500 @enderror" 
                            id="fees" name="fees" value="{{ old('fees') }}">
                        <span class="ml-2 text-gray-500">FCFA</span>
                    </div>
                    @error('fees')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('fields.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">Annuler</a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
@endsection
