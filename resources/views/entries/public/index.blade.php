<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Entries') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2">Entry Name</th>
                                    <th class="px-4 py-2">Owner</th>
                                    <th class="px-4 py-2">Total Points</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($entries as $entry)
                                    <tr class="border-b text-center">
                                        <td class="px-4 py-2">{{ $entry->entry_name }}</td>
                                        <td class="px-4 py-2">{{ $entry->user->name }}</td>
                                        <td class="px-4 py-2">{{ $entry->total_points ?? 0 }}</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('entries.public.roster', $entry) }}" 
                                               class="text-blue-600 hover:text-blue-900">
                                                View Roster
                                            </a>
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