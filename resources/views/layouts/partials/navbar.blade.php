<!-- resources/views/layouts/partials/navbar.blade.php -->
<header class="sticky top-0 z-40 bg-white border-b shadow-sm">
    <div class="px-4 flex items-center justify-between h-16">
        <!-- Mobile menu button and title area -->
        <div class="flex items-center lg:w-64">
            <button @click="mobileOpen = true" class="text-gray-600 hover:text-gray-800 focus:outline-none lg:hidden mr-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            
            <!-- Page title - visible on all devices -->
            <h1 class="text-lg font-semibold text-gray-900 truncate">
                @if (isset($header))
                    {{ $header }}
                @else
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
                    @elseif(request()->routeIs('schools.settings.*'))
                        Paramètres de l'école
                    @elseif(request()->routeIs('profile.*'))
                        Mon profil
                    @endif
                @endif
            </h1>
        </div>
        
        <!-- Right side controls: School selector, search bar, notifications -->
        <div class="flex items-center space-x-3">
            <!-- School Selector -->
            @if(session('current_school'))
            <div class="relative hidden sm:block" x-data="{ schoolOpen: false }">
                <button @click="schoolOpen = !schoolOpen" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    @if(session('current_school')->logo)
                        <img src="{{ asset('storage/' . session('current_school')->logo) }}" class="h-5 w-5 rounded-full mr-1.5 object-cover" alt="{{ session('current_school')->name }}">
                    @else
                        <i class="fas fa-school mr-1.5 text-primary-600"></i>
                    @endif
                    <span class="truncate max-w-[120px] mr-1">{{ session('current_school')->name }}</span>
                    <i class="fas fa-chevron-down text-xs opacity-70"></i>
                </button>
                
                <div x-show="schoolOpen" @click.away="schoolOpen = false" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl py-1 text-gray-700 z-10 border border-gray-100">
                    <div class="px-4 py-2 text-xs text-gray-500 border-b border-gray-100">Écoles disponibles</div>
                    
                    @forelse (auth()->user()->schools as $school)
                        <a href="{{ route('schools.switch', ['school' => $school->id]) }}" class="flex items-center px-4 py-2 hover:bg-gray-50 {{ session('current_school_id') == $school->id ? 'bg-primary-50' : '' }}">
                            <div class="flex items-center flex-1">
                                @if($school->logo)
                                    <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="w-5 h-5 mr-2 rounded-full object-cover">
                                @else
                                    <i class="fas fa-school mr-2 text-primary-400"></i>
                                @endif
                                <span class="text-sm truncate">{{ $school->name }}</span>
                            </div>
                            @if(session('current_school_id') == $school->id)
                                <span class="h-5 w-5 bg-primary-100 rounded-full flex items-center justify-center ml-2 flex-shrink-0">
                                    <i class="fas fa-check text-xs text-primary-600"></i>
                                </span>
                            @endif
                        </a>
                    @empty
                        <div class="px-4 py-2 text-sm text-gray-500">Aucune école disponible</div>
                    @endforelse
                    
                    <div class="border-t border-gray-100 my-1"></div>
                    <a href="{{ route('schools.index') }}" class="block px-4 py-2 hover:bg-gray-50 text-sm">
                        <i class="fas fa-list mr-2 text-primary-400"></i>Gérer les écoles
                    </a>
                </div>
            </div>
            @endif
            
            <!-- Search bar - visible on larger screens -->
            <div class="hidden md:block relative">
                <div class="flex items-center bg-gray-100 rounded-lg px-3 py-1.5 focus-within:ring-2 focus-within:ring-primary-500 focus-within:bg-white transition-all duration-200">
                    <i class="fas fa-search text-gray-400 text-sm"></i>
                    <input type="text" placeholder="Rechercher..." class="bg-transparent border-none focus:outline-none text-sm ml-2 w-40 lg:w-60">
                </div>
            </div>
            
            <!-- Notifications -->
            <div class="relative" x-data="{ notificationsOpen: false }">
                <button @click="notificationsOpen = !notificationsOpen" class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full focus:outline-none relative">
                    <i class="fas fa-bell"></i>
                    <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
                </button>
                
                <div x-show="notificationsOpen" @click.away="notificationsOpen = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-2 z-10 border border-gray-100">
                    <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                        <span class="text-xs text-primary-600 font-medium cursor-pointer hover:text-primary-800">Tout marquer comme lu</span>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        <!-- Sample notifications - replace with dynamic content -->
                        <div class="px-4 py-3 hover:bg-gray-50 border-l-4 border-primary-500">
                            <p class="text-sm font-medium text-gray-900">Nouveau paiement</p>
                            <p class="text-xs text-gray-500">Paul Durand a effectué un paiement de 350€</p>
                            <p class="text-xs text-gray-400 mt-1">Il y a 20 minutes</p>
                        </div>
                        <div class="px-4 py-3 hover:bg-gray-50">
                            <p class="text-sm font-medium text-gray-900">Rappel</p>
                            <p class="text-xs text-gray-500">5 étudiants ont des paiements en retard</p>
                            <p class="text-xs text-gray-400 mt-1">Il y a 3 heures</p>
                        </div>
                    </div>
                    <a href="#" class="block text-center text-primary-600 hover:text-primary-800 text-xs font-medium p-2 border-t border-gray-100">
                        Voir toutes les notifications
                    </a>
                </div>
            </div>
            
            <!-- Mobile user menu dropdown -->
            @auth
            <div class="relative" x-data="{ userOpen: false }">
                <button @click="userOpen = !userOpen" class="flex items-center focus:outline-none">
                    <div class="h-8 w-8 rounded-full bg-primary-200 flex items-center justify-center">
                        <i class="fas fa-user text-sm text-primary-700"></i>
                    </div>
                </button>
                
                <div x-show="userOpen" @click.away="userOpen = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-10 border border-gray-100">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('profile.index') }}" class="flex items-center px-4 py-2 hover:bg-gray-50 text-sm">
                        <i class="fas fa-user-circle mr-2 text-primary-400"></i> Profil
                    </a>
                    @if(session('current_school'))
                    <a href="{{ route('schools.settings.index', session('current_school')) }}" class="flex items-center px-4 py-2 hover:bg-gray-50 text-sm">
                        <i class="fas fa-cog mr-2 text-primary-400"></i> Paramètres
                    </a>
                    @endif
                    <div class="border-t border-gray-100 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full text-left px-4 py-2 hover:bg-gray-50 text-sm">
                            <i class="fas fa-sign-out-alt mr-2 text-primary-400"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </div>
            @else
            <a href="{{ route('login') }}" class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-200">
                <i class="fas fa-user text-gray-500"></i>
            </a>
            @endauth
        </div>
    </div>
    
    <!-- Mobile search bar - visible only on small screens -->
    <div class="px-4 pb-3 lg:hidden">
        <div class="flex items-center bg-gray-100 rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-primary-500 focus-within:bg-white transition-all duration-200 w-full">
            <i class="fas fa-search text-gray-400 text-sm"></i>
            <input type="text" placeholder="Rechercher..." class="bg-transparent border-none focus:outline-none text-sm ml-2 w-full">
        </div>
    </div>
</header>