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

    public function edit(Game $game)
    {
        $stats = PlayerStats::where('game_id', $game->id)
            ->with('player')
            ->get();

        $players = Player::whereIn('team_id', [$game->home_team_id, $game->away_team_id])
            ->orderBy('name')
            ->get();

        return view('admin.player-stats.edit', compact('game', 'stats', 'players'));
    }

    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'stats' => 'required|array',
            'stats.*.player_id' => 'required|exists:players,id',
            'stats.*.passing_yards' => 'nullable|integer',
            'stats.*.passing_tds' => 'nullable|integer',
            'stats.*.interceptions' => 'nullable|integer',
            'stats.*.rushing_yards' => 'nullable|integer',
            'stats.*.rushing_tds' => 'nullable|integer',
            'stats.*.receptions' => 'nullable|integer',
            'stats.*.receiving_yards' => 'nullable|integer',
            'stats.*.receiving_tds' => 'nullable|integer',
            'stats.*.fumbles_lost' => 'nullable|integer',
            'stats.*.two_point_conversions' => 'nullable|integer',
            'stats.*.offensive_fumble_return_td' => 'nullable|integer'
        ]);

        foreach ($validated['stats'] as $playerStats) {
            PlayerStats::updateOrCreate(
                [
                    'game_id' => $game->id,
                    'player_id' => $playerStats['player_id']
                ],
                array_filter([
                    'passing_yards' => $playerStats['passing_yards'] ?? null,
                    'passing_tds' => $playerStats['passing_tds'] ?? null,
                    'interceptions' => $playerStats['interceptions'] ?? null,
                    'rushing_yards' => $playerStats['rushing_yards'] ?? null,
                    'rushing_tds' => $playerStats['rushing_tds'] ?? null,
                    'receptions' => $playerStats['receptions'] ?? null,
                    'receiving_yards' => $playerStats['receiving_yards'] ?? null,
                    'receiving_tds' => $playerStats['receiving_tds'] ?? null,
                    'fumbles_lost' => $playerStats['fumbles_lost'] ?? null,
                    'two_point_conversions' => $playerStats['two_point_conversions'] ?? null,
                    'offensive_fumble_return_td' => $playerStats['offensive_fumble_return_td'] ?? null
                ])
            );
        }

        return redirect()->route('admin.player-stats.show', $game)
            ->with('success', 'Player stats updated successfully');
    }

    public function destroy(PlayerStats $stats)
    {
        $stats->delete();
        
        return redirect()->route('admin.player-stats.index')
            ->with('success', 'Player stats deleted successfully');
    }
}