<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Overall Standings') }}
            </h2>
            <x-primary-button class="ml-3 bg-nfl-primary text-white hover:bg-nfl-secondary px-2 py-2 rounded" onclick="window.location.href='{{ route('standings.weekly', ['week' => $currentWeek]) }}'">
                View Weekly Performance
            </x-primary-button>
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
                                <th class="px-4 py-2">Changes Left</th>
                                <th class="px-4 py-2">Wildcard</th>
                                <th class="px-4 py-2">Divisional</th>
                                <th class="px-4 py-2">Conference</th>
                                <th class="px-4 py-2">Superbowl</th>
                                <th class="px-4 py-2">Total</th>
                                <th class="px-4 py-2"></th>  <!-- New column -->
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($entries as $index => $entry)
                                    <tr class="text-center odd:bg-gray-50 even:bg-white">
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ $entry->entry_name }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ $entry->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 text-center">{{ $entry->getChangesRemaining() }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ number_format($entry->getPointsByRound('Wild Card'), 1) }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ number_format($entry->getPointsByRound('Divisional'), 1) }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ number_format($entry->getPointsByRound('Conference'), 1) }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ number_format($entry->getPointsByRound('Super Bowl'), 1) }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ number_format($entry->total_points, 1) }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            <x-secondary-button class="ml-3 bg-nfl-primary text-white hover:bg-nfl-secondary px-1 py-1 rounded" onclick="window.location.href='{{ route('entries.public.roster', $entry) }}'">
                                                View Roster
                                            </x-secondary-button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
