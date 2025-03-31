<nav class="bg-gradient-to-r from-primary-700 to-primary-600 text-white shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center space-x-2 group">
                @if(session('current_school') && session('current_school')->logo)
                    <div class="p-1.5 bg-white rounded-full shadow-sm transform transition duration-300 group-hover:scale-105">
                        <img src="{{ asset('storage/' . session('current_school')->logo) }}" alt="{{ session('current_school')->name }}" class="h-7 w-auto">
                    </div>
                @else
                    <div class="p-2 bg-primary-200 rounded-full text-primary-700 shadow-sm transform transition duration-300 group-hover:scale-105">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                @endif
                <span class="font-bold text-lg group-hover:text-primary-50 transition-colors duration-200">{{ session('current_school') ? session('current_school')->name : config('app.name', 'Student Fees Manager') }}</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-1">
                @auth
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-md hover:bg-primary-500 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-primary-800 shadow-inner' : '' }} group">
                        <div class="p-1 {{ request()->routeIs('dashboard') ? 'bg-primary-600 rounded-full' : '' }} group-hover:bg-primary-600 group-hover:rounded-full transition-all duration-200">
                            <i class="fas fa-tachometer-alt text-sm"></i>
                        </div>
                        <span class="ml-1.5">{{ $term('dashboard') }}</span>
                    </a>
                    <a href="{{ route('campuses.index') }}" class="flex items-center px-3 py-2 rounded-md hover:bg-primary-500 transition-all duration-200 {{ request()->routeIs('campuses.*') ? 'bg-primary-800 shadow-inner' : '' }} group">
                        <div class="p-1 {{ request()->routeIs('campuses.*') ? 'bg-primary-600 rounded-full' : '' }} group-hover:bg-primary-600 group-hover:rounded-full transition-all duration-200">
                            <i class="fas fa-school text-sm"></i>
                        </div>
                        <span class="ml-1.5">{{ $term('campuses') }}</span>
                    </a>
                    <a href="{{ route('fields.index') }}" class="flex items-center px-3 py-2 rounded-md hover:bg-primary-500 transition-all duration-200 {{ request()->routeIs('fields.*') ? 'bg-primary-800 shadow-inner' : '' }} group">
                        <div class="p-1 {{ request()->routeIs('fields.*') ? 'bg-primary-600 rounded-full' : '' }} group-hover:bg-primary-600 group-hover:rounded-full transition-all duration-200">
                            <i class="fas fa-graduation-cap text-sm"></i>
                        </div>
                        <span class="ml-1.5">{{ $term('fields') }}</span>
                    </a>
                    <a href="{{ route('students.index') }}" class="flex items-center px-3 py-2 rounded-md hover:bg-primary-500 transition-all duration-200 {{ request()->routeIs('students.*') ? 'bg-primary-800 shadow-inner' : '' }} group">
                        <div class="p-1 {{ request()->routeIs('students.*') ? 'bg-primary-600 rounded-full' : '' }} group-hover:bg-primary-600 group-hover:rounded-full transition-all duration-200">
                            <i class="fas fa-user-graduate text-sm"></i>
                        </div>
                        <span class="ml-1.5">{{ $term('students') }}</span>
                    </a>
                    <a href="{{ route('payments.index') }}" class="flex items-center px-3 py-2 rounded-md hover:bg-primary-500 transition-all duration-200 {{ request()->routeIs('payments.*') ? 'bg-primary-800 shadow-inner' : '' }} group">
                        <div class="p-1 {{ request()->routeIs('payments.*') ? 'bg-primary-600 rounded-full' : '' }} group-hover:bg-primary-600 group-hover:rounded-full transition-all duration-200">
                            <i class="fas fa-money-bill-wave text-sm"></i>
                        </div>
                        <span class="ml-1.5">{{ $term('payments') }}</span>
                    </a>
                    <a href="{{ route('reports.index') }}" class="flex items-center px-3 py-2 rounded-md hover:bg-primary-500 transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-primary-800 shadow-inner' : '' }} group">
                        <div class="p-1 {{ request()->routeIs('reports.*') ? 'bg-primary-600 rounded-full' : '' }} group-hover:bg-primary-600 group-hover:rounded-full transition-all duration-200">
                            <i class="fas fa-chart-line text-sm"></i>
                        </div>
                        <span class="ml-1.5">{{ $term('reports') }}</span>
                    </a>
                @endauth
            </div>

            <!-- User Menu -->
            <div class="hidden md:flex items-center space-x-2">
                @auth
                    <!-- School Selector -->
                    @if(session('current_school'))
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center px-3 py-2 text-white bg-primary-800/40 hover:bg-primary-500 rounded-full transition-colors duration-200">
                                @if(session('current_school')->logo)
                                    <img src="{{ asset('storage/' . session('current_school')->logo) }}" class="h-5 w-5 rounded-full mr-1.5 object-cover" alt="{{ session('current_school')->name }}">
                                @else
                                    <i class="fas fa-school mr-1.5 text-primary-200"></i>
                                @endif
                                <span class="mr-1.5 text-sm">{{ session('current_school')->name }}</span>
                                <i class="fas fa-chevron-down text-xs opacity-70"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl py-1 text-gray-700 z-10 border border-gray-100">
                                <div class="px-4 py-2 text-xs text-gray-500 border-b border-gray-100">Écoles disponibles</div>
                                
                                @forelse (auth()->user()->schools as $school)
                                    <a href="{{ route('schools.switch', ['school' => $school->id]) }}" class="flex items-center px-4 py-2 hover:bg-gray-50 {{ session('current_school_id') == $school->id ? 'bg-primary-50' : '' }}">
                                        <div class="flex items-center flex-1">
                                            @if($school->logo)
                                                <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="w-5 h-5 mr-2 rounded-full object-cover">
                                            @else
                                                <i class="fas fa-school mr-2 text-primary-400"></i>
                                            @endif
                                            <span class="text-sm">{{ $school->name }}</span>
                                        </div>
                                        @if(session('current_school_id') == $school->id)
                                            <span class="h-5 w-5 bg-primary-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check text-xs text-primary-600"></i>
                                            </span>
                                        @endif
                                    </a>
                                @empty
                                    <div class="px-4 py-2 text-sm text-gray-500">Aucune école disponible</div>
                                @endforelse
                                
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="{{ route('schools.settings', session('current_school')) }}" class="block px-4 py-2 hover:bg-gray-50 text-sm">
                                    <i class="fas fa-cog mr-2 text-primary-400"></i>Paramètres de l'école
                                </a>
                                <a href="{{ route('schools.index') }}" class="block px-4 py-2 hover:bg-gray-50 text-sm">
                                    <i class="fas fa-list mr-2 text-primary-400"></i>Gérer les écoles
                                </a>
                            </div>
                        </div>
                    @endif
                
                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center px-3 py-2 text-white bg-primary-800/40 hover:bg-primary-500 rounded-full transition-colors duration-200 ml-1">
                            <div class="w-6 h-6 rounded-full bg-primary-200 flex items-center justify-center mr-1.5">
                                <i class="fas fa-user text-xs text-primary-700"></i>
                            </div>
                            <span class="mr-1.5 text-sm">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs opacity-70"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-1 text-gray-700 z-10 border border-gray-100">
                            <a href="{{ route('profile.index') }}" class="flex items-center px-4 py-2 hover:bg-gray-50 text-sm group">
                                <i class="fas fa-user mr-2 text-primary-400 group-hover:text-primary-600 transition-colors"></i>{{ __('Mon profil') }}
                            </a>
                            @if(session('current_school'))
                            <a href="{{ route('schools.settings', session('current_school')) }}" class="flex items-center px-4 py-2 hover:bg-gray-50 text-sm group">
                                <i class="fas fa-cog mr-2 text-primary-400 group-hover:text-primary-600 transition-colors"></i>{{ __('Paramètres') }}
                            </a>
                            @endif
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full text-left px-4 py-2 hover:bg-gray-50 text-sm group">
                                    <i class="fas fa-sign-out-alt mr-2 text-primary-400 group-hover:text-primary-600 transition-colors"></i>{{ __('Déconnexion') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
            
            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-white hover:bg-primary-500 p-2 rounded-full transition-colors duration-200">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-primary-800 pb-3 pt-1 shadow-inner">
        @auth
            <a href="{{ route('dashboard') }}" class="flex items-center mx-3 px-3 py-2 my-1 rounded-md hover:bg-primary-600 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-primary-700' : '' }}">
                <div class="p-1.5 bg-primary-700/50 rounded-full">
                    <i class="fas fa-tachometer-alt text-sm"></i>
                </div>
                <span class="ml-2">{{ $term('dashboard') }}</span>
            </a>
            <a href="{{ route('campuses.index') }}" class="flex items-center mx-3 px-3 py-2 my-1 rounded-md hover:bg-primary-600 transition-all duration-200 {{ request()->routeIs('campuses.*') ? 'bg-primary-700' : '' }}">
                <div class="p-1.5 bg-primary-700/50 rounded-full">
                    <i class="fas fa-school text-sm"></i>
                </div>
                <span class="ml-2">{{ $term('campuses') }}</span>
            </a>
            <a href="{{ route('fields.index') }}" class="flex items-center mx-3 px-3 py-2 my-1 rounded-md hover:bg-primary-600 transition-all duration-200 {{ request()->routeIs('fields.*') ? 'bg-primary-700' : '' }}">
                <div class="p-1.5 bg-primary-700/50 rounded-full">
                    <i class="fas fa-graduation-cap text-sm"></i>
                </div>
                <span class="ml-2">{{ $term('fields') }}</span>
            </a>
            <a href="{{ route('students.index') }}" class="flex items-center mx-3 px-3 py-2 my-1 rounded-md hover:bg-primary-600 transition-all duration-200 {{ request()->routeIs('students.*') ? 'bg-primary-700' : '' }}">
                <div class="p-1.5 bg-primary-700/50 rounded-full">
                    <i class="fas fa-user-graduate text-sm"></i>
                </div>
                <span class="ml-2">{{ $term('students') }}</span>
            </a>
            <a href="{{ route('payments.index') }}" class="flex items-center mx-3 px-3 py-2 my-1 rounded-md hover:bg-primary-600 transition-all duration-200 {{ request()->routeIs('payments.*') ? 'bg-primary-700' : '' }}">
                <div class="p-1.5 bg-primary-700/50 rounded-full">
                    <i class="fas fa-money-bill-wave text-sm"></i>
                </div>
                <span class="ml-2">{{ $term('payments') }}</span>
            </a>
            <a href="{{ route('reports.index') }}" class="flex items-center mx-3 px-3 py-2 my-1 rounded-md hover:bg-primary-600 transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-primary-700' : '' }}">
                <div class="p-1.5 bg-primary-700/50 rounded-full">
                    <i class="fas fa-chart-line text-sm"></i>
                </div>
                <span class="ml-2">{{ $term('reports') }}</span>
            </a>
            <div class="border-t border-primary-600/50 mx-3 my-2"></div>
            <a href="{{ route('profile.index') }}" class="flex items-center mx-3 px-3 py-2 my-1 rounded-md hover:bg-primary-600 transition-all duration-200">
                <div class="p-1.5 bg-primary-700/50 rounded-full">
                    <i class="fas fa-user text-sm"></i>
                </div>
                <span class="ml-2">{{ __('Mon profil') }}</span>
            </a>
            @if(session('current_school'))
            <a href="{{ route('schools.settings', session('current_school')) }}" class="flex items-center mx-3 px-3 py-2 my-1 rounded-md hover:bg-primary-600 transition-all duration-200">
                <div class="p-1.5 bg-primary-700/50 rounded-full">
                    <i class="fas fa-cog text-sm"></i>
                </div>
                <span class="ml-2">{{ __('Paramètres') }}</span>
            </a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="mx-3 px-3 py-2 my-1">
                @csrf
                <button type="submit" class="w-full flex items-center text-left hover:bg-primary-600 rounded-md p-2 transition-all duration-200">
                    <div class="p-1.5 bg-primary-700/50 rounded-full">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </div>
                    <span class="ml-2">{{ __('Déconnexion') }}</span>
                </button>
            </form>
        @endauth
    </div>
</nav>

<script>
    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    });
</script> 