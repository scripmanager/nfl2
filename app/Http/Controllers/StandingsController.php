<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Game;
use Illuminate\Http\Request;

class StandingsController extends Controller
{
    protected $roundToWeek = [
        'Wild Card' => 1,
        'Divisional' => 2,
        'Conference' => 3,
        'Super Bowl' => 4
    ];

    public function index()
    {
        $entries = Entry::with(['players', 'user'])
                    ->get()
                    ->sortByDesc(function($entry) {
                        return $entry->players->sum('pivot.total_points');
                    });

        return view('standings.index', [
            'entries' => $entries,
            'currentWeek' => 1  // Default to Wild Card round
        ]);
    }

    public function weekly(Request $request, $week = null)
{
    $weekToRound = array_flip($this->roundToWeek);
    $weekNumber = $week ?? 1;
    $round = $weekToRound[$weekNumber] ?? 'Wild Card';

    $entries = Entry::with([
        'players.stats' => function($query) use ($round) {
            $query->whereHas('game', function($q) use ($round) {
                $q->where('round', $round);
            });
        }, 
        'user'
    ])
    ->get()
    ->map(function($entry) {
        // Calculate weekly points for each entry
        $weeklyPoints = 0;
        foreach ($entry->players as $player) {
            foreach ($player->stats as $stat) {
                $weeklyPoints += $player->calculateWeeklyScore($stat->game_id);
            }
        }
        $entry->weekly_points = $weeklyPoints;
        return $entry;
    })
    ->sortByDesc('weekly_points')
    ->values();

    return view('standings.weekly', [
        'entries' => $entries,
        'week' => $weekNumber,
        'weeks' => collect($this->roundToWeek)->values(),
        'roundName' => $round
    ]);
}
}