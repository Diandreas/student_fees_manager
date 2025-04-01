@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 flex flex-col sm:flex-row justify-between items-center gap-4">
                <h1 class="text-xl font-bold text-primary-600 flex items-center">
                    <i class="fas fa-file-alt mr-2"></i>Modification de l'en-tête des documents
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
</div>
@endsection 