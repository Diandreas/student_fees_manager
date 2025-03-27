<nav class="bg-primary-600 text-white shadow-md">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center">
                @if(session('current_school') && session('current_school')->logo)
                    <img src="{{ asset('storage/' . session('current_school')->logo) }}" alt="{{ session('current_school')->name }}" class="h-8 w-auto mr-2">
                @else
                    <i class="fas fa-graduation-cap mr-2"></i>
                @endif
                <span class="font-bold text-lg">{{ session('current_school') ? session('current_school')->name : config('app.name', 'Student Fees Manager') }}</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-1">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md hover:bg-primary-700 {{ request()->routeIs('dashboard') ? 'bg-primary-700' : '' }}">
                        <i class="fas fa-tachometer-alt mr-1"></i>{{ $term('dashboard') }}
                    </a>
                    <a href="{{ route('campuses.index') }}" class="px-3 py-2 rounded-md hover:bg-primary-700 {{ request()->routeIs('campuses.*') ? 'bg-primary-700' : '' }}">
                        <i class="fas fa-school mr-1"></i>{{ $term('campuses') }}
                    </a>
                    <a href="{{ route('fields.index') }}" class="px-3 py-2 rounded-md hover:bg-primary-700 {{ request()->routeIs('fields.*') ? 'bg-primary-700' : '' }}">
                        <i class="fas fa-graduation-cap mr-1"></i>{{ $term('fields') }}
                    </a>
                    <a href="{{ route('students.index') }}" class="px-3 py-2 rounded-md hover:bg-primary-700 {{ request()->routeIs('students.*') ? 'bg-primary-700' : '' }}">
                        <i class="fas fa-user-graduate mr-1"></i>{{ $term('students') }}
                    </a>
                    <a href="{{ route('payments.index') }}" class="px-3 py-2 rounded-md hover:bg-primary-700 {{ request()->routeIs('payments.*') ? 'bg-primary-700' : '' }}">
                        <i class="fas fa-money-bill-wave mr-1"></i>{{ $term('payments') }}
                    </a>
                    <a href="{{ route('reports.index') }}" class="px-3 py-2 rounded-md hover:bg-primary-700 {{ request()->routeIs('reports.*') ? 'bg-primary-700' : '' }}">
                        <i class="fas fa-chart-line mr-1"></i>{{ $term('reports') }}
                    </a>
                @endauth
            </div>

            <!-- User Menu -->
            <div class="hidden md:flex items-center">
                @auth
                    <!-- School Selector -->
                    @if(auth()->user()->isAdmin())
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center px-3 py-2 text-white hover:bg-primary-700 rounded-md mr-2">
                                <i class="fas fa-building mr-1"></i>
                                <span class="mr-1">{{ session('current_school') ? session('current_school')->name : 'Choisir une école' }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 text-gray-700 z-10">
                                <div class="px-4 py-2 text-xs text-gray-500">Écoles disponibles</div>
                                <div class="border-t border-gray-100"></div>
                                
                                @forelse (auth()->user()->schools as $school)
                                    <a href="{{ route('schools.switch', ['school' => $school->id]) }}" class="block px-4 py-2 hover:bg-gray-100 {{ session('current_school_id') == $school->id ? 'bg-gray-50' : '' }}">
                                        <div class="flex items-center">
                                            @if($school->logo)
                                                <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="w-5 h-5 mr-2 object-contain">
                                            @else
                                                <i class="fas fa-school mr-2 text-gray-400"></i>
                                            @endif
                                            <span>{{ $school->name }}</span>
                                            @if(session('current_school_id') == $school->id)
                                                <i class="fas fa-check ml-auto text-green-500"></i>
                                            @endif
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-2 text-sm text-gray-500">Aucune école disponible</div>
                                @endforelse
                                
                                <div class="border-t border-gray-100"></div>
                                <a href="{{ route('schools.index') }}" class="block px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i>Gérer les écoles
                                </a>
                            </div>
                        </div>
                    @endif
                
                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center px-3 py-2 text-white hover:bg-primary-700 rounded-md">
                            <i class="fas fa-user-circle mr-1"></i>
                            <span class="mr-1">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 text-gray-700 z-10">
                            <a href="{{ route('profile.index') }}" class="block px-4 py-2 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i>{{ __('Mon profil') }}
                            </a>
                            <a href="{{ route('settings.index') }}" class="block px-4 py-2 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2"></i>{{ __('Paramètres') }}
                            </a>
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>{{ __('Déconnexion') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
            
            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-white hover:bg-primary-700 p-2 rounded-md">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-primary-700 pb-2">
        @auth
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-primary-600 {{ request()->routeIs('dashboard') ? 'bg-primary-600' : '' }}">
                <i class="fas fa-tachometer-alt mr-1"></i>{{ $term('dashboard') }}
            </a>
            <a href="{{ route('campuses.index') }}" class="block px-4 py-2 hover:bg-primary-600 {{ request()->routeIs('campuses.*') ? 'bg-primary-600' : '' }}">
                <i class="fas fa-school mr-1"></i>{{ $term('campuses') }}
            </a>
            <a href="{{ route('fields.index') }}" class="block px-4 py-2 hover:bg-primary-600 {{ request()->routeIs('fields.*') ? 'bg-primary-600' : '' }}">
                <i class="fas fa-graduation-cap mr-1"></i>{{ $term('fields') }}
            </a>
            <a href="{{ route('students.index') }}" class="block px-4 py-2 hover:bg-primary-600 {{ request()->routeIs('students.*') ? 'bg-primary-600' : '' }}">
                <i class="fas fa-user-graduate mr-1"></i>{{ $term('students') }}
            </a>
            <a href="{{ route('payments.index') }}" class="block px-4 py-2 hover:bg-primary-600 {{ request()->routeIs('payments.*') ? 'bg-primary-600' : '' }}">
                <i class="fas fa-money-bill-wave mr-1"></i>{{ $term('payments') }}
            </a>
            <a href="{{ route('reports.index') }}" class="block px-4 py-2 hover:bg-primary-600 {{ request()->routeIs('reports.*') ? 'bg-primary-600' : '' }}">
                <i class="fas fa-chart-line mr-1"></i>{{ $term('reports') }}
            </a>
            <div class="border-t border-primary-500 my-2"></div>
            <a href="{{ route('profile.index') }}" class="block px-4 py-2 hover:bg-primary-600">
                <i class="fas fa-user mr-1"></i>{{ __('Mon profil') }}
            </a>
            <a href="{{ route('settings.index') }}" class="block px-4 py-2 hover:bg-primary-600">
                <i class="fas fa-cog mr-1"></i>{{ __('Paramètres') }}
            </a>
            <form method="POST" action="{{ route('logout') }}" class="block px-4 py-2">
                @csrf
                <button type="submit" class="w-full text-left hover:bg-primary-600">
                    <i class="fas fa-sign-out-alt mr-1"></i>{{ __('Déconnexion') }}
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