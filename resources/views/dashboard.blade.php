<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Overview Section -->
            <x-card title="Your Overview">
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="bg-nfl-primary border border-nfl-primary/20 p-4 rounded-lg">
                        <div class="text-lg font-semibold text-white mb-4">Total Entries</div>
                        <div class="text-3xl font-bold text-white">{{ $entriesCount }} / 4</div>
                        @if($remainingEntries > 0)
                            <x-button href="{{ route('entries.create') }}" variant="ghost" class="mt-2">
                                Add Entry (+{{ $remainingEntries }} remaining)
                            </x-button>
                        @endif
                    </div>
                    <div class="bg-nfl-secondary border border-nfl-secondary/20 p-4 rounded-lg">
                        <div class="text-lg font-semibold text-white mb-4">Best Performing Entry</div>
                        <div class="text-3xl font-bold text-white">
                            @if($entries->isNotEmpty())
                                {{ $entries->max('total_points') ?? 0 }} pts
                            @else
                                0 pts
                            @endif
                        </div>
                    </div>
                    <div class="bg-nfl-accent border border-nfl-accent/20 p-4 rounded-lg">
                        <div class="text-lg font-semibold text-white mb-4">Changes Remaining</div>
                        <div class="text-3xl font-bold text-white">
                            {{ $entries->min('changes_remaining') ?? 2 }} / 2
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Rest of the content... -->
            <!-- Entries Section -->
            <x-card title="Your Entries" class="mt-6">
                @if($entries->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-600 mb-4">You haven't created any entries yet.</p>
                        <x-button href="{{ route('entries.create') }}" variant="primary">
                            Create Your First Entry
                        </x-button>
                    </div>
                @else
                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach($entries as $entry)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-semibold">{{ $entry->entry_name }}</h3>
                                    <span class="text-lg font-bold">{{ $entry->total_points ?? 0 }} pts</span>
                                </div>
                                
                                <div class="space-y-2">
                                    @foreach($entry->rosters as $roster)
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center">
                                                <span class="w-12 text-gray-600">{{ $roster->roster_position }}</span>
                                                <span>{{ $roster->player->name }}</span>
                                            </div>
                                            <span class="text-sm text-gray-600">{{ $roster->player->team->abbreviation }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="mt-4 flex justify-between items-center">
                                    <span class="text-sm text-gray-600">
                                        Changes remaining: {{ $entry->changes_remaining }}
                                    </span>
                                    <x-button href="{{ route('entries.roster', $entry) }}" variant="ghost">
                                        Manage Roster
                                    </x-button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-card>

            <!-- Recent Activity -->
            <x-card title="Recent Activity" class="mt-6">
                <div class="space-y-4">
                    @forelse($entries->flatMap->transactions->take(5) as $transaction)
                        <div class="flex justify-between items-center">
                            <div>
                                Dropped {{ $transaction->droppedPlayer->name }} for 
                                {{ $transaction->addedPlayer->name }}
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ $transaction->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600">No recent activity</p>
                    @endforelse
                </div>
            </x-card>

            <!-- Weekly Performance -->
            <x-card title="Weekly Performance" class="mt-6">
                <div class="grid gap-4 md:grid-cols-2">
                    @foreach($entries as $entry)
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="font-semibold">{{ $entry->name }}</h4>
                                <span class="text-lg font-bold">{{ number_format($entry->total_points, 1) }} pts</span>
                            </div>
                            <div class="space-y-2">
                                @foreach($entry->weekly_points as $week => $points)
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Week {{ $week }}</span>
                                        <span class="font-medium">{{ number_format($points, 1) }} pts</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>