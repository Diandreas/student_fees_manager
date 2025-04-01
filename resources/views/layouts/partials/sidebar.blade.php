<!-- resources/views/layouts/partials/sidebar.blade.php -->
<!-- Overlay for mobile -->
<div x-show="mobileOpen" 
     x-transition:enter="transition-opacity ease-in-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-in-out duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-40 bg-black bg-opacity-60 lg:hidden" 
     @click="mobileOpen = false"></div>

<!-- Sidebar for both desktop and mobile -->
<aside :class="{
        'w-64': sidebarOpen,
        'w-20': !sidebarOpen,
        'transform -translate-x-full': !mobileOpen,
        'transform translate-x-0': mobileOpen
     }" 
     class="fixed inset-y-0 left-0 z-50 bg-white shadow-lg transition-all duration-300 ease-in-out lg:transform lg:translate-x-0">
    
    <!-- Sidebar header/logo -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-100">
        <a href="{{ route('dashboard') }}" class="flex items-center overflow-hidden">
            <div class="flex-shrink-0">
                @if(session('current_school') && session('current_school')->logo)
                    <img src="{{ asset('storage/' . session('current_school')->logo) }}" class="h-8 w-auto" alt="Logo">
                @else
                    <div class="h-8 w-8 bg-primary-600 rounded-md flex items-center justify-center">
                        <span class="text-white font-bold">{{ substr(config('app.name', 'L'), 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <span x-show="sidebarOpen" x-transition class="ml-2 font-semibold text-gray-900 text-lg truncate">{{ config('app.name', 'Laravel') }}</span>
        </a>
        
        <button @click="toggleSidebar()" class="text-gray-500 hover:text-gray-700 focus:outline-none hidden lg:block">
            <svg x-show="sidebarOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
            <svg x-show="!sidebarOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
        </button>
        <button @click="mobileOpen = false" class="text-gray-500 hover:text-gray-700 focus:outline-none lg:hidden">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <div class="flex flex-col h-[calc(100vh-4rem)] overflow-y-auto">
        <nav class="flex-1 px-2 py-4 space-y-1">
            <!-- Main navigation links -->
            <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <div class="p-1.5 {{ request()->routeIs('dashboard') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500 group-hover:bg-primary-600 group-hover:text-white group-hover:rounded-full' }}">
                    <i class="fas fa-tachometer-alt text-sm"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="ml-2.5 truncate">Tableau de bord</span>
            </a>

            <a href="{{ route('campuses.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('campuses.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <div class="p-1.5 {{ request()->routeIs('campuses.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500 group-hover:bg-primary-600 group-hover:text-white group-hover:rounded-full' }}">
                    <i class="fas fa-school text-sm"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="ml-2.5 truncate">{{ $term('campuses') }}</span>
            </a>

            <a href="{{ route('fields.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('fields.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <div class="p-1.5 {{ request()->routeIs('fields.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500 group-hover:bg-primary-600 group-hover:text-white group-hover:rounded-full' }}">
                    <i class="fas fa-graduation-cap text-sm"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="ml-2.5 truncate">{{ $term('fields') }}</span>
            </a>

            <a href="{{ route('students.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('students.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <div class="p-1.5 {{ request()->routeIs('students.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500 group-hover:bg-primary-600 group-hover:text-white group-hover:rounded-full' }}">
                    <i class="fas fa-user-graduate text-sm"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="ml-2.5 truncate">{{ $term('students') }}</span>
            </a>

            <a href="{{ route('payments.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('payments.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <div class="p-1.5 {{ request()->routeIs('payments.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500 group-hover:bg-primary-600 group-hover:text-white group-hover:rounded-full' }}">
                    <i class="fas fa-money-bill-wave text-sm"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="ml-2.5 truncate">{{ $term('payments') }}</span>
            </a>

            <a href="{{ route('reports.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('reports.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <div class="p-1.5 {{ request()->routeIs('reports.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500 group-hover:bg-primary-600 group-hover:text-white group-hover:rounded-full' }}">
                    <i class="fas fa-chart-line text-sm"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="ml-2.5 truncate">{{ $term('reports') }}</span>
            </a>

            <a href="{{ route('activity-logs.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('activity-logs.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <div class="p-1.5 {{ request()->routeIs('activity-logs.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500 group-hover:bg-primary-600 group-hover:text-white group-hover:rounded-full' }}">
                    <i class="fas fa-clock text-sm"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="ml-2.5 truncate">Journal d'activités</span>
            </a>

            <a href="{{ route('archives.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('archives.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <div class="p-1.5 {{ request()->routeIs('archives.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500 group-hover:bg-primary-600 group-hover:text-white group-hover:rounded-full' }}">
                    <i class="fas fa-archive text-sm"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="ml-2.5 truncate">Archives</span>
            </a>

            <a href="{{ route('statistics.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('statistics.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <div class="p-1.5 {{ request()->routeIs('statistics.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500' }}">
                    <i class="fas fa-chart-bar text-sm"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="ml-2.5 truncate">Statistiques</span>
            </a>
            
            @if(session('current_school'))
            <a href="{{ route('schools.settings.index', session('current_school')) }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('schools.settings.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <div class="p-1.5 {{ request()->routeIs('schools.settings.*') ? 'bg-primary-600 text-white rounded-full' : 'text-gray-500' }}">
                    <i class="fas fa-cog text-sm"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="ml-2.5 truncate">{{ __('Paramètres') }}</span>
            </a>
            @endif
        </nav>

        <!-- Quick Actions Section - Only visible when sidebar is expanded -->
        <div x-show="sidebarOpen" x-transition class="px-4 py-3 mt-auto border-t border-gray-100">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Actions rapides</h3>
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('students.create') }}" class="flex flex-col items-center px-2 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg text-center">
                    <div class="p-2 bg-green-100 text-green-600 rounded-full mb-1">
                        <i class="fas fa-user-plus text-sm"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-700 truncate">Nouvel étudiant</span>
                </a>
                <a href="{{ route('payments.create') }}" class="flex flex-col items-center px-2 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg text-center">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-full mb-1">
                        <i class="fas fa-hand-holding-usd text-sm"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-700 truncate">Paiement</span>
                </a>
            </div>
        </div>

        <!-- Quick Actions for collapsed sidebar -->
        <div x-show="!sidebarOpen" x-transition class="px-2 py-3 mt-auto border-t border-gray-100 flex flex-col items-center space-y-3">
            <a href="{{ route('students.create') }}" class="flex flex-col items-center px-1 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg text-center w-full">
                <div class="p-1.5 bg-green-100 text-green-600 rounded-full">
                    <i class="fas fa-user-plus text-xs"></i>
                </div>
            </a>
            <a href="{{ route('payments.create') }}" class="flex flex-col items-center px-1 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg text-center w-full">
                <div class="p-1.5 bg-blue-100 text-blue-600 rounded-full">
                    <i class="fas fa-hand-holding-usd text-xs"></i>
                </div>
            </a>
        </div>

        <!-- User Menu -->
        <div class="border-t border-gray-100">
            @auth
            <div x-data="{ profileOpen: false }" class="relative">
                <button @click="profileOpen = !profileOpen" class="w-full flex items-center px-3 py-3 text-sm text-left text-gray-700 hover:bg-gray-100 focus:outline-none">
                    <div class="flex-shrink-0">
                        <div class="h-9 w-9 rounded-full bg-primary-200 flex items-center justify-center">
                            <i class="fas fa-user text-sm text-primary-700"></i>
                        </div>
                    </div>
                    <div x-show="sidebarOpen" x-transition class="ml-3 truncate">
                        <p class="font-medium text-gray-800 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <svg x-show="sidebarOpen" x-transition class="ml-auto w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div x-show="profileOpen" @click.away="profileOpen = false" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     :class="{
                         'right-0 w-48': sidebarOpen,
                         'left-full ml-2 w-40': !sidebarOpen
                     }"
                     class="absolute bottom-full mb-1 bg-white rounded-lg shadow-lg py-1 z-10">
                    <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user-circle mr-2 text-primary-400"></i> Profil
                    </a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-sign-out-alt mr-2 text-primary-400"></i> Déconnexion
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
                <div x-show="sidebarOpen" x-transition class="ml-3 truncate">
                    <p class="font-medium text-gray-700">Invité</p>
                    <p class="text-xs text-gray-500">Connexion</p>
                </div>
            </a>
            @endauth
        </div>
    </div>
</aside>