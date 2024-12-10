<nav class="bg-nfl-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex">
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="nav-link text-white rounded-md text-sm font-medium {{ request()->routeIs('admin.dashboard.*') ? 'active' : '' }}">NFL Admin</a>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="flex space-x-4">
                <a href="{{ route('admin.games.index') }}" class="nav-link text-white px-3 py-2 mt-4 rounded-md text-sm font-medium {{ request()->routeIs('admin.games.*') ? 'active' : '' }}">
                    Games
                </a>
                <a href="{{ route('admin.player-stats.index') }}" class="nav-link text-white px-3 py-2 mt-4 rounded-md text-sm font-medium {{ request()->routeIs('admin.player-stats.*') ? 'active' : '' }}">
                    Player Stats
                </a>
                <a href="{{ route('admin.players.index') }}" class="nav-link text-white px-3 py-2 mt-4 rounded-md text-sm font-medium {{ request()->routeIs('admin.players.*') ? 'active' : '' }}">
                    Players
                </a>
                <a href="{{ route('admin.teams.index') }}" class="nav-link text-white px-3 py-2 mt-4 rounded-md text-sm font-medium {{ request()->routeIs('admin.teams.*') ? 'active' : '' }}">
                    Teams
                </a>
                <a href="{{ route('dashboard') }}" class="nav-link text-white px-3 py-2 mt-4 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Main Site
                </a>

                <!-- Add more links as needed -->
            </div>

            <!-- User Dropdown -->
            <div class="flex items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-white hover:text-gray-300 focus:outline-none focus:text-gray-300">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.939l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.25 8.28a.75.75 0 01-.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Account Management -->
                        <div class="block px-4 py-2 text-sm text-nfl-primary">
                            {{ __('Manage Account') }}
                        </div>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Logout') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>