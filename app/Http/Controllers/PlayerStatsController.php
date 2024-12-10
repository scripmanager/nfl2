<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Player;
use App\Models\PlayerStats;
use Illuminate\Http\Request;

class PlayerStatsController extends Controller
{
    public function index()
    {
        $stats = PlayerStats::with(['player', 'game'])->paginate(15);
        return view('admin.player-stats.index', compact('stats'));
    }

    public function create()
    {
        $players = Player::all();
        $games = Game::all();
        return view('player-stats.create', compact('players', 'games'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'game_id' => 'required|exists:games,id',
            'passing_yards' => 'required|integer|min:0',
            'passing_tds' => 'required|integer|min:0',
            'interceptions' => 'required|integer|min:0',
            'rushing_yards' => 'required|integer|min:0',
            'rushing_tds' => 'required|integer|min:0',
            'receptions' => 'required|integer|min:0',
            'receiving_yards' => 'required|integer|min:0',
            'receiving_tds' => 'required|integer|min:0',
            'two_point_conversions' => 'required|integer|min:0',
            'fumbles_lost' => 'required|integer|min:0',
            'offensive_fumble_return_td' => 'required|integer|min:0',
        ]);

        PlayerStats::create($validated);

        return redirect()->route('admin.player-stats.index')->with('success', 'Stats added successfully');
    }

    public function edit(PlayerStats $playerStats)
    {
        $players = Player::all();
        $games = Game::all();
        return view('player-stats.edit', compact('playerStats', 'players', 'games'));
    }

    public function update(Request $request, PlayerStats $playerStats)
    {
        $validated = $request->validate([
            'passing_yards' => 'required|integer|min:0',
            'passing_tds' => 'required|integer|min:0',
            'interceptions' => 'required|integer|min:0',
            'rushing_yards' => 'required|integer|min:0',
            'rushing_tds' => 'required|integer|min:0',
            'receptions' => 'required|integer|min:0',
            'receiving_yards' => 'required|integer|min:0',
            'receiving_tds' => 'required|integer|min:0',
            'two_point_conversions' => 'required|integer|min:0',
            'fumbles_lost' => 'required|integer|min:0',
            'offensive_fumble_return_td' => 'required|integer|min:0',
        ]);

        $playerStats->update($validated);

        return redirect()->route('admin.player-stats.index')->with('success', 'Stats updated successfully');
    }

    public function destroy(PlayerStats $playerStats)
    {
        $playerStats->delete();
        return redirect()->route('admin.player-stats.index')->with('success', 'Stats deleted successfully');
    }

    public function gameStats(Game $game)
    {
        $stats = PlayerStats::where('game_id', $game->id)
            ->with('player')
            ->get();
        return view('player-stats.game', compact('game', 'stats'));
    }
}