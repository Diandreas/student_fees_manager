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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne gauche - Informations générales -->
        <div class="lg:col-span-2">
            <!-- Informations générales -->
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
                        
                        <div class="flex justify-end mt-4">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>Enregistrer les informations générales
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Paramètres d'en-tête pour les documents et rapports -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="border-b border-gray-100 p-5">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-file-alt mr-2"></i>En-tête des documents
                    </h5>
                </div>
                <div class="p-5">
                    <p class="text-sm text-gray-600 mb-4">Ces informations apparaîtront sur tous les documents et rapports générés par le système (reçus, listes d'étudiants, rapports, etc.)</p>
                    
                    <form action="{{ route('schools.settings.header', $school) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="header_title" class="block text-sm font-medium text-gray-700 mb-1">Titre principal</label>
                                <input type="text" id="header_title" name="report_settings[header_title]" value="{{ old('report_settings.header_title', $school->report_settings['header_title'] ?? $school->name) }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                <p class="mt-1 text-xs text-gray-500">Affiché en gros caractères en haut des documents</p>
                            </div>

                            <div>
                                <label for="header_subtitle" class="block text-sm font-medium text-gray-700 mb-1">Sous-titre</label>
                                <input type="text" id="header_subtitle" name="report_settings[header_subtitle]" value="{{ old('report_settings.header_subtitle', $school->report_settings['header_subtitle'] ?? '') }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            </div>
                            
                            <div>
                                <label for="header_email" class="block text-sm font-medium text-gray-700 mb-1">Email de contact</label>
                                <input type="email" id="header_email" name="report_settings[header_email]" value="{{ old('report_settings.header_email', $school->report_settings['header_email'] ?? $school->email) }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="header_phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone de contact</label>
                                <input type="tel" id="header_phone" name="report_settings[header_phone]" value="{{ old('report_settings.header_phone', $school->report_settings['header_phone'] ?? $school->phone) }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="header_address" class="block text-sm font-medium text-gray-700 mb-1">Adresse complète</label>
                            <textarea id="header_address" name="report_settings[header_address]" rows="2"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">{{ old('report_settings.header_address', $school->report_settings['header_address'] ?? $school->address) }}</textarea>
                        </div>

                        <div class="mt-4">
                            <label for="header_footer" class="block text-sm font-medium text-gray-700 mb-1">Pied de page des documents</label>
                            <textarea id="header_footer" name="report_settings[header_footer]" rows="2"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">{{ old('report_settings.header_footer', $school->report_settings['header_footer'] ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Par exemple: "Merci de votre confiance" ou informations légales</p>
                        </div>
                        
                        <div class="flex justify-end mt-4">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>Enregistrer l'en-tête des documents
                            </button>
                        </div>
                    </form>
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
                    <form action="{{ route('schools.settings.billing', $school) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
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
                        
                        <div class="flex justify-end mt-4">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>Enregistrer les paramètres de facturation
                            </button>
                        </div>
                    </form>
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

            <!-- Fonctionnalités à venir -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="border-b border-gray-100 p-5">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-clock mr-2"></i>Fonctionnalités à venir
                    </h5>
                </div>
                <div class="p-5">
                    <form action="{{ route('schools.settings.update', $school) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
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
                    </form>
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
                    <form action="{{ route('schools.settings.status', $school) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
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
                        
                        <div class="flex justify-end mt-4">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>Enregistrer le statut
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Administrateurs de l'établissement -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="border-b border-gray-100 p-5">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-users-cog mr-2"></i>Administrateurs
                    </h5>
                </div>
                <div class="p-5">
                    <div class="mb-4 flex justify-between items-center">
                        <p class="text-sm text-gray-600">Personnes ayant accès à cet établissement</p>
                        <a href="{{ route('schools.admins.create', $school) }}" class="btn-sm btn-primary">
                            <i class="fas fa-user-plus mr-1"></i>Ajouter
                        </a>
                    </div>
                    
                    <div class="border rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-2 border-b">
                            <div class="grid grid-cols-3 gap-2">
                                <div class="text-xs font-medium text-gray-500 uppercase">Nom</div>
                                <div class="text-xs font-medium text-gray-500 uppercase">Email</div>
                                <div class="text-xs font-medium text-gray-500 uppercase text-right">Actions</div>
                            </div>
                        </div>
                        
                        <div class="divide-y">
                            @forelse($school->admins as $admin)
                                <div class="px-4 py-3 grid grid-cols-3 gap-2 items-center">
                                    <div class="text-sm font-medium">{{ $admin->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $admin->email }}</div>
                                    <div class="text-right">
                                        <a href="{{ route('schools.admins.edit', [$school, $admin]) }}" class="text-primary-600 hover:text-primary-800">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                    Aucun administrateur trouvé
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection