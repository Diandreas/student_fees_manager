@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 flex flex-col sm:flex-row justify-between items-center gap-4">
                <h1 class="text-xl font-bold text-primary-600 flex items-center">
                    <i class="fas fa-cogs mr-2"></i>Paramètres de l'établissement
                </h1>
                <a href="{{ route('schools.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('schools.settings.update', $school) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Colonne gauche - Informations générales -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                    <div class="border-b border-gray-100 p-5">
                        <h5 class="font-bold text-primary-600 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>Informations générales
                        </h5>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'établissement <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name', $school->name) }}" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

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

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                                <input type="text" id="address" name="address" value="{{ old('address', $school->address) }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('address') border-red-500 @enderror">
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description de l'établissement</label>
                            <textarea id="description" name="description" rows="3"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description', $school->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Paramètres de facturation -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                    <div class="border-b border-gray-100 p-5">
                        <h5 class="font-bold text-primary-600 flex items-center">
                            <i class="fas fa-file-invoice-dollar mr-2"></i>Facturation
                        </h5>
                    </div>
                    <div class="p-5">
                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">Devise <span class="text-red-500">*</span></label>
                            <select id="currency" name="currency" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('currency') border-red-500 @enderror">
                                <option value="XAF" {{ old('currency', $school->currency) == 'XAF' ? 'selected' : '' }}>XAF (Franc CFA)</option>
                                <option value="EUR" {{ old('currency', $school->currency) == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                                <option value="USD" {{ old('currency', $school->currency) == 'USD' ? 'selected' : '' }}>USD (Dollar)</option>
                            </select>
                            @error('currency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite -->
            <div>
                <!-- Logo -->
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
                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Changer le logo</label>
                            <input type="file" id="logo" name="logo" accept="image/*"
                                class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('logo') border-red-500 @enderror">
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">PNG ou JPG, 512x512px maximum</p>
                        </div>
                    </div>
                </div>

                <!-- Fonctionnalités à venir -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                    <div class="border-b border-gray-100 p-5">
                        <h5 class="font-bold text-primary-600 flex items-center">
                            <i class="fas fa-clock mr-2"></i>Fonctionnalités à venir
                        </h5>
                    </div>
                    <div class="p-5">
                        <div class="space-y-4">
                            <div class="flex items-start opacity-60">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" id="has_email_notifications" name="has_email_notifications" {{ old('has_email_notifications', $school->has_email_notifications) ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500" disabled>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="has_email_notifications" class="font-medium text-gray-700">Notifications par email</label>
                                    <p class="text-gray-500">Bientôt disponible</p>
                                </div>
                            </div>

                            <div class="flex items-start opacity-60">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" id="has_sms_notifications" name="has_sms_notifications" {{ old('has_sms_notifications', $school->has_sms_notifications) ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500" disabled>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="has_sms_notifications" class="font-medium text-gray-700">Notifications par SMS</label>
                                    <p class="text-gray-500">Bientôt disponible</p>
                                </div>
                            </div>

                            <div class="flex items-start opacity-60">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" id="has_online_payments" name="has_online_payments" {{ old('has_online_payments', $school->has_online_payments) ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500" disabled>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="has_online_payments" class="font-medium text-gray-700">Paiements en ligne</label>
                                    <p class="text-gray-500">Bientôt disponible</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statut de l'établissement -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                    <div class="border-b border-gray-100 p-5">
                        <h5 class="font-bold text-primary-600 flex items-center">
                            <i class="fas fa-toggle-on mr-2"></i>Statut
                        </h5>
                    </div>
                    <div class="p-5">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" id="is_active" name="is_active" {{ old('is_active', $school->is_active) ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_active" class="font-medium text-gray-700">Établissement actif</label>
                                <p class="text-gray-500">Activer/désactiver cet établissement</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end mt-6">
            <button type="reset" class="btn-secondary mr-3">
                <i class="fas fa-undo mr-2"></i>Annuler
            </button>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save mr-2"></i>Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection 