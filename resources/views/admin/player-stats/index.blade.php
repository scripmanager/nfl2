<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Player Stats') }}
            </h2>
            <a href="{{ route('admin.player-stats.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add New Player Stat
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Player</th>
                                <th class="px-4 py-2">Game</th>
                                <th class="px-4 py-2">Pass Yds</th>
                                <th class="px-4 py-2">Pass TD</th>
                                <th class="px-4 py-2">INT</th>
                                <th class="px-4 py-2">Rush Yds</th>
                                <th class="px-4 py-2">Rush TD</th>
                                <th class="px-4 py-2">Rec</th>
                                <th class="px-4 py-2">Rec Yds</th>
                                <th class="px-4 py-2">Rec TD</th>
                                <th class="px-4 py-2">2PC</th>
                                <th class="px-4 py-2">Fum</th>
                                <th class="px-4 py-2">Fum TD</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($stats as $stat)
                                <tr class="text-center odd:bg-gray-50 even:bg-white">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">{{ $stat->player->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">{{ $stat->game->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">{{ $stat->passing_yards }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">{{ $stat->passing_tds }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">{{ $stat->interceptions }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">{{ $stat->rushing_yards }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">{{ $stat->rushing_tds }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">{{ $stat->receptions }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">{{ $stat->receiving_yards }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">{{ $stat->receiving_tds }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">{{ $stat->two_point_conversions }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">{{ $stat->fumbles_lost }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">{{ $stat->offensive_fumble_return_td }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="inline-flex -space-x-px overflow-hidden rounded-md border bg-nfl-primary shadow-sm">
                                        <a href="{{ route('admin.player-stats.edit', ['player_stat' => $stat]) }}"
                                           class="inline-block px-4 py-2 text-sm font-medium text-white hover:bg-red-500 focus:relative">Edit</a>
                                        <form action="{{ route('admin.player-stats.destroy', $stat) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-block px-4 py-2 text-sm font-medium text-white hover:bg-red-500 focus:relative"
                                                    onclick="return confirm('Are you sure?')">
                                                Delete
                                            </button>
                                        </form>
</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="px-2 py-4 text-center border-b border-gray-200">No player stats found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">
                {{ $stats->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
