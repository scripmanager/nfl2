<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Game;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\ScoringService;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $entries = $user->entries()
            ->with([
                'players.team',
                'players.stats.game',
                'transactions.droppedPlayer',
                'transactions.addedPlayer'
            ])
            ->get();

         $entries->each(function ($entry) {

            $entryActivePlayers=$entry->activePlayers();
            // Get the roster with positions for each entry
            $entry->rosters = $entry->current_players->sortBy(function($player) {
                $positionOrder = [
                    'QB' => 1,
                    'WR1' => 2,
                    'WR2' => 3,
                    'WR3' => 4,
                    'RB1' => 5,
                    'RB2' => 6,
                    'TE' => 7,
                    'FLEX' => 8
                ];
                return $positionOrder[$player->pivot->roster_position] ?? 999;
            })->map(function ($player) use ($entry,$entryActivePlayers) {
                return (object)[
                    'player' => $player,
                    'is_active' => $entryActivePlayers->contains($player->id),
                    'roster_position' => $player->pivot->roster_position
                ];
            });
        });

        $entriesCount = $entries->count();
        $remainingEntries = 4 - $entriesCount; // Max 4 entries per user

        $gamesStarted=Game::where('kickoff', '<=', Carbon::now())->count();

        return view('dashboard', compact(
            'entries',
            'entriesCount',
            'remainingEntries',
            'gamesStarted'
        ));
    }
}
