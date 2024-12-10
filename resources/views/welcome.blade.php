<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NFL Fantasy Playoffs</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold text-gray-800">NFL Fantasy Playoffs</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Register</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="text-center">
                    <h2 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">
                        NFL Playoff Fantasy League
                    </h2>
                    <p class="mt-4 text-xl text-gray-600">
                        Experience the thrill of fantasy football during the NFL playoffs
                    </p>
                    @guest
                        <div class="mt-8">
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg text-lg">
                                Join Now
                            </a>
                        </div>
                    @endguest
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="bg-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-gray-900">How It Works</h3>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center p-6">
                        <div class="text-xl font-semibold mb-4">Create Your Team</div>
                        <p class="text-gray-600">Build a unique roster with 8 players from playoff teams</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="text-xl font-semibold mb-4">Manage Your Roster</div>
                        <p class="text-gray-600">Make strategic changes throughout the playoffs</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="text-xl font-semibold mb-4">Win Big</div>
                        <p class="text-gray-600">Compete against others for the championship</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rules Summary -->
        <div class="bg-gray-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-gray-900">Key Rules</h3>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h4 class="font-semibold mb-2">Team Composition</h4>
                        <p class="text-gray-600">8 players: 1 QB, 2 RB, 3 WR, 1 TE, 1 FLEX</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h4 class="font-semibold mb-2">Entry Limit</h4>
                        <p class="text-gray-600">Up to 4 entries per player at $25 each</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h4 class="font-semibold mb-2">Team Restrictions</h4>
                        <p class="text-gray-600">Maximum 2 players from any NFL team</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h4 class="font-semibold mb-2">Roster Changes</h4>
                        <p class="text-gray-600">2 add/drops allowed for the entire postseason</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="text-center text-gray-600">
                    <p>&copy; {{ date('Y') }} NFL Fantasy Playoffs. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>