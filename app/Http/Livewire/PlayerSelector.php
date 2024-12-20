<?php

namespace App\Http\Livewire;

use App\Models\EntryPlayer;
use App\Models\Game;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Player;
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

        //players who've already lost a game and are out
        $playersOut = Player::query()->leftJoin('teams', 'players.team_id', '=', 'teams.id')
            ->leftJoin('games',function($join) {
                $join->on(\DB::raw('( teams.id = games.home_team_id OR teams.id = games.away_team_id) and 1 '),'=',\DB::raw('1'));
             })->whereRaw('(`games`.`winning_team_id` != `teams`.`id` AND `games`.`winning_team_id` != 0 AND `games`.`id` IS NOT NULL)')
            ->groupBy('players.id')->pluck('players.id');

        //players who have already played this weekend
        $playersAlreadyPlayed = Player::query()->leftJoin('teams', 'players.team_id', '=', 'teams.id')
            ->leftJoin('games',function($join) {
                $join->on(\DB::raw('( teams.id = games.home_team_id OR teams.id = games.away_team_id) and 1 '),'=',\DB::raw('1'));
            })->where('games.kickoff', '<=', Carbon::now())
            ->where('games.kickoff', '>=', Carbon::now()->subDays(3)->toDateTimeString())
            ->groupBy('players.id')->pluck('players.id');

        // Create array of all excluded player IDs including current player
        //
        $excludedPlayerIds = $usedPlayerIds->merge($playersAlreadyPlayed)->merge($playersOut)
            ->push($this->currentPlayerId)  // Add current player to excluded list
            ->unique()
            ->values();

        // Debug logging
        \Log::info('Loading eligible players', [
            'position' => $this->rosterPosition,
            'usedPlayerIds' => $usedPlayerIds,
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

        if ($this->entry->getChangesRemaining() <= 0) {
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

                //if games started track changes else allow unlimited changes
                if(Game::where('kickoff', '<=', Carbon::now())->first()) {

                    //Update current player removed_at
                    $this->entry->players()->newPivotStatement()
                        ->where('entry_id', $this->entry->id)
                        ->where('player_id', $this->currentPlayerId)
                        ->update(['removed_at' => $now]);

                    $this->entry->players()->attach($this->selectedPlayerId, [
                        'roster_position' => $this->rosterPosition
                    ]);

                    // Get the current player data before detaching
                    //                $currentPlayerPivot = $this->entry->players()
                    //                    ->where('players.id', $this->currentPlayerId)
                    //                    ->first()
                    //                    ->pivot;
                    //
                    //                // Save to entry_player_history before detaching (for stats tracking)
                    //                DB::table('entry_player_history')->insert([
                    //                    'entry_id' => $this->entry->id,
                    //                    'player_id' => $this->currentPlayerId,
                    //                    'roster_position' => $this->rosterPosition,
                    //                    'wildcard_points' => $currentPlayerPivot->wildcard_points ?? 0.0,
                    //                    'divisional_points' => $currentPlayerPivot->divisional_points ?? 0.0,
                    //                    'conference_points' => $currentPlayerPivot->conference_points ?? 0.0,
                    //                    'superbowl_points' => $currentPlayerPivot->superbowl_points ?? 0.0,
                    //                    'total_points' => $currentPlayerPivot->total_points ?? 0.0,
                    //                    'removed_at' => $now,
                    //                    'created_at' => $now,
                    //                    'updated_at' => $now
                    //                ]);

                    //                // Detach and attach players
                    //                $this->entry->players()->detach($this->currentPlayerId);
                    //                $this->entry->players()->attach($this->selectedPlayerId, [
                    //                    'roster_position' => $this->rosterPosition
                    //                ]);
                    //
                    // Create single transaction record
                    Transaction::create([
                        'entry_id' => $this->entry->id,
                        'dropped_player_id' => $this->currentPlayerId,
                        'added_player_id' => $this->selectedPlayerId,
                        'roster_position' => $this->rosterPosition,
                        'processed_at' => $now
                    ]);
                }
                else
                {
                    //unlimited changes.
                    $entryPlayer=EntryPlayer::where('entry_id', $this->entry->id)->where('player_id', $this->currentPlayerId)->firstorfail();
                    $entryPlayer->player_id= $this->selectedPlayerId;
                    $entryPlayer->save();
                }


            });

            $this->dispatch('showDialog', [
                'type' => 'success',
                'message' => 'Player changed successfully'
            ]);
            $this->dispatch('hideDropdown');
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
