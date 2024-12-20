<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Teams') }}
            </h2>
            <a href="{{ route('admin.teams.create') }}" class="bg-nfl-primary hover:bg-nfl-secondary text-white font-bold py-2 px-4 rounded">
                Add Team
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-100 items-center">
                                <th class="px-4 py-2">Name</th>
                                <th class="px-4 py-2">Abbreviation</th>
                                <th class="px-4 py-2">Playoff Team</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($teams as $team)
                    <tr class="text-center odd:bg-gray-50 even:bg-white">
                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200 text-md leading-5 text-gray-900">{{ $team->name }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-900">{{ $team->abbreviation }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-900">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $team->is_playoff_team ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $team->is_playoff_team ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap text-center border-b border-gray-200">
                            <div class="inline-flex -space-x-px overflow-hidden rounded-md border bg-nfl-primary shadow-sm">
                                <a href="{{ route('admin.teams.edit', $team) }}" class="inline-block px-4 py-2 text-sm font-medium text-white hover:bg-nfl-secondary focus:relative">Edit</a>
                                <form action="{{ route('admin.teams.destroy', $team) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-block px-4 py-2 text-sm font-medium text-white hover:bg-nfl-secondary focus:relative" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>