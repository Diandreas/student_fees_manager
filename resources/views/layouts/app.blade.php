<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} @if(session('current_school')) - {{ session('current_school')->name }} @endif</title>
    
    <!-- Favicon -->
    @if(session('current_school') && session('current_school')->logo)
        <link rel="icon" href="{{ asset('storage/' . session('current_school')->logo) }}" type="image/x-icon">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @endif

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    @if(session('current_school'))
    <style>
        :root {
            --primary-color: {{ session('current_school')->theme_color ?? '#1a56db' }};
            --primary-color-light: {{ session('current_school')->theme_color ?? '#1a56db' }}26;
            --secondary-color: {{ session('current_school')->secondary_color ?? '#9061F9' }};
            --header-color: {{ session('current_school')->header_color ?? '#1E40AF' }};
            --sidebar-color: {{ session('current_school')->sidebar_color ?? '#1E293B' }};
            --text-color: {{ session('current_school')->text_color ?? '#334155' }};
            --accent-color: {{ session('current_school')->secondary_color ?? '#9061F9' }};
            --font-family: "{{ session('current_school')->font_family ?? 'Nunito' }}", sans-serif;
        }
        
        body {
            font-family: var(--font-family);
            color: var(--text-color);
        }
        
        .bg-primary-custom {
            background-color: var(--primary-color) !important;
        }
        
        .text-primary-custom {
            color: var(--primary-color) !important;
        }
        
        .border-primary-custom {
            border-color: var(--primary-color) !important;
        }
        
        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary-custom:hover {
            background-color: color-mix(in srgb, var(--primary-color) 80%, black);
            border-color: color-mix(in srgb, var(--primary-color) 80%, black);
            color: white;
        }
        
        .btn-outline-primary-custom {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .btn-outline-primary-custom:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .sidebar {
            background-color: var(--sidebar-color);
        }
        
        .navbar-custom {
            background-color: var(--header-color);
        }
    </style>
    @endif
    <style>
        :root {
            /* Définition des couleurs par défaut */
            --primary-color: {{ session('current_school') ? session('current_school')->primary_color : ($themeColors['primary'] ?? '#0A3D62') }};
            --secondary-color: {{ session('current_school') ? session('current_school')->secondary_color : ($themeColors['secondary'] ?? '#1E5B94') }}; 
            --accent-color: {{ session('current_school') ? session('current_school')->theme_color : ($themeColors['accent'] ?? '#D4AF37') }};
            --dark-blue: {{ session('current_school') ? session('current_school')->header_color : ($themeColors['dark_blue'] ?? '#071E3D') }};
            --white: #FFFFFF;
            --light-gray: #F5F7FA;
            --medium-gray: #E0E7EF;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray);
        }
        
        /* Couleurs principales */
        .bg-primary-custom {
            background-color: var(--primary-color) !important;
        }
        .text-primary-custom {
            color: var(--primary-color) !important;
        }
        .bg-secondary-custom {
            background-color: var(--secondary-color) !important;
        }
        .text-secondary-custom {
            color: var(--secondary-color) !important;
        }
        .bg-accent-custom {
            background-color: var(--accent-color) !important;
        }
        .text-accent-custom {
            color: var(--accent-color) !important;
        }
        .bg-dark-blue-custom {
            background-color: var(--dark-blue) !important;
        }
        
        /* Styles d'interface */
        .card {
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s;
            border: none;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        
        /* Styles des boutons */
        .btn-primary-custom {
            background-color: var(--primary-color);
            color: var(--white);
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
        }
        .btn-primary-custom:hover {
            background-color: var(--secondary-color);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .btn-secondary-custom {
            background-color: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            border-radius: 8px;
            transition: all 0.2s;
        }
        .btn-secondary-custom:hover {
            background-color: var(--primary-color);
            color: var(--white);
        }
        .btn-accent-custom {
            background-color: var(--accent-color);
            color: var(--dark-blue);
            border-radius: 8px;
            border: none;
            transition: all 0.2s;
        }
        
        /* Navbar */
        .navbar-custom {
            padding: 0.8rem 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .nav-link {
            font-weight: 500;
            transition: color 0.2s;
            margin: 0 0.5rem;
            position: relative;
        }
        .nav-link:hover::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--accent-color);
            transition: width 0.3s;
        }
        
        /* Logo de l'école */
        .school-logo {
            height: 40px;
            width: auto;
            margin-right: 10px;
        }
        
        /* Footer */
        .footer {
            background-color: var(--dark-blue);
            color: var(--white);
            padding: 2rem 0;
            margin-top: 3rem;
        }
        .footer::before {
            content: '';
            display: block;
            height: 3px;
            background-color: var(--accent-color);
            margin-top: -2rem;
            margin-bottom: 2rem;
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            .card {
                margin-bottom: 1rem;
            }
            .navbar-toggler {
                border-color: rgba(255, 255, 255, 0.2);
            }
        }
        
        /* Correctif pour le problème de contraste */
        .bg-primary-custom.bg-opacity-10 .text-white,
        .badge.bg-primary-custom.bg-opacity-10 {
            color: var(--primary-color) !important;
        }
        
        /* Pour améliorer la lisibilité */
        .text-on-primary {
            color: white !important;
        }
        .text-on-dark {
            color: var(--white) !important;
        }
        
        /* Table styles */
        .table-custom th {
            background-color: var(--light-gray);
            color: var(--primary-color);
            font-weight: 600;
        }
        .table-custom tr:hover {
            background-color: rgba(224, 231, 239, 0.5);
        }
        
        /* Form styles */
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(30, 91, 148, 0.25);
        }
        
        /* Component display switcher */
        .display-mode-switcher {
            display: flex;
            margin-bottom: 1rem;
        }
        .display-mode-switcher .btn {
            border-radius: 0;
            padding: 0.5rem 1rem;
        }
        .display-mode-switcher .btn:first-child {
            border-radius: 6px 0 0 6px;
        }
        .display-mode-switcher .btn:last-child {
            border-radius: 0 6px 6px 0;
        }
        .display-mode-switcher .btn.active {
            background-color: var(--primary-color);
            color: var(--white);
        }
    </style>
    <script>
        // Appliquer les couleurs du thème depuis localStorage
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier s'il y a des préférences de couleurs stockées
            const storedPrimaryColor = localStorage.getItem('theme-primary');
            const storedSecondaryColor = localStorage.getItem('theme-secondary');
            const storedAccentColor = localStorage.getItem('theme-accent');
            const storedDarkBlue = localStorage.getItem('theme-dark-blue');
            
            // Appliquer les couleurs stockées s'il y en a
            if (storedPrimaryColor) {
                document.documentElement.style.setProperty('--primary-color', storedPrimaryColor);
            }
            
            if (storedSecondaryColor) {
                document.documentElement.style.setProperty('--secondary-color', storedSecondaryColor);
            }
            
            if (storedAccentColor) {
                document.documentElement.style.setProperty('--accent-color', storedAccentColor);
            }
            
            if (storedDarkBlue) {
                document.documentElement.style.setProperty('--dark-blue', storedDarkBlue);
            }
        });
    </script>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary-custom shadow navbar-custom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            @if(session('current_school') && session('current_school')->logo)
                <img src="{{ asset('storage/' . session('current_school')->logo) }}" alt="{{ session('current_school')->name }}" class="school-logo">
            @else
                <i class="fas fa-graduation-cap me-2"></i>
            @endif
            {{ session('current_school') ? session('current_school')->name : config('app.name', 'Student Fees Manager') }}
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            @auth
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i>{{ $term('dashboard') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('campuses.*') ? 'active' : '' }}" href="{{ route('campuses.index') }}">
                        <i class="fas fa-school me-1"></i>{{ $term('campuses') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('fields.*') ? 'active' : '' }}" href="{{ route('fields.index') }}">
                        <i class="fas fa-graduation-cap me-1"></i>{{ $term('fields') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                        <i class="fas fa-user-graduate me-1"></i>{{ $term('students') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}">
                        <i class="fas fa-money-bill-wave me-1"></i>{{ $term('payments') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                        <i class="fas fa-chart-line me-1"></i>{{ $term('reports') }}
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="#" id="searchToggle" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                </li>
                
                @if(auth()->user()->isAdmin())
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="schoolDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-building me-1"></i>
                        {{ session('current_school') ? session('current_school')->name : 'Choisir une école' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="schoolDropdown">
                        <li class="px-3 py-1 text-muted fw-light small">Écoles disponibles</li>
                        <li><hr class="dropdown-divider"></li>
                        
                        @forelse (auth()->user()->schools as $school)
                            <li>
                                <a class="dropdown-item d-flex align-items-center {{ session('current_school_id') == $school->id ? 'bg-light' : '' }}" 
                                   href="{{ route('schools.select', ['school' => $school->id]) }}">
                                    @if($school->logo)
                                        <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="me-2" style="width: 20px; height: 20px; object-fit: contain;">
                                    @else
                                        <i class="fas fa-school me-2 text-secondary"></i>
                                    @endif
                                    {{ $school->name }}
                                    @if(session('current_school_id') == $school->id)
                                        <i class="fas fa-check ms-auto text-success"></i>
                                    @endif
                                </a>
                            </li>
                        @empty
                            <li><span class="dropdown-item text-muted">Aucune école disponible</span></li>
                        @endforelse
                        
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('schools.index') }}">
                                <i class="fas fa-cog me-2"></i>Gérer les écoles
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <i class="fas fa-user-circle me-1"></i>
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('profile.index') }}">
                            <i class="fas fa-user me-2"></i>{{ __('Mon profil') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('settings.index') }}">
                            <i class="fas fa-cog me-2"></i>{{ __('Paramètres') }}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>{{ __('Déconnexion') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
            
            <!-- Search Form -->
            <div id="searchForm" class="d-none position-absolute top-100 start-0 end-0 py-2 px-4 bg-white shadow border-top" style="z-index: 1000;">
                <form class="d-flex w-100" action="#" method="GET">
                    <input class="form-control me-2" type="search" placeholder="Rechercher..." aria-label="Search">
                    <button class="btn btn-primary-custom" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </div>
</nav>

<main class="py-4">
    <div class="container-fluid px-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 10px;">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 10px;">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>
</main>

<footer class="footer">
    <div class="container">
        <div class="text-center">
            <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'Student Fees Manager') }}. Tous droits réservés.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle search form
        const searchToggle = document.getElementById('searchToggle');
        const searchForm = document.getElementById('searchForm');
        
        if (searchToggle && searchForm) {
            searchToggle.addEventListener('click', function(e) {
                e.preventDefault();
                searchForm.classList.toggle('d-none');
                if (!searchForm.classList.contains('d-none')) {
                    searchForm.querySelector('input').focus();
                }
            });
            
            // Close search when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchForm.contains(e.target) && e.target !== searchToggle) {
                    searchForm.classList.add('d-none');
                }
            });
        }
        
        // Add component display mode switcher functionality
        const displayModeSwitchers = document.querySelectorAll('.display-mode-switcher .btn');
        displayModeSwitchers.forEach(btn => {
            btn.addEventListener('click', function() {
                const mode = this.getAttribute('data-mode');
                const containerId = this.closest('.display-mode-switcher').getAttribute('data-target');
                const container = document.getElementById(containerId);
                
                // Remove active class from all buttons
                this.parentNode.querySelectorAll('.btn').forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                // Update display mode
                if (container) {
                    container.className = container.className.replace(/mode-\w+/g, '');
                    container.classList.add('mode-' + mode);
                }
            });
        });
    });
</script>

@stack('scripts')
</body>
<!-- Inclure le composant pour changer les couleurs du thème -->
<x-theme-changer-modal />
</html>
