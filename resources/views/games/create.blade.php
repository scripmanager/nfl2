<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Add New Game') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('games.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="home_team_id" class="block text-sm font-medium text-gray-700">Home Team</label>
                            <select name="home_team_id" id="home_team_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Home Team</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}" {{ old('home_team_id') == $team->id ? 'selected' : '' }}>
                                        {{ $team->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('home_team_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="away_team_id" class="block text-sm font-medium text-gray-700">Away Team</label>
                            <select name="away_team_id" id="away_team_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Away Team</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}" {{ old('away_team_id') == $team->id ? 'selected' : '' }}>
                                        {{ $team->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('away_team_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="kickoff" class="block text-sm font-medium text-gray-700">Kickoff Time</label>
                            <input type="datetime-local" name="kickoff" id="kickoff" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                value="{{ old('kickoff') }}">
                            @error('kickoff')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="round" class="block text-sm font-medium text-gray-700">Round</label>
                            <select name="round" id="round" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Round</option>
                                <option value="Wild Card" {{ old('round') == 'Wild Card' ? 'selected' : '' }}>Wild Card</option>
                                <option value="Divisional" {{ old('round') == 'Divisional' ? 'selected' : '' }}>Divisional</option>
                                <option value="Conference" {{ old('round') == 'Conference' ? 'selected' : '' }}>Conference</option>
                                <option value="Super Bowl" {{ old('round') == 'Super Bowl' ? 'selected' : '' }}>Super Bowl</option>
                            </select>
                            @error('round')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create Game
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>