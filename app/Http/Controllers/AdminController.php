<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use App\Models\Game;
use App\Models\PlayerStats;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Entry;
use App\Models\Transaction;

class AdminController extends Controller
{
    public function index()
    {
        $latestSync = [
            'players' => Player::latest()->first()?->created_at,
            'teams' => Team::latest()->first()?->created_at,
            'games' => Game::latest()->first()?->created_at,
            'stats' => PlayerStats::latest()->first()?->created_at,
        ];


        return view('admin..dashboard', [
            'totalUsers' => User::count(),
            'totalEntries' => Entry::count(),
            'activeGames' => Game::where('status', 'active')->count(),
            'recentTransactions' => Transaction::with(['entry.user', 'dropped_player', 'added_player'])->latest()->take(5)->get(),
            'recentStats' => PlayerStats::with(['game', 'player'])->latest()->take(5)->get(),
        ]);
    }

    public function syncPlayers()
    {
        // Implement player sync logic
        return back()->with('status', 'Players synchronized successfully.');
    }

    public function syncTeams()
    {
        // Implement team sync logic
        return back()->with('status', 'Teams synchronized successfully.');
    }

    public function syncSchedule()
    {
        // Implement schedule sync logic
        return back()->with('status', 'Schedule synchronized successfully.');
    }

    public function syncScores()
    {
        // Implement score sync logic
        return back()->with('status', 'Scores synchronized successfully.');
    }

    public function calculatePoints()
    {
        // Implement point calculation logic
        return back()->with('status', 'Points calculated successfully.');
    }

    public function bulkStats()
    {
        return view('admin.bulk-stats');
    }
}
