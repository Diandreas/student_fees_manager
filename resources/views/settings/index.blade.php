@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 flex flex-col sm:flex-row justify-between items-center gap-4">
                <h1 class="text-xl font-bold text-primary-600 flex items-center">
                    <i class="fas fa-cogs mr-2"></i>Paramètres de l'application
                </h1>
                <a href="{{ route('dashboard') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Menu de navigation -->
        <div>
            <div class="bg-white rounded-xl shadow-sm overflow-hidden sticky top-6">
                <nav class="flex flex-col">
                    <a href="#appearance" class="flex items-center gap-3 p-4 border-l-4 border-primary-500 bg-primary-50 text-primary-700 font-medium">
                        <i class="fas fa-palette w-5 text-center"></i>
                        <span>Apparence</span>
                    </a>
                    <a href="#general" class="flex items-center gap-3 p-4 border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300">
                        <i class="fas fa-sliders-h w-5 text-center"></i>
                        <span>Général</span>
                    </a>
                    <a href="#notifications" class="flex items-center gap-3 p-4 border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300">
                        <i class="fas fa-bell w-5 text-center"></i>
                        <span>Notifications</span>
                    </a>
                    <a href="#export" class="flex items-center gap-3 p-4 border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300">
                        <i class="fas fa-file-export w-5 text-center"></i>
                        <span>Export & Rapports</span>
                    </a>
                    <a href="#advanced" class="flex items-center gap-3 p-4 border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300">
                        <i class="fas fa-tools w-5 text-center"></i>
                        <span>Avancé</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Contenu des paramètres -->
        <div class="lg:col-span-3">
            <!-- Apparence -->
            <div id="appearance" class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="border-b border-gray-100 p-5">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-palette mr-2"></i>Apparence
                    </h5>
                </div>
                <div class="p-5">
                    @if(session('appearance_success'))
                        <div class="bg-green-50 text-green-800 rounded-lg p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm">{{ session('appearance_success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('settings.appearance') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Logo de l'application</label>
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-24 w-24 object-contain border rounded-md">
                                </div>
                                <div class="flex-grow">
                                    <input type="file" id="logo" name="logo" accept="image/*"
                                        class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('logo') border-red-500 @enderror">
                                    @error('logo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-gray-500">Format recommandé: PNG avec fond transparent. Dimensions: 200x200px</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="app_name" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'application</label>
                                <input type="text" id="app_name" name="app_name" value="{{ config('app.name') }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('app_name') border-red-500 @enderror">
                                @error('app_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="default_theme" class="block text-sm font-medium text-gray-700 mb-1">Thème par défaut</label>
                                <select id="default_theme" name="default_theme"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('default_theme') border-red-500 @enderror">
                                    <option value="light">Clair</option>
                                    <option value="dark">Sombre</option>
                                    <option value="auto">Automatique (selon système)</option>
                                </select>
                                @error('default_theme')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <button type="button" class="mb-6 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-palette mr-2"></i>Personnaliser les couleurs
                        </button>
                        
                        <div class="mb-6">
                            <h6 class="font-medium text-gray-700 mb-3">Options d'affichage</h6>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" id="show_footer" name="show_footer" value="1" checked
                                            class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="show_footer" class="font-medium text-gray-700">Afficher le pied de page</label>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" id="show_breadcrumbs" name="show_breadcrumbs" value="1" checked
                                            class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="show_breadcrumbs" class="font-medium text-gray-700">Afficher le fil d'Ariane</label>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" id="enable_animations" name="enable_animations" value="1" checked
                                            class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="enable_animations" class="font-medium text-gray-700">Activer les animations</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Général (section ajoutée) -->
            <div id="general" class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="border-b border-gray-100 p-5">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-sliders-h mr-2"></i>Paramètres généraux
                    </h5>
                </div>
                <div class="p-5">
                    <form action="{{ route('settings.advanced') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="date_format" class="block text-sm font-medium text-gray-700 mb-1">Format de date</label>
                                <select id="date_format" name="date_format"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                    <option value="d/m/Y">JJ/MM/AAAA (31/12/2023)</option>
                                    <option value="m/d/Y">MM/JJ/AAAA (12/31/2023)</option>
                                    <option value="Y-m-d">AAAA-MM-JJ (2023-12-31)</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="timezone" class="block text-sm font-medium text-gray-700 mb-1">Fuseau horaire</label>
                                <select id="timezone" name="timezone"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                    <option value="UTC">UTC</option>
                                    <option value="Africa/Douala">Afrique/Douala</option>
                                    <option value="Europe/Paris">Europe/Paris</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">Devise par défaut</label>
                            <select id="currency" name="currency"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                <option value="XAF">XAF (Franc CFA)</option>
                                <option value="EUR">EUR (Euro)</option>
                                <option value="USD">USD (Dollar américain)</option>
                            </select>
                        </div>
                        
                        <div>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Notifications -->
            <div id="notifications" class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="border-b border-gray-100 p-5">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-bell mr-2"></i>Notifications
                    </h5>
                    <p class="text-sm text-gray-500 mt-1">Ces fonctionnalités seront bientôt disponibles</p>
                </div>
                <div class="p-5">
                    <div class="bg-blue-50 text-blue-800 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm">Cette section sera disponible dans une prochaine mise à jour.</p>
                            </div>
                        </div>
                    </div>

                    <form action="#" method="POST" class="opacity-50">
                        @csrf
                        @method('PUT')
                        
                        <fieldset disabled>
                            <div class="mb-6">
                                <h6 class="font-medium text-gray-700 mb-3">Notifications par e-mail</h6>
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" id="email_new_student" name="email_notifications[]" value="new_student" checked
                                                class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="email_new_student" class="font-medium text-gray-700">Nouvel étudiant inscrit</label>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" id="email_new_payment" name="email_notifications[]" value="new_payment" checked
                                                class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="email_new_payment" class="font-medium text-gray-700">Nouveau paiement enregistré</label>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" id="email_payment_due" name="email_notifications[]" value="payment_due" checked
                                                class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="email_payment_due" class="font-medium text-gray-700">Échéance de paiement</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label for="email_from" class="block text-sm font-medium text-gray-700 mb-1">Adresse d'expédition</label>
                                    <input type="email" id="email_from" name="email_from" value="noreply@example.com"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                    <p class="mt-1 text-sm text-gray-500">Adresse e-mail utilisée pour envoyer les notifications</p>
                                </div>
                                
                                <div>
                                    <label for="email_name" class="block text-sm font-medium text-gray-700 mb-1">Nom d'expédition</label>
                                    <input type="text" id="email_name" name="email_name" value="Student Fees Manager"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </div>
                            </div>
                            
                            <div>
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save mr-2"></i>Enregistrer
                                </button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>

            <!-- Export & Rapports -->
            <div id="export" class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="border-b border-gray-100 p-5">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-file-export mr-2"></i>Export & Rapports
                    </h5>
                </div>
                <div class="p-5">
                    <form action="{{ route('settings.export') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <label for="pdf_paper_size" class="block text-sm font-medium text-gray-700 mb-1">Format de papier PDF</label>
                            <select id="pdf_paper_size" name="pdf_paper_size"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                <option value="a4">A4</option>
                                <option value="letter">Letter</option>
                                <option value="legal">Legal</option>
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <label for="pdf_orientation" class="block text-sm font-medium text-gray-700 mb-1">Orientation PDF</label>
                            <select id="pdf_orientation" name="pdf_orientation"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                <option value="portrait">Portrait</option>
                                <option value="landscape">Paysage</option>
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <h6 class="font-medium text-gray-700 mb-3">Options d'export</h6>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" id="add_header_footer" name="add_header_footer" value="1" checked
                                            class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="add_header_footer" class="font-medium text-gray-700">Ajouter en-tête et pied de page</label>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" id="include_logo" name="include_logo" value="1" checked
                                            class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="include_logo" class="font-medium text-gray-700">Inclure le logo de l'école</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Avancé -->
            <div id="advanced" class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="border-b border-gray-100 p-5">
                    <h5 class="font-bold text-primary-600 flex items-center">
                        <i class="fas fa-tools mr-2"></i>Paramètres avancés
                    </h5>
                </div>
                <div class="p-5">
                    <div class="bg-yellow-50 text-yellow-800 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">Attention</p>
                                <p class="text-sm">Ces paramètres sont destinés aux utilisateurs avancés. Une modification incorrecte peut affecter le fonctionnement de l'application.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('settings.advanced') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <label for="cache_ttl" class="block text-sm font-medium text-gray-700 mb-1">Durée de vie du cache (minutes)</label>
                            <input type="number" id="cache_ttl" name="cache_ttl" value="60" min="0"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        </div>
                        
                        <div class="mb-6">
                            <label for="pagination_limit" class="block text-sm font-medium text-gray-700 mb-1">Éléments par page</label>
                            <select id="pagination_limit" name="pagination_limit"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <label for="log_level" class="block text-sm font-medium text-gray-700 mb-1">Niveau de journalisation</label>
                            <select id="log_level" name="log_level"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                <option value="debug">Debug</option>
                                <option value="info">Info</option>
                                <option value="warning">Warning</option>
                                <option value="error">Error</option>
                            </select>
                        </div>
                        
                        <div>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 