<x-admin-layout>

    <div class="max-w-7xl mx-auto">
        <!-- Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <div class="text-sm font-medium text-gray-500">Total Entries</div>
                <div class="mt-2 text-3xl font-bold text-gray-900">
                    {{ \App\Models\Entry::count() }}
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <div class="text-sm font-medium text-gray-500">Active Users</div>
                <div class="mt-2 text-3xl font-bold text-gray-900">
                    {{ \App\Models\User::count() }}
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <div class="text-sm font-medium text-gray-500">Playoff Teams</div>
                <div class="mt-2 text-3xl font-bold text-gray-900">
                    {{ \App\Models\Team::where('is_playoff_team', true)->count() }}
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <div class="text-sm font-medium text-gray-500">Pending Transactions</div>
                <div class="mt-2 text-3xl font-bold text-gray-900">
                    {{ \App\Models\Transaction::whereNull('processed_at')->count() }}
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-8">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.games.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700">
                        Add New Game
                    </a>
                    <a href="{{ route('admin.players.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700">
                        Add New Player
                    </a>
                    <button
x-data
@click="$dispatch('open-modal', 'bulk-stats-update')"
class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-white hover:bg-purple-700">
                        Bulk Update Stats
                    </button>
                </div>
            </div>
        </div>

        <!-- Current Games -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-8">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Current Games</h3>
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
                                <tr class="text-center">
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
                                            <a href="{{ route('admin.games.edit', $game) }}" class="inline-block px-4 py-2 text-sm font-medium text-white hover:bg-red-500 focus:relative">Update Score</a>
                                            <a href="{{ route('admin.games.stats', $game) }}" class="inline-block px-4 py-2 text-sm font-medium text-white hover:bg-red-500 focus:relative">Manage Stats</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Transactions</h3>
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
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Stat Corrections</h3>
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
            </div>
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
