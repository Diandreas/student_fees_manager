<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Student Fees Manager') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
<nav class="bg-primary-600 shadow-md">
    <div class="container mx-auto px-4 py-2">
        <div class="flex justify-between items-center">
            <a class="text-white text-xl font-bold" href="{{ url('/') }}">
                {{ config('app.name', 'Student Fees Manager') }}
            </a>

            @auth
                <div class="hidden md:block">
                    <div class="flex space-x-4">
                        <a class="text-white hover:bg-primary-700 px-3 py-2 rounded-md text-sm font-medium" href="{{ route('dashboard') }}">Dashboard</a>
                        <a class="text-white hover:bg-primary-700 px-3 py-2 rounded-md text-sm font-medium" href="{{ route('campuses.index') }}">Campuses</a>
                        <a class="text-white hover:bg-primary-700 px-3 py-2 rounded-md text-sm font-medium" href="{{ route('fields.index') }}">Fields</a>
                        <a class="text-white hover:bg-primary-700 px-3 py-2 rounded-md text-sm font-medium" href="{{ route('students.index') }}">Students</a>
                        <a class="text-white hover:bg-primary-700 px-3 py-2 rounded-md text-sm font-medium" href="{{ route('payments.index') }}">Payments</a>
                    </div>
                </div>

                <div class="hidden md:block">
                    <div class="relative group">
                        <button class="flex items-center text-white hover:bg-primary-700 px-3 py-2 rounded-md text-sm font-medium">
                            {{ Auth::user()->username }}
                            <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden group-hover:block">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="md:hidden">
                    <button type="button" class="text-white hover:bg-primary-700 inline-flex items-center justify-center p-2 rounded-md" id="mobile-menu-button">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            @endauth
        </div>

        @auth
            <div class="hidden md:hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a class="text-white hover:bg-primary-700 block px-3 py-2 rounded-md text-base font-medium" href="{{ route('dashboard') }}">Dashboard</a>
                    <a class="text-white hover:bg-primary-700 block px-3 py-2 rounded-md text-base font-medium" href="{{ route('campuses.index') }}">Campuses</a>
                    <a class="text-white hover:bg-primary-700 block px-3 py-2 rounded-md text-base font-medium" href="{{ route('fields.index') }}">Fields</a>
                    <a class="text-white hover:bg-primary-700 block px-3 py-2 rounded-md text-base font-medium" href="{{ route('students.index') }}">Students</a>
                    <a class="text-white hover:bg-primary-700 block px-3 py-2 rounded-md text-base font-medium" href="{{ route('payments.index') }}">Payments</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-white hover:bg-primary-700 block w-full text-left px-3 py-2 rounded-md text-base font-medium">Logout</button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>

<main class="py-6">
    <div class="container mx-auto px-4">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</main>

<script>
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>
</body>
</html>
