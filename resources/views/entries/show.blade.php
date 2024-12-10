<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-gray-800">
            {{ __('Roster View') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Points Card -->
                <div class="px-4 py-6 bg-white border rounded-lg shadow flex items-center justify-between">
                    <h3 class="text-lg font-medium">Total Points</h3>
                    <p class="mt-2 text-2xl font-bold">{{ $totalPoints }}</p>
                </div>

                <!-- Changes Remaining Card -->
                <div class="px-4 py-6 bg-white border rounded-lg shadow flex items-center justify-between">
                    <h3 class="text-lg font-medium">Changes Remaining</h3>
                    <p class="mt-2 text-2xl font-bold">{{ $changesRemaining }} / 2</p>
                </div>

                <!-- Players Active Card -->
                <div class="px-4 py-6 bg-white border rounded-lg shadow flex items-center justify-between">
                    <h3 class="text-lg font-medium">Players Active</h3>
                    <p class="mt-2 text-2xl font-bold">{{ $playersActive }} / 8</p>
                </div>

                <!-- Points by Position Card -->
                <div class="px-4 py-6 bg-white border rounded-lg shadow flex items-center justify-between">
                    <h3 class="text-lg font-medium">Points by Position</h3>
                    <ul class="mt-2">
                        @foreach($pointsByPosition as $position => $points)
                            <li class="flex justify-between">
                                <span class="capitalize">{{ $position }}</span>
                                <span>{{ $points }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Roster Table -->
    <div class="overflow-x-auto bg-white border rounded-lg shadow">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Player Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Wildcard Points</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Divisional Points</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Conference Points</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Super Bowl Points</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Points</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($entry->players as $player)
                    <tr x-data="{ showDropdown: false, loading: false }">
                        <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $player->pivot->roster_position }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $player->name }} ({{ $player->team->name }})
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($player->pivot->wildcard_points, 1) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($player->pivot->divisional_points, 1) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($player->pivot->conference_points, 1) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($player->pivot->superbowl_points, 1) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($player->pivot->total_points, 1) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center relative">
                            @if(!in_array($player->id, $lockedPlayers->pluck('id')->toArray()))
                                <button 
                                    @click="showDropdown = !showDropdown; if(showDropdown) $wire.loadEligiblePlayers('{{ $player->pivot->roster_position }}')"
                                    class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">
                                    Change Player
                                </button>
                                
                                <!-- Dropdown -->
                                <div 
                                    x-show="showDropdown" 
                                    @click.away="showDropdown = false"
                                    class="absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg z-50">
                                    <livewire:player-selector 
                                        :entry="$entry" 
                                        :currentPlayerId="$player->id"
                                        :rosterPosition="$player->pivot->roster_position" />
                                </div>
                            @else
                                <span class="text-red-500">Locked</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>