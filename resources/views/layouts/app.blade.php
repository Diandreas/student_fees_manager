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

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">
    <div id="app" class="flex flex-col min-h-screen">
        @include('layouts.partials.navbar')
        
        <div class="flex flex-1">
            {{-- @include('layouts.partials.sidebar') --}}
            
            <main class="flex-1 px-4 py-8 overflow-y-auto">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border-l-4 border-green-600 text-green-800 p-4 rounded shadow-sm" role="alert">
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border-l-4 border-red-600 text-red-800 p-4 rounded shadow-sm" role="alert">
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                @endif

                @if(session('info'))
                    <div class="mb-4 bg-blue-100 border-l-4 border-blue-600 text-blue-800 p-4 rounded shadow-sm" role="alert">
                        <p class="font-medium">{{ session('info') }}</p>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-4 bg-yellow-100 border-l-4 border-yellow-600 text-yellow-800 p-4 rounded shadow-sm" role="alert">
                        <p class="font-medium">{{ session('warning') }}</p>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
        
        <footer class="bg-white py-4 text-center text-gray-500 text-sm border-t">
            <p>© {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
        </footer>
    </div>
    
    @stack('scripts')
    
</body>
</html>
