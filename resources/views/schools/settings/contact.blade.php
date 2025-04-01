@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 flex flex-col sm:flex-row justify-between items-center gap-4">
                <h1 class="text-xl font-bold text-primary-600 flex items-center">
                    <i class="fas fa-address-card mr-2"></i>Modification des informations de contact
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
                <i class="fas fa-address-card mr-2"></i>Informations de contact
            </h5>
        </div>
        <div class="p-5">
            <form action="{{ route('schools.settings.contact', $school) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $school->email) }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $school->phone) }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                        <input type="text" id="address" name="address" value="{{ old('address', $school->address) }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('address') border-red-500 @enderror">
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>Enregistrer les informations de contact
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 