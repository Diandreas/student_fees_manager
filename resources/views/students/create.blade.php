@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5">
                <h1 class="text-xl font-bold text-primary-600 flex items-center">
                    <i class="fas fa-user-plus mr-2"></i>Ajouter un nouvel étudiant
                </h1>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 p-5">
            <h5 class="font-bold text-gray-700 flex items-center">
                <i class="fas fa-info-circle mr-2 text-primary-500"></i>Informations de l'étudiant
            </h5>
        </div>
        <div class="p-5">
            <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Informations personnelles -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">
                        <h6 class="font-bold text-primary-600 flex items-center mb-4">
                            <i class="fas fa-id-card mr-2"></i>Informations personnelles
                        </h6>
                        
                        <div class="mb-6 text-center">
                            <div class="relative inline-block">
                                <div id="photo-preview" class="w-36 h-36 rounded-full bg-primary-100 flex items-center justify-center mb-3 mx-auto overflow-hidden shadow-md border-4 border-white">
                                    <span class="text-4xl font-bold text-primary-600" id="initials">?</span>
                                </div>
                                <label for="photo" class="absolute bottom-0 right-0 bg-primary-600 text-white w-9 h-9 rounded-full flex items-center justify-center cursor-pointer hover:bg-primary-700 transition-colors duration-200 shadow-md">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" class="hidden" id="photo" name="photo" accept="image/*">
                                @error('photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Cliquez sur l'icône pour ajouter une photo</p>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="fullName" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nom complet <span class="text-red-500">*</span>
                                </label>
                                <input type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('fullName') border-red-500 @enderror" 
                                       id="fullName" name="fullName" value="{{ old('fullName') }}" required>
                                @error('fullName')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('email') border-red-500 @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                                    Adresse <span class="text-red-500">*</span>
                                </label>
                                <textarea class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('address') border-red-500 @enderror" 
                                          id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Téléphone
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone-alt text-gray-400"></i>
                                    </div>
                                    <input type="text" class="w-full rounded-lg border-gray-300 pl-10 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('phone') border-red-500 @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}">
                                </div>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informations académiques et parents -->
                    <div class="space-y-6">
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">
                            <h6 class="font-bold text-primary-600 flex items-center mb-4">
                                <i class="fas fa-graduation-cap mr-2"></i>Informations académiques
                            </h6>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="field_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        Filière <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-bookmark text-gray-400"></i>
                                        </div>
                                        @if ($selectedField)
                                            <input type="hidden" name="field_id" value="{{ $selectedField->id }}">
                                            <input type="text" class="w-full rounded-lg border-gray-300 pl-10 shadow-sm bg-gray-100 cursor-not-allowed" 
                                                value="{{ $selectedField->name }} ({{ $selectedField->campus->name }})" readonly>
                                            <p class="mt-1 text-sm text-primary-600 flex items-center">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Cette filière est présélectionnée et ne peut pas être modifiée.
                                            </p>
                                        @else
                                            <select class="w-full rounded-lg border-gray-300 pl-10 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('field_id') border-red-500 @enderror" 
                                                    id="field_id" name="field_id" required>
                                                <option value="">-- Sélectionner une filière --</option>
                                                @foreach($fields as $field)
                                                    <option value="{{ $field->id }}" {{ old('field_id') == $field->id ? 'selected' : '' }}>
                                                        {{ $field->name }} ({{ $field->campus->name }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    @error('field_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Frais de scolarité
                                    </label>
                                    <div class="flex">
                                        <div class="relative flex-grow">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-money-bill text-gray-400"></i>
                                            </div>
                                            <input type="text" class="w-full rounded-l-lg border-gray-300 pl-10 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50" id="fees_display" readonly>
                                        </div>
                                        <span class="inline-flex items-center px-4 rounded-r-lg border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm font-medium">
                                            FCFA
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500 flex items-center">
                                        <i class="fas fa-info-circle mr-1 text-primary-400"></i>
                                        Le montant des frais de scolarité est défini par la filière sélectionnée.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">
                            <h6 class="font-bold text-primary-600 flex items-center mb-4">
                                <i class="fas fa-users mr-2"></i>Informations des parents
                            </h6>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="parent_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nom du parent/tuteur
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input type="text" class="w-full rounded-lg border-gray-300 pl-10 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('parent_name') border-red-500 @enderror" 
                                               id="parent_name" name="parent_name" value="{{ old('parent_name') }}">
                                    </div>
                                    @error('parent_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="parent_tel" class="block text-sm font-medium text-gray-700 mb-1">
                                            Téléphone parent
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-phone-alt text-gray-400"></i>
                                            </div>
                                            <input type="text" class="w-full rounded-lg border-gray-300 pl-10 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('parent_tel') border-red-500 @enderror" 
                                                   id="parent_tel" name="parent_tel" value="{{ old('parent_tel') }}">
                                        </div>
                                        @error('parent_tel')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="parent_email" class="block text-sm font-medium text-gray-700 mb-1">
                                            Email parent
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-envelope text-gray-400"></i>
                                            </div>
                                            <input type="email" class="w-full rounded-lg border-gray-300 pl-10 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('parent_email') border-red-500 @enderror" 
                                                   id="parent_email" name="parent_email" value="{{ old('parent_email') }}">
                                        </div>
                                        @error('parent_email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="parent_profession" class="block text-sm font-medium text-gray-700 mb-1">
                                        Profession du parent
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-briefcase text-gray-400"></i>
                                        </div>
                                        <input type="text" class="w-full rounded-lg border-gray-300 pl-10 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('parent_profession') border-red-500 @enderror" 
                                               id="parent_profession" name="parent_profession" value="{{ old('parent_profession') }}">
                                    </div>
                                    @error('parent_profession')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="parent_address" class="block text-sm font-medium text-gray-700 mb-1">
                                        Adresse du parent
                                    </label>
                                    <div class="relative">
                                        <div class="absolute top-3 left-3 pointer-events-none">
                                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                                        </div>
                                        <textarea class="w-full rounded-lg border-gray-300 pl-10 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('parent_address') border-red-500 @enderror" 
                                                id="parent_address" name="parent_address" rows="2">{{ old('parent_address') }}</textarea>
                                    </div>
                                    @error('parent_address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contact d'urgence -->
                <div class="mt-6">
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">
                        <h6 class="font-bold text-primary-600 flex items-center mb-4">
                            <i class="fas fa-ambulance mr-2"></i>Contact d'urgence
                        </h6>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nom du contact d'urgence
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user-shield text-gray-400"></i>
                                    </div>
                                    <input type="text" class="w-full rounded-lg border-gray-300 pl-10 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('emergency_contact_name') border-red-500 @enderror" 
                                           id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}">
                                </div>
                                @error('emergency_contact_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="emergency_contact_tel" class="block text-sm font-medium text-gray-700 mb-1">
                                    Téléphone du contact d'urgence
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone-alt text-gray-400"></i>
                                    </div>
                                    <input type="text" class="w-full rounded-lg border-gray-300 pl-10 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('emergency_contact_tel') border-red-500 @enderror" 
                                           id="emergency_contact_tel" name="emergency_contact_tel" value="{{ old('emergency_contact_tel') }}">
                                </div>
                                @error('emergency_contact_tel')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="relationship" class="block text-sm font-medium text-gray-700 mb-1">
                                    Relation avec l'étudiant
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user-friends text-gray-400"></i>
                                    </div>
                                    <select class="w-full rounded-lg border-gray-300 pl-10 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('relationship') border-red-500 @enderror" 
                                            id="relationship" name="relationship">
                                        <option value="">-- Sélectionner --</option>
                                        <option value="Père" {{ old('relationship') == 'Père' ? 'selected' : '' }}>Père</option>
                                        <option value="Mère" {{ old('relationship') == 'Mère' ? 'selected' : '' }}>Mère</option>
                                        <option value="Frère/Soeur" {{ old('relationship') == 'Frère/Soeur' ? 'selected' : '' }}>Frère/Soeur</option>
                                        <option value="Oncle/Tante" {{ old('relationship') == 'Oncle/Tante' ? 'selected' : '' }}>Oncle/Tante</option>
                                        <option value="Grand-parent" {{ old('relationship') == 'Grand-parent' ? 'selected' : '' }}>Grand-parent</option>
                                        <option value="Autre" {{ old('relationship') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                </div>
                                @error('relationship')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-between mt-6">
                    <a href="{{ route('students.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors duration-200 shadow-sm flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>Retour à la liste
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm flex items-center gap-2">
                        <i class="fas fa-save"></i>Enregistrer l'étudiant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fieldSelect = document.getElementById('field_id');
    const feesDisplay = document.getElementById('fees_display');
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photo-preview');
    const initialsElement = document.getElementById('initials');
    const fullNameInput = document.getElementById('fullName');
    
    // Données des filières (frais)
    const fieldsData = {
        @foreach($fields as $field)
            {{ $field->id }}: {{ $field->fees }},
        @endforeach
    };
    
    // Pour une filière présélectionnée
    @if($selectedField)
        feesDisplay.value = new Intl.NumberFormat('fr-FR').format({{ $selectedField->fees }});
    @else
        // Mettre à jour les frais lors du changement de filière
        if (fieldSelect) {
            fieldSelect.addEventListener('change', function() {
                const selectedFieldId = this.value;
                const fees = fieldsData[selectedFieldId] || 0;
                feesDisplay.value = new Intl.NumberFormat('fr-FR').format(fees);
            });
            
            // Déclencher l'événement change pour afficher les frais initiaux
            fieldSelect.dispatchEvent(new Event('change'));
        }
    @endif
    
    // Prévisualisation de la photo
    photoInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    // Mettre à jour les initiales lors de la saisie du nom
    fullNameInput.addEventListener('input', function() {
        if (!photoInput.files.length) {
            const initials = this.value.trim().split(' ').map(word => word[0]).join('').toUpperCase();
            initialsElement.textContent = initials || '?';
        }
    });
});
</script>
@endpush
@endsection
