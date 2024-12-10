<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="player_id" class="block mb-2">Player</label>
        <select name="player_id" id="player_id" class="w-full border rounded p-2">
            <option value="">Select Player</option>
            @foreach($players as $player)
                <option value="{{ $player->id }}" {{ old('player_id', $playerstat->player_id ?? '') == $player->id ? 'selected' : '' }}>
                    {{ $player->name }}
                </option>
            @endforeach
        </select>
        @error('player_id')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="game_id" class="block mb-2">Game</label>
        <select name="game_id" id="game_id" class="w-full border rounded p-2">
            <option value="">Select Game</option>
            @foreach($games as $game)
                <option value="{{ $game->id }}" {{ old('game_id', $playerstat->game_id ?? '') == $game->id ? 'selected' : '' }}>
                    {{ $game->name }}
                </option>
            @endforeach
        </select>
        @error('game_id')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Passing Stats -->
    <div>
        <label for="passing_yards" class="block mb-2">Passing Yards</label>
        <input type="number" name="passing_yards" id="passing_yards" value="{{ old('passing_yards', $playerstat->passing_yards ?? '') }}" class="w-full border rounded p-2">
        @error('passing_yards')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="passing_tds" class="block mb-2">Passing TDs</label>
        <input type="number" name="passing_tds" id="passing_tds" value="{{ old('passing_tds', $playerstat->passing_tds ?? '') }}" class="w-full border rounded p-2">
        @error('passing_tds')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="interceptions" class="block mb-2">Interceptions</label>
        <input type="number" name="interceptions" id="interceptions" value="{{ old('interceptions', $playerstat->interceptions ?? '') }}" class="w-full border rounded p-2">
        @error('interceptions')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Rushing Stats -->
    <div>
        <label for="rushing_yards" class="block mb-2">Rushing Yards</label>
        <input type="number" name="rushing_yards" id="rushing_yards" value="{{ old('rushing_yards', $playerstat->rushing_yards ?? '') }}" class="w-full border rounded p-2">
        @error('rushing_yards')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="rushing_tds" class="block mb-2">Rushing TDs</label>
        <input type="number" name="rushing_tds" id="rushing_tds" value="{{ old('rushing_tds', $playerstat->rushing_tds ?? '') }}" class="w-full border rounded p-2">
        @error('rushing_tds')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Receiving Stats -->
    <div>
        <label for="receptions" class="block mb-2">Receptions</label>
        <input type="number" name="receptions" id="receptions" value="{{ old('receptions', $playerstat->receptions ?? '') }}" class="w-full border rounded p-2">
        @error('receptions')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="receiving_yards" class="block mb-2">Receiving Yards</label>
        <input type="number" name="receiving_yards" id="receiving_yards" value="{{ old('receiving_yards', $playerstat->receiving_yards ?? '') }}" class="w-full border rounded p-2">
        @error('receiving_yards')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="receiving_tds" class="block mb-2">Receiving TDs</label>
        <input type="number" name="receiving_tds" id="receiving_tds" value="{{ old('receiving_tds', $playerstat->receiving_tds ?? '') }}" class="w-full border rounded p-2">
        @error('receiving_tds')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Miscellaneous Stats -->
    <div>
        <label for="two_point_conversions" class="block mb-2">Two-Point Conversions</label>
        <input type="number" name="two_point_conversions" id="two_point_conversions" value="{{ old('two_point_conversions', $playerstat->two_point_conversions ?? '') }}" class="w-full border rounded p-2">
        @error('two_point_conversions')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="fumbles_lost" class="block mb-2">Fumbles Lost</label>
        <input type="number" name="fumbles_lost" id="fumbles_lost" value="{{ old('fumbles_lost', $playerstat->fumbles_lost ?? '') }}" class="w-full border rounded p-2">
        @error('fumbles_lost')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="offensive_fumble_return_td" class="block mb-2">Offensive Fumble Return TD</label>
        <input type="number" name="offensive_fumble_return_td" id="offensive_fumble_return_td" value="{{ old('offensive_fumble_return_td', $playerstat->offensive_fumble_return_td ?? '') }}" class="w-full border rounded p-2">
        @error('offensive_fumble_return_td')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="mt-6 flex justify-end">
    <a href="{{ route('admin.playerstats.index') }}" class="btn-secondary px-4 py-2 rounded mr-2">Cancel</a>
    <button type="submit" class="btn-primary px-4 py-2 rounded">{{ $submit }}</button>
</div>