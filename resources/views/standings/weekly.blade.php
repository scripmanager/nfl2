<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Weekly Performance') }} - {{ $roundName }}
            </h2>
            <div class="flex items-center space-x-4">
                <select class="form-select rounded-md shadow-sm border-gray-300" onchange="window.location.href=this.value">
                    @foreach($weeks as $weekNum)
                        <option value="{{ route('standings.weekly', ['week' => $weekNum]) }}" 
                                {{ $week == $weekNum ? 'selected' : '' }}>
                            {{ [1 => 'Wild Card', 2 => 'Divisional', 3 => 'Conference', 4 => 'Super Bowl'][$weekNum] }}
                        </option>
                    @endforeach
                </select>
                <x-primary-button class="ml-3 bg-nfl-primary text-white hover:bg-nfl-secondary px-2 py-2 rounded" onclick="window.location.href='{{ route('standings.index') }}'">
                    View Overall Standings
                </x-primary-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Rank</th>
                                <th class="px-4 py-2">Entry Name</th>
                                <th class="px-4 py-2">Owner</th>
                                <th class="px-4 py-2">{{ $roundName }} Points</th>
                                <th class="px-4 py-2">Actions</th>  <!-- New column -->                                  
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-center">
                            @foreach($entries as $index => $entry)
                                <tr class="text-center odd:bg-gray-100 even:bg-white">
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        {{ $entry->entry_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        {{ $entry->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        {{ number_format($entry->weekly_points, 1) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        <x-primary-button class="ml-3 bg-nfl-primary text-white hover:bg-nfl-secondary px-2 py-2 rounded" onclick="window.location.href='{{ route('entries.public.roster', $entry) }}'">
                                            View Roster
                                        </x-primary-button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>