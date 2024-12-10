<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $entry->name }} - Details
            </h2>
            <span class="text-gray-600">Owner: {{ $entry->user->name }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Current Roster</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Player</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Team</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Total Points</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($entry->players as $player)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $player->pivot->position }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $player->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $player->team->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ number_format($player->stats->sum(function($stat) { return $stat->calculatePoints(); }), 1) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h3 class="text-lg font-semibold mt-8 mb-4">Weekly Performance</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Week</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Points</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Details</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($totalByWeek as $week => $points)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">Week {{ $week }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($points, 1) }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            @foreach($entry->players as $player)
                                                @if(isset($weeklyPoints[$player->id][$week]))
                                                    <div>
                                                        {{ $player->name }}: {{ number_format($weeklyPoints[$player->id][$week], 1) }}
                                                    </div>
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>