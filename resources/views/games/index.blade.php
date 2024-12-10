<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Playoff Games') }}
            </h2>
            @if(auth()->user()->is_admin)
                <a href="{{ route('games.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add New Game
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach($games as $round => $roundGames)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">{{ $round }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($roundGames as $game)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 bg-white border-b border-gray-200">
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="text-sm text-gray-500">
                                            {{ $game->kickoff->format('l, M j, Y g:i A') }}
                                        </div>
                                        <div class="text-sm px-2 py-1 rounded {{ $game->status === 'finished' ? 'bg-gray-200' : ($game->status === 'in_progress' ? 'bg-green-200' : 'bg-yellow-200') }}">
                                            {{ ucfirst($game->status) }}
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center mb-2">
                                        <div class="flex items-center">
                                            <span class="font-semibold">{{ $game->homeTeam->name }}</span>
                                        </div>
                                        <span class="font-semibold">{{ $game->home_score }}</span>
                                    </div>
                                    
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            <span class="font-semibold">{{ $game->awayTeam->name }}</span>
                                        </div>
                                        <span class="font-semibold">{{ $game->away_score }}</span>
                                    </div>

                                    <div class="mt-4 flex justify-end">
                                        <a href="{{ route('games.show', $game) }}" class="text-blue-600 hover:text-blue-800">
                                            View Details
                                        </a>
                                        @if(auth()->user()->is_admin)
                                            <a href="{{ route('games.edit', $game) }}" class="ml-4 text-yellow-600 hover:text-yellow-800">
                                                Edit
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>