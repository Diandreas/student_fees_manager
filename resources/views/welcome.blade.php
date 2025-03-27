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
            body {
                font-family: 'Poppins', sans-serif;
            }
            .hero-pattern {
                background-color: rgba(34, 197, 94, 0.05);
                background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2322c55e' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            }
        </style>
    </head>
    <body class="antialiased bg-gray-50 text-gray-700">
        <div class="hero-pattern min-h-screen">
            <div class="relative min-h-screen flex flex-col items-center justify-center">
                <div class="relative w-full max-w-2xl px-6 lg:max-w-5xl">
                    <header class="flex justify-between items-center py-8">
                        <div class="flex items-center">
                            <div class="bg-primary-600 text-white p-3 rounded-lg shadow-md mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <h1 class="text-2xl font-bold text-primary-700">{{ config('app.name', 'Student Fees Manager') }}</h1>
                        </div>
                        
                        @if (Route::has('login'))
                            <nav class="flex space-x-4">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-primary-700 transition">
                                        Tableau de bord
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="bg-white text-primary-600 px-4 py-2 rounded-lg shadow-sm border border-gray-200 hover:bg-gray-50 transition">
                                        Connexion
                                    </a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-primary-700 transition">
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
                                    <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-primary-500">
                                        <div class="text-primary-600 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <h3 class="font-medium text-gray-800">Paiements</h3>
                                        <p class="text-sm text-gray-600">Suivi des paiements et historique complet</p>
                                    </div>
                                    
                                    <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-secondary-500">
                                        <div class="text-secondary-600 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <h3 class="font-medium text-gray-800">Étudiants</h3>
                                        <p class="text-sm text-gray-600">Gestion des dossiers étudiants</p>
                                    </div>
                                    
                                    <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-accent">
                                        <div class="text-accent mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <h3 class="font-medium text-gray-800">Rapports</h3>
                                        <p class="text-sm text-gray-600">Analyses et statistiques détaillées</p>
                                    </div>
                                    
                                    <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-primary-500">
                                        <div class="text-primary-600 mb-2">
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
                                    <a href="{{ route('login') }}" class="bg-primary-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-primary-700 transition text-center">
                                        Commencer maintenant
                                    </a>
                                </div>
                                @endguest
                            </div>
                            
                            <div class="lg:w-1/2">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-primary-600 rounded-3xl opacity-10 transform rotate-3"></div>
                                    <div class="relative bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                                        <div class="bg-primary-600 text-white p-4">
                                            <h4 class="font-medium">Tableau de bord</h4>
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
