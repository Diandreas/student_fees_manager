@php
$currentSchool = session('current_school');
$user = auth()->user();
@endphp

<nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 fixed z-30 w-full">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
                <button id="toggleSidebarMobile" aria-expanded="true" aria-controls="sidebar" class="lg:hidden mr-2 text-gray-600 hover:text-gray-900 cursor-pointer p-2 hover:bg-gray-100 focus:bg-gray-100 focus:ring-2 focus:ring-gray-100 rounded">
                    <svg id="toggleSidebarMobileHamburger" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <svg id="toggleSidebarMobileClose" class="w-6 h-6 hidden" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                
                <a href="{{ route('dashboard') }}" class="text-xl font-bold flex items-center lg:ml-2.5">
                    @if($currentSchool && $currentSchool->logo)
                        <img src="{{ asset('storage/' . $currentSchool->logo) }}" class="h-8 mr-2" alt="{{ $currentSchool->name }}">
                    @else
                        <span class="self-center text-primary-600"><i class="fas fa-graduation-cap mr-2"></i></span>
                    @endif
                    <span class="self-center whitespace-nowrap">{{ $currentSchool ? $currentSchool->name : config('app.name') }}</span>
                </a>
                
                @if(auth()->check() && $currentSchool)
                <form class="hidden lg:block lg:pl-8">
                    <label for="topbar-search" class="sr-only">Rechercher</label>
                    <div class="mt-1 relative lg:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input type="text" name="email" id="topbar-search" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5" placeholder="Rechercher">
                    </div>
                </form>
                @endif
            </div>
            
            <div class="flex items-center">
                <div class="hidden lg:flex items-center">
                    <!-- Theme toggle -->
                    <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none rounded-lg text-sm p-2.5">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                @if(auth()->check())
                <!-- School switch dropdown -->
                <div class="ml-3 relative">
                    <button class="flex items-center p-2 text-gray-600 hover:text-gray-900 rounded-lg" id="school-dropdown" aria-expanded="false" data-dropdown-toggle="dropdown-schools">
                        <span class="sr-only">Switch school</span>
                        <i class="fas fa-school"></i>
                    </button>
                    <div class="hidden bg-white text-base z-50 list-none divide-y divide-gray-100 rounded shadow my-4 min-w-48" id="dropdown-schools">
                        <div class="px-4 py-3">
                            <span class="block text-sm font-semibold">Écoles</span>
                        </div>
                        <ul class="py-1" aria-labelledby="school-dropdown">
                            @if(auth()->check() && isset($user) && $user->schools)
                                @foreach($user->schools as $school)
                                    <li>
                                        <form action="{{ route('schools.switch', $school) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="flex items-center py-2 px-4 text-sm hover:bg-gray-100 w-full text-left {{ $school->id === ($currentSchool->id ?? null) ? 'bg-primary-50 text-primary-600' : 'text-gray-700' }}">
                                                @if($school->logo)
                                                    <img src="{{ asset('storage/' . $school->logo) }}" class="h-5 w-5 mr-2 rounded-full" alt="{{ $school->name }}">
                                                @else
                                                    <span class="h-5 w-5 mr-2 flex items-center justify-center bg-primary-100 text-primary-600 rounded-full">
                                                        <i class="fas fa-school text-xs"></i>
                                                    </span>
                                                @endif
                                                {{ $school->name }}
                                                @if($school->id === ($currentSchool->id ?? null))
                                                <span class="ml-auto">
                                                    <i class="fas fa-check text-primary-600"></i>
                                                </span>
                                                @endif
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- User dropdown -->
                <div class="ml-3 relative">
                    <div>
                        <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button-2" aria-expanded="false" data-dropdown-toggle="dropdown-2">
                            <span class="sr-only">Open user menu</span>
                            @if(isset($user) && $user->profile_photo_path)
                                <img class="h-8 w-8 rounded-full border-2 border-primary-200" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}">
                            @else
                                <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold border-2 border-primary-200">
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                        </button>
                    </div>
                    <div class="hidden bg-white text-base z-50 list-none divide-y divide-gray-100 rounded shadow my-4 min-w-48" id="dropdown-2">
                        <div class="px-4 py-3">
                            <span class="block text-sm font-semibold">{{ $user->name ?? 'Utilisateur' }}</span>
                            <span class="block text-sm font-light text-gray-500 truncate">{{ $user->email ?? '' }}</span>
                        </div>
                        <ul class="py-1" aria-labelledby="user-menu-button-2">
                            <li>
                                <a href="{{ route('profile.index') }}" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Mon profil</a>
                            </li>
                            <li>
                                <a href="{{ route('schools.index') }}" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Mes écoles</a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Déconnexion</a>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</nav> 