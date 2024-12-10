<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Overall Standings') }}
            </h2>
            <a href="{{ route('standings.weekly', ['week' => $currentWeek]) }}" class="text-blue-600 hover:text-blue-800">
                View Weekly Performance
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Entry Name</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Wildcard Points</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Divisional Points</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Conference Points</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Superbowl Points</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Total Points</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($entries as $index => $entry)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ $entry->entry_name }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ $entry->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ number_format($entry->players->sum('pivot.wildcard_points'), 1) }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ number_format($entry->players->sum('pivot.divisional_points'), 1) }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ number_format($entry->players->sum('pivot.conference_points'), 1) }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ number_format($entry->players->sum('pivot.superbowl_points'), 1) }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ number_format($entry->players->sum('pivot.total_points'), 1) }}</td>    
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