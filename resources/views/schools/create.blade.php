@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="font-medium text-gray-700 text-lg">Créer une nouvelle école</h5>
        </div>
        
        <div class="p-6">
            <form action="{{ route('schools.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'école</label>
                            <input type="text" name="name" id="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('name') border-red-500 @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                            <input type="file" name="logo" id="logo" class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('logo') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Format recommandé: SVG ou PNG avec fond transparent.</p>
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-1">Couleur primaire</label>
                                <div class="flex items-center">
                                    <input type="color" name="primary_color" id="primary_color" class="h-10 w-10 border-0 p-0 @error('primary_color') border-red-500 @enderror" value="{{ old('primary_color', '#16a34a') }}">
                                    <input type="text" class="ml-2 w-full rounded-md border-gray-300 shadow-sm" value="#16a34a" id="primary_color_text" readonly>
                                </div>
                                @error('primary_color')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-1">Couleur secondaire</label>
                                <div class="flex items-center">
                                    <input type="color" name="secondary_color" id="secondary_color" class="h-10 w-10 border-0 p-0 @error('secondary_color') border-red-500 @enderror" value="{{ old('secondary_color', '#10b981') }}">
                                    <input type="text" class="ml-2 w-full rounded-md border-gray-300 shadow-sm" value="#10b981" id="secondary_color_text" readonly>
                                </div>
                                @error('secondary_color')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="mb-4">
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Email de contact</label>
                            <input type="email" name="contact_email" id="contact_email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('contact_email') border-red-500 @enderror" value="{{ old('contact_email') }}" required>
                            @error('contact_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone de contact</label>
                            <input type="text" name="contact_phone" id="contact_phone" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('contact_phone') border-red-500 @enderror" value="{{ old('contact_phone') }}">
                            @error('contact_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                            <textarea name="address" id="address" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('schools.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">Annuler</a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">Créer l'école</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Script pour mettre à jour les champs textuels des couleurs
        document.addEventListener('DOMContentLoaded', function() {
            const primaryColorInput = document.getElementById('primary_color');
            const primaryColorText = document.getElementById('primary_color_text');
            const secondaryColorInput = document.getElementById('secondary_color');
            const secondaryColorText = document.getElementById('secondary_color_text');
            
            primaryColorInput.addEventListener('input', function() {
                primaryColorText.value = this.value;
            });
            
            secondaryColorInput.addEventListener('input', function() {
                secondaryColorText.value = this.value;
            });
        });
    </script>
@endsection 