<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4">Current Roster</h2>
                    
                    @foreach($entry->players as $player)
                        <div class="mb-4 p-4 border rounded flex justify-between items-center">
                            <div>
                                <span class="font-medium">{{ $player->pivot->position }}:</span>
                                {{ $player->name }} ({{ $player->team->name }})
                            </div>
                            
                            @if(!in_array($player->id, $lockedPlayers->pluck('id')->toArray()))
                                <button 
                                    @click="$dispatch('open-modal', 'changePlayerModal'); openChangeDialog('{{ $player->pivot->position }}', {{ $player->id }})"
                                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    Change Player
                                </button>
                            @else
                                <span class="text-red-500">Locked</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Change Player Modal -->
    <x-modal name="changePlayerModal" :show="false">
    <form method="POST" action="{{ route('entries.process-transaction', $entry) }}" id="changePlayerForm" class="p-6">
        @csrf
        @method('POST')
        <input type="hidden" name="dropped_player_id" id="dropPlayerId">
        <input type="hidden" name="position" id="position">
        
        <h3 class="text-lg font-medium mb-4">Select New Player</h3>
        
        <select name="added_player_id" id="addPlayerId" class="w-full mb-4">
            <!-- Options populated by JS -->
        </select>

        <div class="flex justify-end space-x-2 mt-6">
            <x-secondary-button type="button" @click="$dispatch('close')">
                Cancel
            </x-secondary-button>
            <x-primary-button type="submit">
                Confirm Change
            </x-primary-button>
        </div>
    </form>
</x-modal>

@push('scripts')
<script>
    const players = @json($players);

    function openChangeDialog(position, playerId) {
        document.getElementById('position').value = position;
        document.getElementById('dropPlayerId').value = playerId;
        
        const select = document.getElementById('addPlayerId');
        select.innerHTML = '';
        let eligiblePlayers = [];
        
        if (position === 'FLEX') {
            eligiblePlayers = [
                ...(players['RB'] || []), 
                ...(players['WR'] || []), 
                ...(players['TE'] || [])
            ];
        } else {
            const basePosition = position.replace(/[0-9]/g, '');
            eligiblePlayers = players[basePosition] || [];
        }

        eligiblePlayers.forEach(player => {
            const option = document.createElement('option');
            option.value = player.id;
            option.textContent = `${player.name} (${player.team.name})`;
            select.appendChild(option);
        });
    }
</script>
@endpush
</x-app-layout>