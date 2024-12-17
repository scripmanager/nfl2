<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Player;
use App\Models\PlayerStats;
use Illuminate\Http\Request;

class PlayerStatsController extends Controller
{
    public function index()
    {
        $stats = PlayerStats::with(['player', 'game'])
            ->latest()
            ->paginate(15);

        return view('admin.player-stats.index', compact('stats'));
    }

    public function create()
    {
        $games = Game::orderBy('kickoff', 'desc')->get();
        $players = Player::orderBy('name')->get();

        return view('admin.player-stats.create', compact('games', 'players'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'player_id' => 'required|exists:players,id',
            'passing_yards' => 'nullable|integer',
            'passing_tds' => 'nullable|integer',
            'interceptions' => 'nullable|integer',
            'rushing_yards' => 'nullable|integer',
            'rushing_tds' => 'nullable|integer',
            'receptions' => 'nullable|integer',
            'receiving_yards' => 'nullable|integer',
            'receiving_tds' => 'nullable|integer',
            'fumbles_lost' => 'nullable|integer',
            'two_point_conversions' => 'nullable|integer',
            'offensive_fumble_return_td' => 'nullable|integer'
        ]);

        PlayerStats::create($validated);

        return redirect()->route('admin.player-stats.index')
            ->with('success', 'Player stats created successfully');
    }

    public function show(Game $game)
    {
        $stats = PlayerStats::where('game_id', $game->id)
            ->with('player')
            ->get();

        return view('admin.player-stats.show', compact('game', 'stats'));
    }

    public function edit(PlayerStats $player_stat)
    {
        $games = Game::with(['homeTeam', 'awayTeam'])->orderBy('kickoff')->get();
        $players = Player::with('team')->orderBy('name')->get();
        
        return view('admin.player-stats.edit', compact('player_stat', 'games', 'players'));
    }
    
    public function update(Request $request, PlayerStats $player_stat)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'player_id' => 'required|exists:players,id',
            'passing_yards' => 'nullable|integer',
            'passing_tds' => 'nullable|integer',
            'interceptions' => 'nullable|integer',
            'rushing_yards' => 'nullable|integer',
            'rushing_tds' => 'nullable|integer',
            'receptions' => 'nullable|integer',
            'receiving_yards' => 'nullable|integer',
            'receiving_tds' => 'nullable|integer',
            'fumbles_lost' => 'nullable|integer',
            'two_point_conversions' => 'nullable|integer',
            'offensive_fumble_return_td' => 'nullable|integer'
        ]);
    
        $player_stat->update($validated);
    
        return redirect()->route('admin.player-stats.index')
            ->with('success', 'Player stats updated successfully');
    }
    
    public function destroy(PlayerStats $stats)
    {
        $stats->delete();
        
        return redirect()->route('admin.player-stats.index')
            ->with('success', 'Player stats deleted successfully');
    }
}