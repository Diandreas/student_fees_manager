@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 flex flex-col sm:flex-row justify-between items-center gap-4">
                <h1 class="text-xl font-bold text-primary-600 flex items-center">
                    <i class="fas fa-image mr-2"></i>Modification du logo
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
                <i class="fas fa-image mr-2"></i>Logo
            </h5>
        </div>
        <div class="p-5">
            <div class="flex justify-center mb-4">
                @if($school->logo)
                    <img src="{{ Storage::url($school->logo) }}" alt="Logo" class="h-32 w-32 object-contain border rounded-md">
                @else
                    <div class="h-32 w-32 flex items-center justify-center bg-gray-100 rounded-md">
                        <i class="fas fa-school text-3xl text-gray-400"></i>
                    </div>
                @endif
            </div>
            <form action="{{ route('schools.settings.logo', $school) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Changer le logo</label>
                    <input type="file" id="logo" name="logo" accept="image/*" required
                        class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('logo') border-red-500 @enderror">
                    @error('logo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">PNG ou JPG, 512x512px maximum</p>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-upload mr-2"></i>Mettre à jour le logo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 