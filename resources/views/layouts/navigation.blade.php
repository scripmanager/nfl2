<nav class="bg-nfl-primary">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}">
                                <span class="text-white font-bold text-xl">NFL Playoff Fantasy</span>
                            </a>
                        </div>

                        <div class="flex space-x-4">
                            @auth
                                <a href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="nav-link text-white px-3 py-2 mt-4 rounded-md text-sm font-medium">
                                    {{ __('Dashboard') }}
</a >
                                <a href="{{ route('entries.index') }}" :active="request()->routeIs('entries.*')" class="nav-link text-white px-3 py-2 mt-4 rounded-md text-sm font-medium">
                                    {{ __('My Entries') }}
                                </a>
                                <a href="{{ route('standings.index') }}" :active="request()->routeIs('standings.*')" class="nav-link text-white px-3 py-2 mt-4 rounded-md text-sm font-medium">
                                    {{ __('Standings') }}
                                </a>
                                <a href="{{ route('transactions.index') }}" :active="request()->routeIs('transactions.*')" class="nav-link text-white px-3 py-2 mt-4 rounded-md text-sm font-medium">
                                    {{ __('Transactions') }}
                                </a>   
                            @endauth
                            @if (Auth::user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.*')" class="nav-link text-white px-3 py-2 mt-4 rounded-md text-sm font-medium">
                                    {{ __('Admin Dashboard') }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        @auth
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center text-sm font-medium text-white hover:text-gray-200 focus:outline-none transition duration-150 ease-in-out">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        @else
                            <a href="{{ route('login') }}" class="text-white hover:text-gray-200 px-3 py-2">Log in</a>
                            <a href="{{ route('register') }}" class="ml-4 text-white hover:text-gray-200 px-3 py-2">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>