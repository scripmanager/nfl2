<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Player Stats') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.player-stats.update', $playerStats) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Player</label>
                                <div class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-gray-100 rounded-md">
                                    {{ $playerStats->player->name }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Game</label>
                                <div class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-gray-100 rounded-md">
                                    {{ $playerStats->game->name }}
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div>
                                <label for="passing_yards" class="block text-sm font-medium text-gray-700">Passing Yards</label>
                                <input type="number" name="passing_yards" id="passing_yards" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ $playerStats->passing_yards }}">
                            </div>

                            <div>
                                <label for="passing_tds" class="block text-sm font-medium text-gray-700">Passing TDs</label>
                                <input type="number" name="passing_tds" id="passing_tds" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ $playerStats->passing_tds }}">
                            </div>

                            <div>
                                <label for="interceptions" class="block text-sm font-medium text-gray-700">Interceptions</label>
                                <input type="number" name="interceptions" id="interceptions" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ $playerStats->interceptions }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label for="rushing_yards" class="block text-sm font-medium text-gray-700">Rushing Yards</label>
                                <input type="number" name="rushing_yards" id="rushing_yards" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ $playerStats->rushing_yards }}">
                            </div>

                            <div>
                                <label for="rushing_tds" class="block text-sm font-medium text-gray-700">Rushing TDs</label>
                                <input type="number" name="rushing_tds" id="rushing_tds" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ $playerStats->rushing_tds }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div>
                                <label for="receptions" class="block text-sm font-medium text-gray-700">Receptions</label>
                                <input type="number" name="receptions" id="receptions" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ $playerStats->receptions }}">
                            </div>

                            <div>
                                <label for="receiving_yards" class="block text-sm font-medium text-gray-700">Receiving Yards</label>
                                <input type="number" name="receiving_yards" id="receiving_yards" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ $playerStats->receiving_yards }}">
                            </div>

                            <div>
                                <label for="receiving_tds" class="block text-sm font-medium text-gray-700">Receiving TDs</label>
                                <input type="number" name="receiving_tds" id="receiving_tds" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ $playerStats->receiving_tds }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div>
                                <label for="two_point_conversions" class="block text-sm font-medium text-gray-700">2PT Conversions</label>
                                <input type="number" name="two_point_conversions" id="two_point_conversions" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ $playerStats->two_point_conversions }}">
                            </div>

                            <div>
                                <label for="fumbles_lost" class="block text-sm font-medium text-gray-700">Fumbles Lost</label>
                                <input type="number" name="fumbles_lost" id="fumbles_lost" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ $playerStats->fumbles_lost }}">
                            </div>

                            <div>
                                <label for="offensive_fumble_return_td" class="block text-sm font-medium text-gray-700">Offensive Fumble Return TD</label>
                                <input type="number" name="offensive_fumble_return_td" id="offensive_fumble_return_td" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ $playerStats->offensive_fumble_return_td }}">
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admin.player-stats.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Stats
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>