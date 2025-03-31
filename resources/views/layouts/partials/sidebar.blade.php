<!-- Sidebar -->
<aside class="hidden lg:block w-64 bg-gray-800 text-white">
    <div class="p-4">
        <div class="mb-6">
            <h2 class="text-xl font-bold flex items-center">
                <i class="fas fa-user-graduate mr-2"></i>
                @if(session('current_school'))
                    {{ session('current_school')->term('student_fees_manager', 'Gestion des Frais') }}
                @else
                    Gestion des Frais
                @endif
            </h2>
        </div>
        
        <nav class="space-y-1">
            <div class="mb-2 text-xs uppercase text-gray-400 font-semibold tracking-wider">
                {{ __('Navigation principale') }}
            </div>
            
            <a href="{{ route('dashboard') }}" class="block py-2.5 px-4 rounded transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span>{{ $term('dashboard') }}</span>
                </div>
            </a>
            
            <a href="{{ route('students.index') }}" class="block py-2.5 px-4 rounded transition-colors {{ request()->routeIs('students.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-user-graduate w-6"></i>
                    <span>{{ $term('students') }}</span>
                </div>
            </a>
            
            <a href="{{ route('fields.index') }}" class="block py-2.5 px-4 rounded transition-colors {{ request()->routeIs('fields.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-graduation-cap w-6"></i>
                    <span>{{ $term('fields') }}</span>
                </div>
            </a>
            
            <a href="{{ route('campuses.index') }}" class="block py-2.5 px-4 rounded transition-colors {{ request()->routeIs('campuses.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-school w-6"></i>
                    <span>{{ $term('campuses') }}</span>
                </div>
            </a>
            
            <a href="{{ route('payments.index') }}" class="block py-2.5 px-4 rounded transition-colors {{ request()->routeIs('payments.index') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-money-bill-wave w-6"></i>
                    <span>{{ $term('payments') }}</span>
                </div>
            </a>
            
            <a href="{{ route('payments.quick') }}" class="block py-2.5 px-4 rounded transition-colors {{ request()->routeIs('payments.quick') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-bolt w-6"></i>
                    <span>{{ __('Paiement rapide') }}</span>
                </div>
            </a>
            
            <div class="my-3 border-t border-gray-700"></div>
            
            <div class="mb-2 text-xs uppercase text-gray-400 font-semibold tracking-wider">
                {{ __('Rapports & Analyses') }}
            </div>
            
            <a href="{{ route('reports.index') }}" class="block py-2.5 px-4 rounded transition-colors {{ request()->routeIs('reports.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-chart-line w-6"></i>
                    <span>{{ $term('reports') }}</span>
                </div>
            </a>
            
            <div class="my-3 border-t border-gray-700"></div>
            
            <div class="mb-2 text-xs uppercase text-gray-400 font-semibold tracking-wider">
                {{ __('Administration') }}
            </div>
            
            @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('schools.index') }}" class="block py-2.5 px-4 rounded transition-colors {{ request()->routeIs('schools.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <div class="flex items-center">
                        <i class="fas fa-building w-6"></i>
                        <span>{{ __('Écoles') }}</span>
                    </div>
                </a>
            @endif
            
            <a href="{{ route('profile.index') }}" class="block py-2.5 px-4 rounded transition-colors {{ request()->routeIs('profile.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-user-circle w-6"></i>
                    <span>{{ __('Mon profil') }}</span>
                </div>
            </a>
            
            @if(session('current_school'))
            <a href="{{ route('schools.settings', session('current_school')) }}" class="block py-2.5 px-4 rounded transition-colors {{ request()->routeIs('schools.settings') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-cog w-6"></i>
                    <span>{{ __('Paramètres') }}</span>
                </div>
            </a>
            @endif
        </nav>
    </div>
</aside> 