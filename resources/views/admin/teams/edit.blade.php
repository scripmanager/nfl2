<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Team') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <form method="POST" action="{{ route('admin.teams.update', $team) }}">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Team Name
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="name" 
                           type="text" 
                           name="name" 
                           value="{{ old('name', $team->name) }}" 
                           required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="abbreviation">
                        Abbreviation
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                           id="abbreviation" 
                           type="text" 
                           name="abbreviation" 
                           value="{{ old('abbreviation', $team->abbreviation) }}" 
                           required>
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_playoff_team" 
                               value="1" 
                               {{ old('is_playoff_team', $team->is_playoff_team) ? 'checked' : '' }}
                               class="form-checkbox">
                        <span class="ml-2">Playoff Team</span>
                    </label>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Update Team
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>