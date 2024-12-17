<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Player Stat') }}
            </h2>
        </div>
    </x-slot>

    <form action="{{ route('admin.player-stats.update', ['player_stat' => $player_stat]) }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')
        @include('admin.player-stats.partials.form')
    </form>
</x-admin-layout>