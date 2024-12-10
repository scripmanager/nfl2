<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Weekly Points Comparison
            </h2>
            <a href="{{ route('standings.index') }}" class="text-blue-600 hover:text-blue-800">
                Overall Standings
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Entry</th>
                                    @foreach($weeks as $week)
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                                            Week {{ $week }}
                                        </th>
                                    @endforeach
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($entries as $index => $entry)
                                    <tr class="{{ $index < 3 ? 'bg-yellow-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $entry['name'] }}</div>
                                            <div class="text-sm text-gray-500">{{ $entry['user'] }}</div>
                                        </td>
                                        @foreach($weeks as $week)
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ number_format($entry['weekly_points'][$week] ?? 0, 1) }}
                                            </td>
                                        @endforeach
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                                            {{ number_format($entry['total_points'], 1) }}
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