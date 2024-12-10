<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Player;
use App\Models\PlayerChangeHistory;
use Illuminate\Support\Facades\DB;

class PlayerSelector extends Component
{
    public $entry;
    public $currentPlayerId;
    public $rosterPosition;
    public $selectedPlayerId;
    public $players;

    public function mount()
{
    $this->loadEligiblePlayers();
}

public function loadEligiblePlayers()
{
    // Get already used players for this entry
    $usedPlayerIds = $this->entry->players()
        ->where('players.id', '!=', $this->currentPlayerId)
        ->pluck('players.id');

    // Get previously dropped players
    $droppedPlayerIds = PlayerChangeHistory::where('entry_id', $this->entry->id)
        ->where('action', 'dropped')
        ->pluck('player_id');

    // Debug logging
    \Log::info('Loading eligible players', [
        'position' => $this->rosterPosition,
        'usedPlayerIds' => $usedPlayerIds,
        'droppedPlayerIds' => $droppedPlayerIds
    ]);

    // Get the base query for eligible players
    $this->players = Player::where(function($query) {
            if ($this->rosterPosition === 'QB') {
                $query->where('position', 'QB');
            } 
            elseif (str_starts_with($this->rosterPosition, 'RB')) {
                $query->where('position', 'RB');
            }
            elseif (str_starts_with($this->rosterPosition, 'WR')) {
                $query->where('position', 'WR');
            }
            elseif ($this->rosterPosition === 'FLEX') {
                $query->whereIn('position', ['RB', 'WR', 'TE']);
            }
            elseif ($this->rosterPosition === 'TE') {
                $query->where('position', 'TE');
            }
        })
        ->whereNotIn('id', $usedPlayerIds)
        ->whereNotIn('id', $droppedPlayerIds)
        ->get();

    // Debug logging
    \Log::info('Eligible players loaded', [
        'count' => $this->players->count(),
        'players' => $this->players->pluck('name', 'id')
    ]);
}

public function changePlayer()
{
    if (!$this->selectedPlayerId) {
        $this->dispatch('showDialog', [
            'type' => 'error',
            'message' => 'Please select a player'
        ]);
        return;
    }

    if ($this->entry->changes_remaining <= 0) {
        $this->dispatch('showDialog', [
            'type' => 'error',
            'message' => 'No changes remaining'
        ]);
        return;
    }

    $selectedPlayer = Player::find($this->selectedPlayerId);

    // Get count of players from the same team as the selected player
    $sameTeamCount = $this->entry->players()
        ->where('team_id', $selectedPlayer->team_id)
        ->where('players.id', '!=', $this->currentPlayerId)
        ->count();

    \Log::info('Team count check', [
        'team_id' => $selectedPlayer->team_id,
        'count' => $sameTeamCount
    ]);

    if ($sameTeamCount >= 2) {
        $this->dispatch('showDialog', [
            'type' => 'error',
            'message' => 'Cannot have more than 2 players from the same team'
        ]);
        return;
    }

    try {
            \DB::transaction(function () {
                // Get the current player data before detaching
                $currentPlayerPivot = $this->entry->players()
                    ->where('players.id', $this->currentPlayerId)
                    ->first()
                    ->pivot;
    
                // Save to history before detaching
                DB::table('entry_player_history')->insert([
                    'entry_id' => $this->entry->id,
                    'player_id' => $this->currentPlayerId,
                    'roster_position' => $this->rosterPosition, // Use the current roster position
                    'wildcard_points' => $currentPlayerPivot->wildcard_points,
                    'divisional_points' => $currentPlayerPivot->divisional_points,
                    'conference_points' => $currentPlayerPivot->conference_points,
                    'superbowl_points' => $currentPlayerPivot->superbowl_points,
                    'total_points' => $currentPlayerPivot->total_points,
                    'removed_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
    
                // Record the change in change history
                PlayerChangeHistory::create([
                    'entry_id' => $this->entry->id,
                    'player_id' => $this->currentPlayerId,
                    'action' => 'dropped'
                ]);
    
                // Detach and attach players
                $this->entry->players()->detach($this->currentPlayerId);
                $this->entry->players()->attach($this->selectedPlayerId, [
                    'roster_position' => $this->rosterPosition
                ]);

            PlayerChangeHistory::create([
                'entry_id' => $this->entry->id,
                'player_id' => $this->selectedPlayerId,
                'action' => 'added'
            ]);

            $this->entry->decrement('changes_remaining');
        });

        $this->dispatch('showDialog', [
            'type' => 'success',
            'message' => 'Player changed successfully'
        ]);
        $this->dispatch('playerUpdated');

    } catch (\Exception $e) {
        $this->dispatch('showDialog', [
            'type' => 'error',
            'message' => 'Failed to change player: ' . $e->getMessage()
        ]);
    }
}

    public function render()
    {
        return view('livewire.player-selector');
    }
}