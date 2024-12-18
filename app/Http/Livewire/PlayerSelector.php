<?php

namespace App\Http\Livewire;

use App\Models\Game;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Player;
use App\Models\PlayerChangeHistory;
use App\Models\Transaction;
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

    // Get previously dropped players from entry_player_history
    $droppedPlayerIds = DB::table('entry_player_history')
        ->where('entry_id', $this->entry->id)
        ->pluck('player_id');

    // Create array of all excluded player IDs including current player
    $excludedPlayerIds = $usedPlayerIds->merge($droppedPlayerIds)
        ->push($this->currentPlayerId)  // Add current player to excluded list
        ->unique()
        ->values();

    // Debug logging
    \Log::info('Loading eligible players', [
        'position' => $this->rosterPosition,
        'usedPlayerIds' => $usedPlayerIds,
        'droppedPlayerIds' => $droppedPlayerIds,
        'currentPlayerId' => $this->currentPlayerId
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
        ->whereNotIn('id', $excludedPlayerIds)
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
                $now = now();
                
                // Get current player data first
                $currentPivot = DB::table('entry_player')
                    ->where('entry_id', $this->entry->id)
                    ->where('player_id', $this->currentPlayerId)
                    ->first();
            
                // Save to entry_player_history before removing
                DB::table('entry_player_history')->insert([
                    'entry_id' => $this->entry->id,
                    'player_id' => $this->currentPlayerId,
                    'roster_position' => $this->rosterPosition,
                    'wildcard_points' => $currentPivot->wildcard_points ?? 0.0,
                    'divisional_points' => $currentPivot->divisional_points ?? 0.0,
                    'conference_points' => $currentPivot->conference_points ?? 0.0,
                    'superbowl_points' => $currentPivot->superbowl_points ?? 0.0,
                    'total_points' => $currentPivot->total_points ?? 0.0,
                    'removed_at' => $now->format('Y-m-d H:i:s'),
                    'created_at' => $now->format('Y-m-d H:i:s'),
                    'updated_at' => $now->format('Y-m-d H:i:s')
                ]);
            
                // Remove the current player
                DB::table('entry_player')
                    ->where('entry_id', $this->entry->id)
                    ->where('player_id', $this->currentPlayerId)
                    ->delete();
            
                // Add the new player
                DB::table('entry_player')->insert([
                    'entry_id' => $this->entry->id,
                    'player_id' => $this->selectedPlayerId,
                    'roster_position' => $this->rosterPosition,
                    'created_at' => $now->format('Y-m-d H:i:s'),
                    'updated_at' => $now->format('Y-m-d H:i:s')
                ]);
            
                // Create transaction record
                Transaction::create([
                    'entry_id' => $this->entry->id,
                    'dropped_player_id' => $this->currentPlayerId,
                    'added_player_id' => $this->selectedPlayerId,
                    'roster_position' => $this->rosterPosition,
                    'processed_at' => $now
                ]);
            
                //if any games started decrease changes remaining
                if($gamesStarted = Game::where('kickoff', '<=', Carbon::now())->first()) {
                    $this->entry->decrement('changes_remaining');
                }
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
