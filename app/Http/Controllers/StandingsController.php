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
        // Convert week numbers to round names
        $weekToRound = array_flip($this->roundToWeek);
        
        // If no week specified, use Wild Card (week 1)
        $weekNumber = $week ?? 1;
        $round = $weekToRound[$weekNumber] ?? 'Wild Card';

        $entries = Entry::with([
            'players.playerStats' => function($query) use ($round) {
                $query->whereHas('game', function($q) use ($round) {
                    $q->where('round', $round);
                });
            }, 
            'user'
        ])
        ->get()
        ->map(function($entry) {
            $entry->weekly_points = $entry->players->sum(function($player) {
                return $player->playerStats->sum('points');
            });
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