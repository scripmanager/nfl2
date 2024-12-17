@extends('admin-layout')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Players Management</h2>
                    <a href="{{ route('admin.players.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add New Player</a>
                </div>

                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Name</th>
                                <th class="px-4 py-2">Position</th>
                                <th class="px-4 py-2">Team</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($players as $player)
                            <tr class="text-center">
                                <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">{{ $player->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">{{ $player->position }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">{{ $player->team->name ?? 'No Team' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-gray-900">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($player->status === 'active') bg-green-100 text-green-800 
                                    @elseif($player->status === 'injured') bg-red-100 text-red-800 
                                    @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($player->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="inline-flex -space-x-px overflow-hidden rounded-md border bg-nfl-primary shadow-sm">
                                    <a href="{{ route('admin.players.edit', $player) }}" class="inline-block px-4 py-2 text-sm font-medium text-white hover:bg-red-500 focus:relative">Edit</a>
                                    <form action="{{ route('admin.players.destroy', $player) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-block px-4 py-2 text-sm font-medium text-white hover:bg-red-500 focus:relative" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $players->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection