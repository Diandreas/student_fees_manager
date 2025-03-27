@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="font-medium text-gray-700 text-lg">Modifier l'administrateur pour {{ $school->name }}</h5>
        </div>
        
        <div class="p-6">
            <form action="{{ route('schools.admins.update', ['school' => $school, 'admin' => $admin]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0 h-16 w-16">
                        @if($admin->avatar)
                            <img class="h-16 w-16 rounded-full" src="{{ asset('storage/' . $admin->avatar) }}" alt="{{ $admin->name }}">
                        @else
                            <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center text-xl font-medium text-gray-600">
                                {{ substr($admin->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $admin->name }}</h3>
                        <p class="text-gray-500">{{ $admin->email }}</p>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                    <select name="role" id="role" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('role') border-red-500 @enderror">
                        <option value="admin" {{ (old('role', $admin->pivot->role ?? 'admin') == 'admin') ? 'selected' : '' }}>Administrateur</option>
                        <option value="manager" {{ (old('role', $admin->pivot->role ?? '') == 'manager') ? 'selected' : '' }}>Gestionnaire</option>
                        <option value="finance" {{ (old('role', $admin->pivot->role ?? '') == 'finance') ? 'selected' : '' }}>Finance</option>
                        <option value="secretary" {{ (old('role', $admin->pivot->role ?? '') == 'secretary') ? 'selected' : '' }}>Secrétariat</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h6 class="font-medium text-gray-700 mb-2">Permissions spécifiques</h6>
                    
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="permissions[]" id="manage_students" value="manage_students" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" 
                                {{ in_array('manage_students', old('permissions', $admin->pivot->permissions ?? [])) ? 'checked' : '' }}>
                            <label for="manage_students" class="ml-2 block text-sm text-gray-700">Gestion des étudiants</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="permissions[]" id="manage_fees" value="manage_fees" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                {{ in_array('manage_fees', old('permissions', $admin->pivot->permissions ?? [])) ? 'checked' : '' }}>
                            <label for="manage_fees" class="ml-2 block text-sm text-gray-700">Gestion des frais</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="permissions[]" id="manage_teachers" value="manage_teachers" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                {{ in_array('manage_teachers', old('permissions', $admin->pivot->permissions ?? [])) ? 'checked' : '' }}>
                            <label for="manage_teachers" class="ml-2 block text-sm text-gray-700">Gestion des enseignants</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="permissions[]" id="manage_programs" value="manage_programs" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                {{ in_array('manage_programs', old('permissions', $admin->pivot->permissions ?? [])) ? 'checked' : '' }}>
                            <label for="manage_programs" class="ml-2 block text-sm text-gray-700">Gestion des programmes</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="permissions[]" id="manage_reports" value="manage_reports" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                {{ in_array('manage_reports', old('permissions', $admin->pivot->permissions ?? [])) ? 'checked' : '' }}>
                            <label for="manage_reports" class="ml-2 block text-sm text-gray-700">Accès aux rapports</label>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('schools.show', $school) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">Annuler</a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
@endsection 