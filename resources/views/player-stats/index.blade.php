<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Player Stats') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <a href="{{ route('admin.player-stats.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Stats
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Player</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Game</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Pass Yds/TD/INT</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Rush Yds/TD</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Rec/Yds/TD</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Points</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($stats as $stat)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-no-wrap">{{ $stat->player->name }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap">{{ $stat->game->name }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            {{ $stat->passing_yards }}/{{ $stat->passing_tds }}/{{ $stat->interceptions }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            {{ $stat->rushing_yards }}/{{ $stat->rushing_tds }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            {{ $stat->receptions }}/{{ $stat->receiving_yards }}/{{ $stat->receiving_tds }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">{{ $stat->calculatePoints() }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.player-stats.edit', $stat) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
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
</x-admin-layout>