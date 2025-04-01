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
    <div class="min-h-screen flex" x-data="{ 
        sidebarOpen: localStorage.getItem('sidebarOpen') === 'true', 
        mobileOpen: false,
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem('sidebarOpen', this.sidebarOpen);
        }
    }">
        <!-- Sidebar (desktop and mobile) -->
        @include('layouts.partials.sidebar')

        <!-- Main Content Area -->
        <div :class="{'lg:ml-64': sidebarOpen, 'lg:ml-20': !sidebarOpen}" class="flex-1 flex flex-col min-h-screen transition-all duration-300">
            <!-- Navbar -->
            @include('layouts.partials.navbar')

            <!-- Page Content -->
            <main class="flex-1 py-4 px-4 md:px-6 lg:px-8 overflow-hidden">
                <div class="mx-auto max-w-7xl">
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
            <footer class="bg-white border-t py-3 px-4 lg:px-8 mt-auto">
                <div class="max-w-7xl mx-auto">
                    <div class="flex flex-col md:flex-row md:justify-between items-center text-xs text-gray-500">
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