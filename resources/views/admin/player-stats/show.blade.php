<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('View Player Stat') }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Player Stat Details</h2>
        <p><strong>Player:</strong> {{ $playerstat->player->name }}</p>
        <p><strong>Game:</strong> {{ $playerstat->game->name }}</p>
        <p><strong>Passing Yards:</strong> {{ $playerstat->passing_yards }}</p>
        <p><strong>Passing TDs:</strong> {{ $playerstat->passing_tds }}</p>
        <p><strong>Interceptions:</strong> {{ $playerstat->interceptions }}</p>
        <p><strong>Rushing Yards:</strong> {{ $playerstat->rushing_yards }}</p>
        <p><strong>Rushing TDs:</strong> {{ $playerstat->rushing_tds }}</p>
        <p><strong>Receptions:</strong> {{ $playerstat->receptions }}</p>
        <p><strong>Receiving Yards:</strong> {{ $playerstat->receiving_yards }}</p>
        <p><strong>Receiving TDs:</strong> {{ $playerstat->receiving_tds }}</p>
        <p><strong>Two-Point Conversions:</strong> {{ $playerstat->two_point_conversions }}</p>
        <p><strong>Fumbles Lost:</strong> {{ $playerstat->fumbles_lost }}</p>
        <p><strong>Offensive Fumble Return TD:</strong> {{ $playerstat->offensive_fumble_return_td }}</p>

        <div class="mt-4">
            <a href="{{ route('admin.playerstats.index') }}" class="btn-secondary px-4 py-2 rounded">Back to List</a>
        </div>
    </div>
</x-admin-layout>