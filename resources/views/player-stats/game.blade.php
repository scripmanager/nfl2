<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Game Stats: {{ $game->name }}
            </h2>
            <div class="text-sm text-gray-600">
                Status: <span class="font-semibold">{{ ucfirst($game->status) }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <div class="text-lg font-semibold mb-2">Score</div>
                        <div class="flex justify-center space-x-8 text-xl">
                            <div>{{ $game->home_team->name }}: {{ $game->home_score }}</div>
                            <div>{{ $game->away_team->name }}: {{ $game->away_score }}</div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Player</th>
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Team</th>
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Passing</th>
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Rushing</th>
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Receiving</th>
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Other</th>
                                    <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Points</th>
                                    <th class="px-4 py-3 bg-gray-50"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($stats as $stat)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $stat->player->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $stat->player->position }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            {{ $stat->player->team->name }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div>{{ $stat->passing_yards }} yds</div>
                                            <div>{{ $stat->passing_tds }} TD</div>
                                            <div>{{ $stat->interceptions }} INT</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div>{{ $stat->rushing_yards }} yds</div>
                                            <div>{{ $stat->rushing_tds }} TD</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div>{{ $stat->receptions }} rec</div>
                                            <div>{{ $stat->receiving_yards }} yds</div>
                                            <div>{{ $stat->receiving_tds }} TD</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div>2PT: {{ $stat->two_point_conversions }}</div>
                                            <div>FUM: {{ $stat->fumbles_lost }}</div>
                                            <div>FUM TD: {{ $stat->offensive_fumble_return_td }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-lg font-semibold">
                                            {{ $stat->calculatePoints() }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm font-medium">
                                            <a href="{{ route('player-stats.edit', $stat) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-3 text-center text-gray-500">
                                            No stats recorded for this game yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($game->status !== 'finished')
                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('player-stats.create', ['game' => $game->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Add Player Stats
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>