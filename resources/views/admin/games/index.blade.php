<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Playoff Games') }}
            </h2>
            <a href="{{ route('admin.games.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add New Game
            </a>
        </div>
    </x-slot>


    <div class="max-w-7xl mx-auto">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-100 items-center">
                                <th class="px-4 py-2">Game</th>
                                <th class="px-4 py-2">Round</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach($games as $game)
                            <tr class="text-center">
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    {{ $game->homeTeam->name }} vs {{ $game->awayTeam->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    {{ $game->round }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    {{ ucfirst($game->status) }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="inline-flex -space-x-px overflow-hidden rounded-md border bg-nfl-primary shadow-sm">
                                        <a href="{{ route('admin.games.edit', $game) }}" class="inline-block px-4 py-2 text-sm font-medium text-white hover:bg-red-500 focus:relative">Edit</a>
                                        <a href="{{ route('admin.games.show', $game) }}" class="inline-block px-4 py-2 text-sm font-medium text-white hover:bg-green-500 focus:relative">View</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
