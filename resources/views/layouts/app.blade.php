<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} @if(session('current_school')) - {{ session('current_school')->name }} @endif</title>
    
    <!-- Favicon -->
    @if(session('current_school') && session('current_school')->logo)
        <link rel="icon" href="{{ asset('storage/' . session('current_school')->logo) }}" type="image/x-icon">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @endif

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar (desktop) -->
        <div x-data="{ open: true }" :class="{'w-64': open, 'w-20': !open}" 
             class="fixed inset-y-0 left-0 z-50 bg-white shadow-lg transition-all duration-300 ease-in-out hidden lg:block">
            
            <!-- Sidebar header/logo -->
            <div class="flex items-center justify-between h-16 px-4 border-b border-gray-100">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <div class="flex-shrink-0">
                        @if(session('current_school') && session('current_school')->logo)
                            <img src="{{ asset('storage/' . session('current_school')->logo) }}" class="h-8 w-auto" alt="Logo">
                        @else
                            <div class="h-8 w-8 bg-primary-600 rounded-md flex items-center justify-center">
                                <span class="text-white font-bold">{{ substr(config('app.name', 'L'), 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <span x-show="open" class="ml-2 font-semibold text-gray-900 text-lg transition-opacity">{{ config('app.name', 'Laravel') }}</span>
                </a>
                
                <button @click="open = !open" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                    <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <div class="py-4 flex flex-col h-[calc(100vh-4rem)]">
                <nav class="flex-1 px-2 space-y-1 overflow-y-auto">
                    <!-- Liste de navigation plate, sans catégories -->
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="p-1.5 {{ request()->routeIs('dashboard') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500 group-hover:bg-primary-600 group-hover:text-white group-hover:rounded-full' }}">
                            <i class="fas fa-tachometer-alt text-sm"></i>
                        </div>
                        <span x-show="open" class="ml-2.5">Tableau de bord</span>
                    </a>

                    <a href="{{ route('campuses.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('campuses.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="p-1.5 {{ request()->routeIs('campuses.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500 group-hover:bg-primary-600 group-hover:text-white group-hover:rounded-full' }}">
                            <i class="fas fa-school text-sm"></i>
                        </div>
                        <span x-show="open" class="ml-2.5">Campus</span>
                    </a>

                    <a href="{{ route('fields.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('fields.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="p-1.5 {{ request()->routeIs('fields.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500 group-hover:bg-primary-600 group-hover:text-white group-hover:rounded-full' }}">
                            <i class="fas fa-graduation-cap text-sm"></i>
                        </div>
                        <span x-show="open" class="ml-2.5">Filières</span>
                    </a>

                    <a href="{{ route('students.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('students.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="p-1.5 {{ request()->routeIs('students.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500 group-hover:bg-primary-600 group-hover:text-white group-hover:rounded-full' }}">
                            <i class="fas fa-user-graduate text-sm"></i>
                        </div>
                        <span x-show="open" class="ml-2.5">Étudiants</span>
                    </a>

                    <a href="{{ route('payments.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('payments.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="p-1.5 {{ request()->routeIs('payments.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500 group-hover:bg-primary-600 group-hover:text-white group-hover:rounded-full' }}">
                            <i class="fas fa-money-bill-wave text-sm"></i>
                        </div>
                        <span x-show="open" class="ml-2.5">Paiements</span>
                    </a>
                     @if(session('current_school'))
                            <a href="{{ route('schools.settings.index', session('current_school')) }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('schools.settings.index') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                <div class="p-1.5 {{ request()->routeIs('schools.settings.index') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500 group-hover:bg-primary-600 group-hover:text-white group-hover:rounded-full' }}">
                                    <i class="fas fa-cog text-sm"></i>
                                </div>
                                <span x-show="open" class="ml-2.5">{{ __('Paramètres') }}</span>
                            </a>
                            @endif
                    
                    {{-- <a href="{{ route('invoices.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('invoices.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="p-1.5 {{ request()->routeIs('invoices.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500 group-hover:bg-primary-600 group-hover:text-white group-hover:rounded-full' }}">
                            <i class="fas fa-file-invoice-dollar text-sm"></i>
                        </div>
                        <span x-show="open" class="ml-2.5">Factures</span>
                    </a> --}}

                    <a href="{{ route('reports.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('reports.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="p-1.5 {{ request()->routeIs('reports.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500 group-hover:bg-primary-600 group-hover:text-white group-hover:rounded-full' }}">
                            <i class="fas fa-chart-line text-sm"></i>
                        </div>
                        <span x-show="open" class="ml-2.5">Rapports</span>
                    </a>

                    <a href="{{ route('activity-logs.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('activity-logs.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="p-1.5 {{ request()->routeIs('activity-logs.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500 group-hover:bg-primary-600 group-hover:text-white group-hover:rounded-full' }}">
                            <i class="fas fa-clock text-sm"></i>
                        </div>
                        <span x-show="open" class="ml-2.5">Journal d'activités</span>
                    </a>

                    <a href="{{ route('archives.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('archives.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="p-1.5 {{ request()->routeIs('archives.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500 group-hover:bg-primary-600 group-hover:text-white group-hover:rounded-full' }}">
                            <i class="fas fa-archive text-sm"></i>
                        </div>
                        <span x-show="open" class="ml-2.5">Archives</span>
                    </a>

                    <a href="{{ route('statistics.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('statistics.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="p-1.5 {{ request()->routeIs('statistics.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500' }}">
                            <i class="fas fa-chart-bar text-sm"></i>
                        </div>
                        <span x-show="open" class="ml-2.5">Statistiques</span>
                    </a>
                </nav>

                <!-- Section Actions Rapides -->
                <div x-show="open" class="px-4 py-3 mt-2 border-t border-gray-100">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Actions rapides</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('students.create') }}" class="flex flex-col items-center px-2 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg text-center">
                            <div class="p-2 bg-green-100 text-green-600 rounded-full mb-1">
                                <i class="fas fa-user-plus text-sm"></i>
                            </div>
                            <span class="text-xs font-medium text-gray-700">Nouvel étudiant</span>
                        </a>
                        <a href="{{ route('payments.create') }}" class="flex flex-col items-center px-2 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg text-center">
                            <div class="p-2 bg-blue-100 text-blue-600 rounded-full mb-1">
                                <i class="fas fa-hand-holding-usd text-sm"></i>
                            </div>
                            <span class="text-xs font-medium text-gray-700">Paiement</span>
                        </a>
                        <a href="{{ route('fields.create') }}" class="flex flex-col items-center px-2 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg text-center">
                            <div class="p-2 bg-purple-100 text-purple-600 rounded-full mb-1">
                                <i class="fas fa-graduation-cap text-sm"></i>
                            </div>
                            <span class="text-xs font-medium text-gray-700">Nouvelle filière</span>
                        </a>
                        <a href="{{ route('reports.index') }}" class="flex flex-col items-center px-2 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg text-center">
                            <div class="p-2 bg-yellow-100 text-yellow-600 rounded-full mb-1">
                                <i class="fas fa-file-export text-sm"></i>
                            </div>
                            <span class="text-xs font-medium text-gray-700">Rapports</span>
                        </a>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="mt-auto border-t border-gray-100">
                    @auth
                    <div x-data="{ profileOpen: false }" class="relative">
                        <button @click="profileOpen = !profileOpen" class="w-full flex items-center px-3 py-3 text-sm text-left text-gray-700 hover:bg-gray-100 focus:outline-none">
                            <div class="flex-shrink-0">
                                <img class="h-9 w-9 rounded-full object-cover border border-gray-200" 
                                     src="{{ Auth::user()->profile_photo_url ?? asset('images/default-avatar.png') }}" 
                                     alt="{{ Auth::user()->name }}">
                            </div>
                            <div x-show="open" class="ml-3">
                                <p class="font-medium text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <svg x-show="open" class="ml-auto w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <div x-show="profileOpen" @click.away="profileOpen = false" 
                             class="absolute right-0 bottom-full mb-1 w-48 bg-white rounded-lg shadow-lg py-1 z-10">
                            {{-- <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profil
                            </a> --}}
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Déconnexion
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="flex items-center px-3 py-3 text-gray-700 hover:bg-gray-100">
                        <div class="flex-shrink-0">
                            <div class="h-9 w-9 rounded-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                        </div>
                        <div x-show="open" class="ml-3">
                            <p class="font-medium text-gray-700">Invité</p>
                            <p class="text-xs text-gray-500">Connexion</p>
                        </div>
                    </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Main Content avec Mobile Menu -->
        <div x-data="{ mobileOpen: false }" class="lg:pl-64 flex-1 flex flex-col min-h-screen">
            <!-- Mobile Header -->
            <div class="sticky top-0 z-40 flex items-center justify-between h-16 px-4 bg-white border-b lg:hidden shadow-sm">
                <button @click="mobileOpen = true" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                
                <div class="flex items-center">
                    @if(session('current_school') && session('current_school')->logo)
                        <img src="{{ asset('storage/' . session('current_school')->logo) }}" class="h-8 w-auto mr-2" alt="Logo">
                    @endif
                    <span class="text-lg font-semibold text-gray-900">
                        {{ config('app.name', 'Laravel') }}
                        @if(session('current_school'))
                            <span class="text-sm text-gray-500 ml-2">{{ session('current_school')->name }}</span>
                        @endif
                    </span>
                </div>
                
                @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center focus:outline-none">
                        <i class="fas fa-user text-gray-500"></i>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" 
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-10">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Profil
                        </a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile-header').submit();" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Déconnexion
                        </a>
                        <form id="logout-form-mobile-header" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-200">
                    <i class="fas fa-user text-gray-500"></i>
                </a>
                @endauth
            </div>

            <!-- Mobile Sidebar -->
            <div x-show="mobileOpen" 
                 class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 transition-opacity lg:hidden" 
                 @click="mobileOpen = false"></div>
            
            <div x-show="mobileOpen" 
                 class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out lg:hidden">
                <!-- Mobile sidebar content -->
                <div class="flex flex-col h-full">
                    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-100">
                        <a href="{{ route('dashboard') }}" class="flex items-center">
                            <div class="flex-shrink-0">
                                @if(session('current_school') && session('current_school')->logo)
                                    <img src="{{ asset('storage/' . session('current_school')->logo) }}" class="h-8 w-auto" alt="Logo">
                                @else
                                    <div class="h-8 w-8 bg-primary-600 rounded-md flex items-center justify-center">
                                        <span class="text-white font-bold">{{ substr(config('app.name', 'L'), 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <span class="ml-2 font-semibold text-gray-900 text-lg">{{ config('app.name', 'Laravel') }}</span>
                        </a>
                        
                        <button @click="mobileOpen = false" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Navigation mobile -->
                    <div class="py-4 flex flex-col h-[calc(100vh-4rem)]">
                        <nav class="flex-1 px-2 space-y-1 overflow-y-auto">
                            <!-- Même navigation que desktop -->
                            <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                <div class="p-1.5 {{ request()->routeIs('dashboard') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500' }}">
                                    <i class="fas fa-tachometer-alt text-sm"></i>
                                </div>
                                <span class="ml-2.5">Tableau de bord</span>
                            </a>

                            <a href="{{ route('campuses.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('campuses.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                <div class="p-1.5 {{ request()->routeIs('campuses.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500' }}">
                                    <i class="fas fa-school text-sm"></i>
                                </div>
                                <span class="ml-2.5">Campus</span>
                            </a>

                            <a href="{{ route('fields.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('fields.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                <div class="p-1.5 {{ request()->routeIs('fields.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500' }}">
                                    <i class="fas fa-graduation-cap text-sm"></i>
                                </div>
                                <span class="ml-2.5">Filières</span>
                            </a>

                            <a href="{{ route('students.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('students.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                <div class="p-1.5 {{ request()->routeIs('students.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500' }}">
                                    <i class="fas fa-user-graduate text-sm"></i>
                                </div>
                                <span class="ml-2.5">Étudiants</span>
                            </a>

                            <a href="{{ route('payments.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('payments.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                <div class="p-1.5 {{ request()->routeIs('payments.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500' }}">
                                    <i class="fas fa-money-bill-wave text-sm"></i>
                                </div>
                                <span class="ml-2.5">Paiements</span>
                            </a>
                            
                            {{-- <a href="{{ route('invoices.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('invoices.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                <div class="p-1.5 {{ request()->routeIs('invoices.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500' }}">
                                    <i class="fas fa-file-invoice-dollar text-sm"></i>
                                </div>
                                <span class="ml-2.5">Factures</span>
                            </a> --}}

                            <a href="{{ route('reports.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('reports.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                <div class="p-1.5 {{ request()->routeIs('reports.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500' }}">
                                    <i class="fas fa-chart-line text-sm"></i>
                                </div>
                                <span class="ml-2.5">Rapports</span>
                            </a>
                           

                            <a href="{{ route('activity-logs.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('activity-logs.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                <div class="p-1.5 {{ request()->routeIs('activity-logs.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500' }}">
                                    <i class="fas fa-clock text-sm"></i>
                                </div>
                                <span class="ml-2.5">Journal d'activités</span>
                            </a>

                            <a href="{{ route('archives.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('archives.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                <div class="p-1.5 {{ request()->routeIs('archives.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500' }}">
                                    <i class="fas fa-archive text-sm"></i>
                                </div>
                                <span class="ml-2.5">Archives</span>
                            </a>

                            <a href="{{ route('statistics.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('statistics.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                <div class="p-1.5 {{ request()->routeIs('statistics.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500' }}">
                                    <i class="fas fa-chart-bar text-sm"></i>
                                </div>
                                <span class="ml-2.5">Statistiques</span>
                            </a>
                        </nav>

                        <!-- Section Actions Rapides (Mobile) -->
                        <div class="px-4 py-3 mt-2 border-t border-gray-100">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Actions rapides</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('students.create') }}" class="flex flex-col items-center px-2 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg text-center">
                                    <div class="p-2 bg-green-100 text-green-600 rounded-full mb-1">
                                        <i class="fas fa-user-plus text-sm"></i>
                                    </div>
                                    <span class="text-xs font-medium text-gray-700">Nouvel étudiant</span>
                                </a>
                                <a href="{{ route('payments.create') }}" class="flex flex-col items-center px-2 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg text-center">
                                    <div class="p-2 bg-blue-100 text-blue-600 rounded-full mb-1">
                                        <i class="fas fa-hand-holding-usd text-sm"></i>
                                    </div>
                                    <span class="text-xs font-medium text-gray-700">Paiement</span>
                                </a>
                                <a href="{{ route('fields.create') }}" class="flex flex-col items-center px-2 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg text-center">
                                    <div class="p-2 bg-purple-100 text-purple-600 rounded-full mb-1">
                                        <i class="fas fa-graduation-cap text-sm"></i>
                                    </div>
                                    <span class="text-xs font-medium text-gray-700">Nouvelle filière</span>
                                </a>
                                <a href="{{ route('reports.index') }}" class="flex flex-col items-center px-2 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg text-center">
                                    <div class="p-2 bg-yellow-100 text-yellow-600 rounded-full mb-1">
                                        <i class="fas fa-file-export text-sm"></i>
                                    </div>
                                    <span class="text-xs font-medium text-gray-700">Rapports</span>
                                </a>
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="mt-auto border-t border-gray-100">
                            @auth
                            <div class="px-3 py-3">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-user text-gray-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-800">{{ Auth::user()->name }}</p>
                                        <div class="flex space-x-2 mt-1">
                                            {{-- <a href="{{ route('profile.show') }}" class="text-xs text-primary-600 hover:text-primary-700">Profil</a> --}}
                                            <span class="text-xs text-gray-400">|</span>
                                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();" 
                                               class="text-xs text-primary-600 hover:text-primary-700">Déconnexion</a>
                                        </div>
                                        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @else
                            <a href="{{ route('login') }}" class="flex items-center px-3 py-3 text-sm text-gray-700 hover:bg-gray-100">
                                <div class="flex-shrink-0">
                                    <div class="h-9 w-9 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-500"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium text-gray-700">Invité</p>
                                    <p class="text-xs text-gray-500">Connexion</p>
                                </div>
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Header -->
            <div class="hidden lg:block sticky top-0 z-30 bg-white border-b shadow-sm">
                <div class="flex items-center justify-between h-16 px-6">
                    <div>
                        @if (isset($header))
                            <h1 class="text-xl font-semibold text-gray-900">{{ $header }}</h1>
                        @else
                            <h1 class="text-xl font-semibold text-gray-900">
                                @if(request()->routeIs('dashboard'))
                                    Tableau de bord
                                @elseif(request()->routeIs('campuses.*'))
                                    Campus
                                @elseif(request()->routeIs('fields.*'))
                                    Filières
                                @elseif(request()->routeIs('students.*'))
                                    Étudiants
                                @elseif(request()->routeIs('payments.*'))
                                    Paiements
                                @elseif(request()->routeIs('invoices.*'))
                                    Factures
                                @elseif(request()->routeIs('reports.*'))
                                    Rapports
                                @elseif(request()->routeIs('activity-logs.*'))
                                    Journal d'activités
                                @elseif(request()->routeIs('archives.*'))
                                    Archives
                                @elseif(request()->routeIs('statistics.*'))
                                    Statistiques
                                @endif
                            </h1>
                        @endif
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- School Selector (if applicable) -->
                        @if(session('current_school'))
                        <div class="flex items-center text-sm text-gray-700">
                            <span class="mr-2">École:</span>
                            <span class="font-medium">{{ session('current_school')->name }}</span>
                        </div>
                        @endif
                        
                        <!-- Notifications (placeholder) -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="p-1 text-gray-500 hover:text-gray-700 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" 
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-2 z-10">
                                <div class="px-4 py-2 border-b">
                                    <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                                </div>
                                <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                    Aucune notification
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 py-6">
                <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <!-- Mobile header title -->
                    <div class="lg:hidden mb-6">
                        @if (isset($header))
                            <h1 class="text-xl font-semibold text-gray-900">{{ $header }}</h1>
                        @else
                            <h1 class="text-xl font-semibold text-gray-900">
                                @if(request()->routeIs('dashboard'))
                                    Tableau de bord
                                @elseif(request()->routeIs('campuses.*'))
                                    Campus
                                @elseif(request()->routeIs('fields.*'))
                                    Filières
                                @elseif(request()->routeIs('students.*'))
                                    Étudiants
                                @elseif(request()->routeIs('payments.*'))
                                    Paiements
                                @elseif(request()->routeIs('invoices.*'))
                                    Factures
                                @elseif(request()->routeIs('reports.*'))
                                    Rapports
                                @elseif(request()->routeIs('activity-logs.*'))
                                    Journal d'activités
                                @elseif(request()->routeIs('archives.*'))
                                    Archives
                                @elseif(request()->routeIs('statistics.*'))
                                    Statistiques
                                @endif
                            </h1>
                        @endif
                    </div>

                    <!-- Alerts -->
                    @if(session('success'))
                        <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded shadow-sm flex items-center" role="alert">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded shadow-sm flex items-center" role="alert">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="font-medium">{{ session('error') }}</p>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="mb-4 bg-blue-50 border-l-4 border-blue-500 text-blue-800 p-4 rounded shadow-sm flex items-center" role="alert">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="font-medium">{{ session('info') }}</p>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded shadow-sm flex items-center" role="alert">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p class="font-medium">{{ session('warning') }}</p>
                        </div>
                    @endif
                    
                    <!-- Main Content -->
                    @yield('content')
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="bg-white border-t py-4 px-4 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    <div class="flex flex-col md:flex-row md:justify-between items-center text-sm text-gray-500">
                        <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Tous droits réservés.</p>
                        <div class="mt-2 md:mt-0 flex space-x-4">
                            <a href="#" class="hover:text-gray-700">Confidentialité</a>
                            <a href="#" class="hover:text-gray-700">Conditions</a>
                            <a href="#" class="hover:text-gray-700">Support</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>