<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Student Fees Manager') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        
            <style>
            :root {
                --blue-presidential: #0A2463;
                --blue-secondary: #1E5F8C;
                --blue-accent: #4891C5;
                --blue-light: #EBF2FA;
                --blue-highlight: #0047AB;
            }
            
            body {
                font-family: 'Poppins', sans-serif;
            }
            
            .hero-pattern {
                background-color: rgba(10, 36, 99, 0.03);
                background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%230A2463' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            }
            
            .feature-card {
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }
            
            .feature-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px -5px rgba(10, 36, 99, 0.1);
            }
            
            .feature-card::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 3px;
                transform: scaleX(0);
                transform-origin: right;
                transition: transform 0.4s ease;
            }
            
            .feature-card:hover::after {
                transform: scaleX(1);
                transform-origin: left;
            }
            
            .feature-card.blue-primary::after {
                background-color: var(--blue-presidential);
            }
            
            .feature-card.blue-secondary::after {
                background-color: var(--blue-secondary);
            }
            
            .feature-card.blue-accent::after {
                background-color: var(--blue-accent);
            }
            
            .btn-primary {
                background-color: var(--blue-presidential);
                color: white;
                transition: all 0.3s ease;
            }
            
            .btn-primary:hover {
                background-color: var(--blue-highlight);
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(10, 36, 99, 0.2);
            }
            
            .btn-secondary {
                background-color: white;
                color: var(--blue-presidential);
                border: 1px solid rgba(10, 36, 99, 0.2);
                transition: all 0.3s ease;
            }
            
            .btn-secondary:hover {
                border-color: var(--blue-presidential);
                transform: translateY(-2px);
            }
            
            .dashboard-preview {
                box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.1);
                transition: all 0.5s ease;
            }
            
            .dashboard-preview:hover {
                transform: translateY(-10px) rotate(0deg) !important;
                box-shadow: 0 30px 40px -15px rgba(0, 0, 0, 0.15);
            }
            </style>
    </head>
    <body class="antialiased bg-gray-50 text-gray-700">
        <div class="hero-pattern min-h-screen">
            <div class="relative min-h-screen flex flex-col items-center justify-center">
                <div class="relative w-full max-w-2xl px-6 lg:max-w-5xl">
                    <header class="flex justify-between items-center py-8">
                        <div class="flex items-center">
                            <div style="background-color: var(--blue-presidential);" class="text-white p-3 rounded-lg shadow-md mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <h1 class="text-2xl font-bold" style="color: var(--blue-presidential);">{{ config('app.name', 'Student Fees Manager') }}</h1>
                        </div>
                        
                        @if (Route::has('login'))
                            <nav class="flex space-x-4">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="btn-primary px-4 py-2 rounded-lg shadow-sm transition">
                                        Tableau de bord
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn-secondary px-4 py-2 rounded-lg shadow-sm transition">
                                        Connexion
                                    </a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn-primary px-4 py-2 rounded-lg shadow-sm transition">
                                            Inscription
                                        </a>
                                    @endif
                                @endauth
                            </nav>
                        @endif
                    </header>

                    <main class="py-10">
                        <div class="flex flex-col lg:flex-row items-center gap-12">
                            <div class="lg:w-1/2">
                                <h2 class="text-4xl font-bold text-gray-800 mb-6">Gestion des Frais Étudiants</h2>
                                <p class="text-lg text-gray-600 mb-8">Un système complet pour gérer les paiements des frais de scolarité, suivre les étudiants et générer des rapports détaillés.</p>
                                
                                <div class="grid grid-cols-2 gap-6 mb-8">
                                    <div class="feature-card blue-primary bg-white p-5 rounded-lg shadow-sm border-l-4" style="border-color: var(--blue-presidential);">
                                        <div class="mb-3" style="color: var(--blue-presidential);">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <h3 class="font-medium text-gray-800">Paiements</h3>
                                        <p class="text-sm text-gray-600">Suivi des paiements et historique complet</p>
                                    </div>

                                    <div class="feature-card blue-secondary bg-white p-5 rounded-lg shadow-sm border-l-4" style="border-color: var(--blue-secondary);">
                                        <div class="mb-3" style="color: var(--blue-secondary);">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <h3 class="font-medium text-gray-800">Étudiants</h3>
                                        <p class="text-sm text-gray-600">Gestion des dossiers étudiants</p>
                                    </div>

                                    <div class="feature-card blue-accent bg-white p-5 rounded-lg shadow-sm border-l-4" style="border-color: var(--blue-accent);">
                                        <div class="mb-3" style="color: var(--blue-accent);">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <h3 class="font-medium text-gray-800">Rapports</h3>
                                        <p class="text-sm text-gray-600">Analyses et statistiques détaillées</p>
                                    </div>

                                    <div class="feature-card blue-primary bg-white p-5 rounded-lg shadow-sm border-l-4" style="border-color: var(--blue-presidential);">
                                        <div class="mb-3" style="color: var(--blue-presidential);">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <h3 class="font-medium text-gray-800">Campus</h3>
                                        <p class="text-sm text-gray-600">Gestion multi-campus</p>
                                    </div>
                                </div>

                                @guest
                                <div class="flex space-x-4">
                                    <a href="{{ route('login') }}" class="btn-primary px-6 py-3 rounded-lg shadow-md transition text-center font-medium flex items-center">
                                        Commencer maintenant
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                                @endguest
                            </div>

                            <div class="lg:w-1/2">
                                <div class="relative">
                                    <div class="absolute inset-0 rounded-3xl opacity-10 transform rotate-3" style="background-color: var(--blue-presidential);"></div>
                                    <div class="dashboard-preview relative bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                                        <div class="p-4 text-white" style="background-color: var(--blue-presidential);">
                                            <div class="flex items-center justify-between">
                                                <h4 class="font-medium">Tableau de bord administrateur</h4>
                                                <div class="flex space-x-2">
                                                    <span class="h-3 w-3 rounded-full bg-red-400"></span>
                                                    <span class="h-3 w-3 rounded-full bg-yellow-400"></span>
                                                    <span class="h-3 w-3 rounded-full bg-green-400"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <img src="https://images.unsplash.com/photo-1606761568499-6d2451b23c66?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTJ8fGRhc2hib2FyZHxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=600&q=60" 
                                             alt="Dashboard Preview" class="w-full h-auto">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>

                    <footer class="py-8 text-center text-sm text-gray-500 border-t border-gray-200">
                        <p>{{ config('app.name', 'Student Fees Manager') }} &copy; {{ date('Y') }} | Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p>
                    </footer>
                </div>
            </div>
        </div>
    </body>
</html>