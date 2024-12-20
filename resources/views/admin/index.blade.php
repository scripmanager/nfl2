@extends('admin-layout')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Admin Dashboard') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Overview Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">System Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-nfl-primary border border-nfl-primary/20 p-4 rounded-lg">
                    <div class="text-lg font-semibold text-white mb-4">Total Users</div>
                    <div class="text-3xl font-bold text-white">{{ $totalUsers ?? 0 }}</div>
                </div>
                <div class="bg-nfl-secondary border border-nfl-secondary/20 p-4 rounded-lg">
                    <div class="text-lg font-semibold text-white mb-4">Total Entries</div>
                    <div class="text-3xl font-bold text-white">{{ $totalEntries ?? 0 }}</div>
                </div>
                <div class="bg-nfl-accent border border-nfl-accent/20 p-4 rounded-lg">
                    <div class="text-lg font-semibold text-white mb-4">Active Games</div>
                    <div class="text-3xl font-bold text-white">{{ $activeGames ?? 0 }}</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <h2 class="text-lg font-bold px-2 py-1 bg-blue-600 text-white w-fit rounded mb-4">Quick Actions</h2>
        
        <div class="grid grid-cols-2 gap-4 mb-6">
            <!-- Games -->
            <a href="{{ route('admin.games.index') }}" 
               class="block p-8 bg-blue-500 hover:bg-blue-600 rounded-lg relative overflow-hidden">
                <div class="mt-6">Games</div>
                <div class="text-sm text-blue-100">Manage games and stats</div>
            </a>
            
            <!-- Players -->
            <a href="{{ route('admin.players.index') }}" 
               class="block p-8 bg-green-500 hover:bg-green-600 rounded-lg relative overflow-hidden">
                <div class="text-xl font-bold text-white mb-2">Players</div>
                <div class="text-sm text-green-100">Manage player roster</div>
            </a>
        </div>

    <!-- Remove nested grid -->
<div class="grid grid-cols-3 gap-4 mb-6">
    <!-- Teams -->
    <a href="{{ route('admin.teams.index') }}" class="block p-6 hover:bg-blue-100 border-2 border-blue-600 rounded-lg">
        <div class="text-lg font-bold text-blue-600 mb-2">Teams</div>
        <div class="text-sm text-blue-600">View NFL teams</div>
    </a>

    <!-- Player Stats -->
    <a href="{{ route('admin.player-stats.index') }}" class="block p-6 hover:bg-blue-100 border-2 border-blue-600 rounded-lg">
        <div class="text-lg font-bold text-blue-600 mb-2">Player Stats</div>
        <div class="text-sm text-blue-600">Update game stats</div>
    </a>

<!-- Modal Trigger Button -->
<button 
    x-data
    @click="$dispatch('open-modal', 'bulk-stats-upload')"
    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
    Bulk Upload Stats
</button>

<!-- Modal Component -->
<x-modal name="bulk-stats-upload" :show="false">
    <div class="p-6">
        <h2 class="text-lg font-medium mb-4">Upload Stats CSV</h2>
        <form action="{{ route('admin.bulk-stats.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <input type="file" name="stats_file" accept=".csv" 
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
            </div>
            <div class="flex justify-end mt-6">
                <x-button>Upload</x-button>
            </div>
        </form>
    </div>
</x-modal>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Recent Transactions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-bold mb-4">Recent Transactions</h3>
                <div class="space-y-4">
                    @forelse($recentTransactions ?? [] as $transaction)
                        <div class="flex justify-between items-center">
                            <div>
                                {{ $transaction->entry->user->name }} - 
                                Dropped {{ $transaction->dropped_player->name }} for 
                                {{ $transaction->added_player->name }}
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ $transaction->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600">No recent transactions</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Stats Updates -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-bold mb-4">Recent Stats Updates</h3>
                <div class="space-y-4">
                    @forelse($recentStats ?? [] as $stat)
                        <div class="flex justify-between items-center">
                            <div>
                                {{ $stat->game->name }} - 
                                {{ $stat->player->name }}
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ $stat->updated_at->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600">No recent stat updates</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
    <!-- JavaScript to Handle Modal -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[action="{{ route("admin.bulk-stats.index") }}"]');
        
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
