<!-- resources/views/games/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ $game->round }} - {{ $game->homeTeam->name }} vs {{ $game->awayTeam->name }}
            </h2>
            @if(auth()->user()->is_admin)
                <div class="flex space-x-4">
                    <button 
                        onclick="openUpdateScoreModal()"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Update Score
                    </button>
                    <button 
                        onclick="openUpdateStatusModal()"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Update Status
                    </button>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Game Information Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-3 gap-4">
                        <!-- Home Team -->
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ $game->homeTeam->name }}</div>
                            <div class="text-4xl font-bold my-4">{{ $game->home_score }}</div>
                        </div>

                        <!-- Game Info -->
                        <div class="text-center">
                            <div class="text-lg font-semibold">{{ $game->round }}</div>
                            <div class="my-2">{{ $game->kickoff->format('M j, Y g:i A') }}</div>
                            <div class="inline-block px-4 py-2 rounded-full 
                                {{ $game->status === 'scheduled' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $game->status === 'in_progress' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $game->status === 'finished' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst($game->status) }}
                            </div>
                        </div>

                        <!-- Away Team -->
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ $game->awayTeam->name }}</div>
                            <div class="text-4xl font-bold my-4">{{ $game->away_score }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Player Stats Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4">Player Statistics</h3>
                    
                    @foreach([$game->homeTeam, $game->awayTeam] as $team)
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold mb-2">{{ $team->name }}</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Player</th>
                                            <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Pass</th>
                                            <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Rush</th>
                                            <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Receive</th>
                                            <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Other</th>
                                            <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Points</th>
                                            @if(auth()->user()->is_admin && !$game->isFinished())
                                                <th class="px-4 py-2 bg-gray-50"></th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($playerStats[$team->id] ?? [] as $stat)
                                            <tr>
                                                <td class="px-4 py-2">
                                                    <div class="font-medium">{{ $stat->player->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $stat->player->position }}</div>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <div>{{ $stat->passing_yards }} yds</div>
                                                    <div>{{ $stat->passing_tds }} TD</div>
                                                    <div>{{ $stat->interceptions }} INT</div>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <div>{{ $stat->rushing_yards }} yds</div>
                                                    <div>{{ $stat->rushing_tds }} TD</div>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <div>{{ $stat->receptions }} rec</div>
                                                    <div>{{ $stat->receiving_yards }} yds</div>
                                                    <div>{{ $stat->receiving_tds }} TD</div>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <div>2PT: {{ $stat->two_point_conversions }}</div>
                                                    <div>FUM: {{ $stat->fumbles_lost }}</div>
                                                </td>
                                                <td class="px-4 py-2 font-bold">
                                                    {{ $stat->calculatePoints() }}
                                                </td>
                                                @if(auth()->user()->is_admin && !$game->isFinished())
                                                    <td class="px-4 py-2">
                                                        <button 
                                                            onclick="openUpdateStatsModal({{ $stat->player->id }})"
                                                            class="text-blue-600 hover:text-blue-900">
                                                            Update
                                                        </button>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->is_admin)
        <!-- Update Score Modal -->
        <x-modal name="update-score" :show="false">
            <form method="POST" action="{{ route('games.update-score', $game) }}" class="p-6">
                @csrf
                @method('PATCH')
                
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    Update Game Score
                </h2>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ $game->homeTeam->name }}</label>
                        <input type="number" name="home_score" value="{{ $game->home_score }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ $game->awayTeam->name }}</label>
                        <input type="number" name="away_score" value="{{ $game->away_score }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        Cancel
                    </x-secondary-button>

                    <x-primary-button class="ml-3">
                        Update Score
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <!-- Update Status Modal -->
        <x-modal name="update-status" :show="false">
            <form method="POST" action="{{ route('games.update-status', $game) }}" class="p-6">
                @csrf
                @method('PATCH')
                
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    Update Game Status
                </h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="scheduled" {{ $game->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="in_progress" {{ $game->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="finished" {{ $game->status === 'finished' ? 'selected' : '' }}>Finished</option>
                    </select>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        Cancel
                    </x-secondary-button>

                    <x-primary-button class="ml-3">
                        Update Status
                    </x-primary-button>
                </div>
            </form>
        </x-modal>
    @endif

    @push('scripts')
    <script>
        function openUpdateScoreModal() {
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'update-score' }));
        }

        function openUpdateStatusModal() {
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'update-status' }));
        }

        function openUpdateStatsModal(playerId) {
            // Implementation for updating individual player stats
        }
    </script>
    @endpush
</x-app-layout>