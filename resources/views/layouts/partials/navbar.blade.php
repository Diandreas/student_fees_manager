<nav class="navbar navbar-expand-md navbar-custom shadow-sm">
    <div class="container">
        @if(session('current_school'))
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                @if(session('current_school')->logo)
                    <img src="{{ asset('storage/' . session('current_school')->logo) }}" alt="{{ session('current_school')->name }}" class="me-2" style="height: 32px;">
                @else
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                        <span class="fw-bold" style="color: {{ session('current_school')->theme_color ?? '#1a56db' }};">
                            {{ substr(session('current_school')->name, 0, 1) }}
                        </span>
                    </div>
                @endif
                <span class="text-white">{{ session('current_school')->name }}</span>
            </a>
        @else
            <a class="navbar-brand text-white" href="{{ url('/') }}">
                {{ config('app.name', 'School Manager') }}
            </a>
        @endif
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                @auth
                    @if(session('current_school'))
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('dashboard') }}">
                                {{ session('current_school')->term('dashboard', 'Tableau de bord') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('students.index') }}">
                                {{ session('current_school')->term('students', 'Étudiants') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('payments.index') }}">
                                {{ session('current_school')->term('payments', 'Paiements') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('reports.index') }}">
                                {{ session('current_school')->term('reports', 'Rapports') }}
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('login') }}">{{ __('Connexion') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('register') }}">{{ __('Inscription') }}</a>
                        </li>
                    @endif
                @else
                    @if(session('current_school'))
                        <li class="nav-item dropdown">
                            <a id="schoolDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="fas fa-school me-1"></i> {{ session('current_school')->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="schoolDropdown">
                                <a class="dropdown-item" href="{{ route('schools.select') }}">
                                    <i class="fas fa-exchange-alt me-1"></i> Changer d'école
                                </a>
                                <a class="dropdown-item" href="{{ route('school.settings') }}">
                                    <i class="fas fa-cog me-1"></i> Paramètres de l'école
                                </a>
                            </div>
                        </li>
                    @endif
                    
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('profile.index') }}">
                                <i class="fas fa-user me-1"></i> Mon profil
                            </a>
                            <a class="dropdown-item" href="{{ route('settings.index') }}">
                                <i class="fas fa-cogs me-1"></i> Paramètres
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-1"></i> {{ __('Déconnexion') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav> 