<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Player;
use App\Models\Entry;
use Carbon\Carbon;

class RosterLockService
{
    public function isPlayerLocked(Player $player): bool 
    {
        $game = $this->getPlayerCurrentGame($player);
        return $game && $this->isGameLocked($game);
    }
    
    public function canModifyRoster(Entry $entry, Player $player): bool
    {
        // Check if player's game has started
        $game = $this->getPlayerCurrentGame($player);
        if ($game && $this->isGameLocked($game)) {
            return false;
        }

        // Check if entry has changes remaining
        if ($entry->changes_remaining <= 0) {
            return false;
        }

        return true;
    }

    public function isGameLocked(Game $game): bool
    {
        return $game->kickoff <= Carbon::now() || 
               in_array($game->status, ['in_progress', 'finished']);
    }

    public function getPlayerCurrentGame(Player $player): ?Game
    {
        return Game::where(function($query) use ($player) {
            $query->where('home_team_id', $player->team_id)
                  ->orWhere('away_team_id', $player->team_id);
        })
        ->where('kickoff', '>', Carbon::now()->subHours(24))
        ->orderBy('kickoff')
        ->first();
    }

    public function validateTeamLimit(Entry $entry, Player $newPlayer, ?Player $droppedPlayer = null): bool
    {
        $currentTeamCounts = $entry->players()
            ->when($droppedPlayer, function($query) use ($droppedPlayer) {
                $query->where('id', '!=', $droppedPlayer->id);
            })
            ->get()
            ->groupBy('team_id')
            ->map->count();

        $newTeamCount = ($currentTeamCounts[$newPlayer->team_id] ?? 0) + 1;

        return $newTeamCount <= 2;
    }

    public function getLockedPlayers(Entry $entry)
    {
        return $entry->players()->get()->filter(function($player) {
            $game = $this->getPlayerCurrentGame($player);
            return $game && $this->isGameLocked($game);
        });
    }
}