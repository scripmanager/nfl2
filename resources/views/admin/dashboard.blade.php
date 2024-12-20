<x-admin-layout>

    <div class="max-w-7xl mx-auto">
        <!-- Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <x-smcard title="Total Entries" class="mt-0">
                <div class="mt-1 text-3xl text-center font-bold text-gray-900">
                    {{ \App\Models\Entry::count() }}
                </div>
            </x-card>

            <x-smcard title="Active Users" class="mt-0">
                <div class="mt-1 text-3xl text-center font-bold text-gray-900">
                    {{ \App\Models\User::count() }}
                </div>
            </x-card>

            <x-smcard title="Playoff Teams" class="mt-0">
                <div class="mt-1 text-3xl text-center font-bold text-gray-900">
                    {{ \App\Models\Team::where('is_playoff_team', true)->count() }}
                </div>
            </x-card>

            <x-smcard title="Pending Transactions" class="mt-0">
                <div class="mt-1 text-3xl text-center font-bold text-gray-900">
                    {{ \App\Models\Transaction::whereNull('processed_at')->count() }}
                </div>
            </x-card>
        </div>

        <!-- Quick Actions -->
        <x-card title="Quick Actions" class="mt-6">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.games.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-nfl-primary border border-transparent rounded-md font-semibold text-white hover:bg-nfl-primary/90">
                        Add New Game
                    </a>
                    <a href="{{ route('admin.players.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-nfl-accent border border-transparent rounded-md font-semibold text-white hover:bg-nfl-accent/90">
                        Add New Player
                    </a>
                    <button
x-data
@click="$dispatch('open-modal', 'bulk-stats-update')"
class="inline-flex items-center justify-center px-4 py-2 bg-nfl-primary border border-transparent rounded-md font-semibold text-white hover:bg-nfl-primary/90">
                        Bulk Update Stats
                    </button>
                </div>
            </div>
        </x-card>

        <!-- Current Games -->
        <x-card title="Current Games" class="mt-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Game</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Score</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach(\App\Models\Game::whereIn('status', ['scheduled', 'in_progress'])->orderBy('kickoff')->get() as $game)
                                <tr class="odd:bg-gray-50 even:bg-white text-center">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $game->homeTeam->name }} vs {{ $game->awayTeam->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $game->status === 'scheduled' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                            {{ ucfirst($game->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $game->home_score }} - {{ $game->away_score }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="inline-flex -space-x-px overflow-hidden rounded-md border bg-nfl-primary shadow-sm">
                                            <a href="{{ route('admin.games.edit', $game) }}" class="inline-block px-4 py-2 text-sm font-medium text-white hover:bg-nfl-secondary focus:relative">Update Score</a>
                                            <a href="{{ route('admin.games.stats', $game) }}" class="inline-block px-4 py-2 text-sm font-medium text-white hover:bg-nfl-secondary focus:relative">Manage Stats</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
</div>
        </x-card>   
        <!-- Recent Activity -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <x-card title="Recent Transactions" class="mt-6">
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach(\App\Models\Transaction::latest()->take(5)->get() as $transaction)
                            <div class="border-l-4 border-blue-400 pl-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $transaction->entry->user->name }} - {{ $transaction->entry->entry_name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                Dropped: {{ $transaction->droppedPlayer->name ?? 'Unknown Player' }} for {{ $transaction->addedPlayer->name ?? 'Unknown Player' }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $transaction->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-card>

            <x-card title="Stat Corrections" class="mt-6">
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach(\App\Models\StatCorrection::latest()->take(5)->get() as $correction)
                            <div class="border-l-4 border-yellow-400 pl-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $correction->player->name }} - {{ $correction->game->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $correction->description }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $correction->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-card>
        </div>
    </div>

<x-modal name="bulk-stats-update" :show="false" maxWidth="2xl">
@livewire('admin.modals.bulk-stats-update')
</x-modal>

    <!-- JavaScript to Handle Modal -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[action="{{ route("admin.games.stats.bulk-update") }}"]');

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal using Alpine.js event
                        window.dispatchEvent(new CustomEvent('close-modal', {
                            detail: 'bulk-stats-update'
                        }));

                        // Show success message
                        alert('Stats updated successfully');
                        window.location.reload();
                    } else {
                        alert('Error updating stats');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating stats');
                });
            });
        }
    });
</script>
</x-admin-layout>
