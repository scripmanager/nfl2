@inject('scoringService', 'App\Services\ScoringService')
<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-nfl-primary leading-tight">
            {{ __('Game Statistics') }}
        </h2>
    </x-slot>


    <div style="width: fit-content; min-width: 100%;" class="px-4 mx-auto">
        <!-- Game Information -->
        <div class="bg-white shadow-sm rounded-lg mb-6">
            <div class="p-6 bg-nfl-accent border-b border-gray-200 rounded-lg">
                <h3 class="text-lg font-semibold mb-2 text-white">{{ $game->homeTeam->name }} vs {{ $game->awayTeam->name }}</h3>
                <p class="text-white"><strong>Kickoff:</strong> {{ $game->kickoff->format('l, M j, Y g:i A') }}</p>
                <p class="text-white"><strong>Status:</strong> {{ ucfirst($game->status) }}</p>
                <p class="text-white"><strong>Round:</strong> {{ $game->round }}</p>
                <p class="text-white"><strong>Score:</strong> {{ $game->home_score }} - {{ $game->away_score }}</p>
            </div>
        </div>

        <!-- Player Statistics -->
        <div style="width: fit-content; min-width: 100%;" class="bg-white shadow-sm rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200 rounded-lg">
                <h4 class="text-md font-semibold mb-4 text-nfl-primary">Player Statistics</h4>
                <table class="divide-y divide-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 rounded-lg">
                            <th class="px-4 py-2">Player</th>
                            <th class="px-4 py-2">Team</th>
                            <th class="px-4 py-2">Passing Yards</th>
                            <th class="px-4 py-2">Passing TDs</th>
                            <th class="px-4 py-2">Interceptions</th>
                            <th class="px-4 py-2">Rushing Yards</th>
                            <th class="px-4 py-2">Rushing TDs</th>
                            <th class="px-4 py-2">Receptions</th>
                            <th class="px-4 py-2">Receiving Yards</th>
                            <th class="px-4 py-2">Receiving TDs</th>
                            <th class="px-4 py-2">2PT Conversions</th>
                            <th class="px-4 py-2">Fumbles Lost</th>
                            <th class="px-4 py-2">Fumble TDs</th>
                            <th class="px-4 py-2">Points</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($game->playerStats as $stat)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stat->player->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stat->player->team->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">{{ $stat->passing_yards }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">{{ $stat->passing_tds }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">{{ $stat->interceptions }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">{{ $stat->rushing_yards }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">{{ $stat->rushing_tds }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">{{ $stat->receptions }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">{{ $stat->receiving_yards }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">{{ $stat->receiving_tds }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">{{ $stat->two_point_conversions }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">{{ $stat->fumbles_lost }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">{{ $stat->offensive_fumble_return_td }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 font-semibold">{{ $scoringService->calculateGamePoints($game, $stat->player)['points'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No player statistics available for this game.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>

        <!-- Optional: Add Export Button or Additional Features -->
    </div>
</x-admin-layout>
