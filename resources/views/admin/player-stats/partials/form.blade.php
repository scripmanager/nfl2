@csrf

<div class="space-y-6">
    <div>
        <label for="game_id" class="block text-sm font-medium text-gray-700">Game</label>
        <select name="game_id" id="game_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Select a game</option>
            @foreach($games as $game)
                <option value="{{ $game->id }}" {{ old('game_id', $stats->game_id ?? '') == $game->id ? 'selected' : '' }}>
                    {{ $game->homeTeam->name }} vs {{ $game->awayTeam->name }} - {{ \Carbon\Carbon::parse($game->kickoff)->format('M d, Y') }}
                </option>
            @endforeach
        </select>
        @error('game_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="player_id" class="block text-sm font-medium text-gray-700">Player</label>
        <select name="player_id" id="player_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Select a player</option>
            @foreach($players as $player)
                <option value="{{ $player->id }}" {{ old('player_id', $stats->player_id ?? '') == $player->id ? 'selected' : '' }}>
                    {{ $player->name }} - {{ $player->position }} ({{ $player->team->abbreviation }})
                </option>
            @endforeach
        </select>
        @error('player_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Passing Stats -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Passing</h3>
            <div class="space-y-4">
                <div>
                    <label for="passing_yards" class="block text-sm font-medium text-gray-700">Passing Yards</label>
                    <input type="number" name="passing_yards" id="passing_yards" 
                           value="{{ old('passing_yards', $stats->passing_yards ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('passing_yards')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="passing_tds" class="block text-sm font-medium text-gray-700">Passing TDs</label>
                    <input type="number" name="passing_tds" id="passing_tds" 
                           value="{{ old('passing_tds', $stats->passing_tds ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('passing_tds')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="interceptions" class="block text-sm font-medium text-gray-700">Interceptions</label>
                    <input type="number" name="interceptions" id="interceptions" 
                           value="{{ old('interceptions', $stats->interceptions ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('interceptions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Rushing Stats -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Rushing</h3>
            <div class="space-y-4">
                <div>
                    <label for="rushing_yards" class="block text-sm font-medium text-gray-700">Rushing Yards</label>
                    <input type="number" name="rushing_yards" id="rushing_yards" 
                           value="{{ old('rushing_yards', $stats->rushing_yards ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('rushing_yards')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="rushing_tds" class="block text-sm font-medium text-gray-700">Rushing TDs</label>
                    <input type="number" name="rushing_tds" id="rushing_tds" 
                           value="{{ old('rushing_tds', $stats->rushing_tds ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('rushing_tds')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Receiving Stats -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Receiving</h3>
            <div class="space-y-4">
                <div>
                    <label for="receptions" class="block text-sm font-medium text-gray-700">Receptions</label>
                    <input type="number" name="receptions" id="receptions" 
                           value="{{ old('receptions', $stats->receptions ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('receptions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="receiving_yards" class="block text-sm font-medium text-gray-700">Receiving Yards</label>
                    <input type="number" name="receiving_yards" id="receiving_yards" 
                           value="{{ old('receiving_yards', $stats->receiving_yards ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('receiving_yards')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="receiving_tds" class="block text-sm font-medium text-gray-700">Receiving TDs</label>
                    <input type="number" name="receiving_tds" id="receiving_tds" 
                           value="{{ old('receiving_tds', $stats->receiving_tds ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('receiving_tds')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Other Stats -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Other</h3>
            <div class="space-y-4">
                <div>
                    <label for="two_point_conversions" class="block text-sm font-medium text-gray-700">2-Point Conversions</label>
                    <input type="number" name="two_point_conversions" id="two_point_conversions" 
                           value="{{ old('two_point_conversions', $stats->two_point_conversions ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('two_point_conversions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="fumbles_lost" class="block text-sm font-medium text-gray-700">Fumbles Lost</label>
                    <input type="number" name="fumbles_lost" id="fumbles_lost" 
                           value="{{ old('fumbles_lost', $stats->fumbles_lost ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('fumbles_lost')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="offensive_fumble_return_td" class="block text-sm font-medium text-gray-700">Offensive Fumble Return TD</label>
                    <input type="number" name="offensive_fumble_return_td" id="offensive_fumble_return_td" 
                           value="{{ old('offensive_fumble_return_td', $stats->offensive_fumble_return_td ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('offensive_fumble_return_td')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-x-6">
    <a href="{{ route('admin.player-stats.index') }}" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
        {{ isset($stats) ? 'Update' : 'Create' }}
    </button>
</div>