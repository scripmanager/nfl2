<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-nfl-primary">
            {{ __('Create New Entry') }}
        </h2>
    </x-slot>
    @if($errors->any())
    <div class="mb-4 bg-nfl-background border border-nfl-primary/20 text-nfl-primary px-4 py-3 rounded relative">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        </div>
    @endif
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('entries.store') }}" id="entryForm">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Entry Name
                            </label>
                            <input type="text" name="name" class="w-full rounded-md border-gray-300" required>
                        </div>


                        <!-- QB Selection -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Quarterback (QB)
                            </label>
                            <select name="players[QB]" class="w-full rounded-md border-gray-300" required>
                                <option value="">Select QB</option>
                                @foreach($players['QB'] ?? [] as $player)
                                    <option value="{{ $player->id }}" data-team="{{ $player->team_id }}">
                                        {{ $player->name }} - {{ $player->team->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- RB Selections -->
                        @foreach(['RB1', 'RB2'] as $rb)
                            <div class="mb-6">
                                <label class="block mb-2 text-sm font-medium text-gray-700">
                                    Running Back ({{ $rb }})
                                </label>
                                <select name="players[{{ $rb }}]" class="w-full rounded-md border-gray-300" required>
                                    <option value="">Select RB</option>
                                    @foreach($players['RB'] ?? [] as $player)
                                        <option value="{{ $player->id }}" data-team="{{ $player->team_id }}">
                                            {{ $player->name }} - {{ $player->team->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach

                        <!-- WR Selections -->
                        @foreach(['WR1', 'WR2', 'WR3'] as $wr)
                            <div class="mb-6">
                                <label class="block mb-2 text-sm font-medium text-gray-700">
                                    Wide Receiver ({{ $wr }})
                                </label>
                                <select name="players[{{ $wr }}]" class="w-full rounded-md border-gray-300" required>
                                    <option value="">Select WR</option>
                                    @foreach($players['WR'] ?? [] as $player)
                                        <option value="{{ $player->id }}" data-team="{{ $player->team_id }}">
                                            {{ $player->name }} - {{ $player->team->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach

                        <!-- TE Selection -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Tight End (TE)
                            </label>
                            <select name="players[TE]" class="w-full rounded-md border-gray-300" required>
                                <option value="">Select TE</option>
                                @foreach($players['TE'] ?? [] as $player)
                                    <option value="{{ $player->id }}" data-team="{{ $player->team_id }}">
                                        {{ $player->name }} - {{ $player->team->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- FLEX Selection -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                FLEX (RB/WR/TE)
                            </label>
                            <select name="players[FLEX]" class="w-full rounded-md border-gray-300" required>
                                <option value="">Select FLEX</option>
                                @foreach(['RB', 'WR', 'TE'] as $pos)
                                    @foreach($players[$pos] ?? [] as $player)
                                        <option value="{{ $player->id }}" data-team="{{ $player->team_id }}">
                                            {{ $player->name }} ({{ $pos }}) - {{ $player->team->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                                Create Entry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('entryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get all selected players
            const selections = Array.from(this.querySelectorAll('select'))
                .map(select => ({
                    id: select.value,
                    team: select.options[select.selectedIndex].dataset.team
                }));

            // Check for duplicate players
            const playerIds = selections.map(s => s.id);
            if (new Set(playerIds).size !== playerIds.length) {
                alert('Each player can only be selected once');
                return;
            }

            // Check team limits
            const teamCounts = {};
            selections.forEach(s => {
                teamCounts[s.team] = (teamCounts[s.team] || 0) + 1;
            });

            for (const count of Object.values(teamCounts)) {
                if (count > 2) {
                    alert('You cannot select more than 2 players from the same team');
                    return;
                }
            }

            // If all validations pass, submit the form
            this.submit();
        });
    </script>
    @endpush
</x-app-layout>