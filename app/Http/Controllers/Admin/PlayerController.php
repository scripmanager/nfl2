<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;
use App\Models\Team; 

class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::with('team')->orderBy('name')->paginate(20);
        return view('admin.players.index', compact('players'));
    }


    public function create()
    {
        $teams = Team::orderBy('name')->get();
        return view('admin.players.create', compact('teams'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|in:QB,RB,WR,TE',
            'team_id' => 'required|exists:teams,id',
            'status' => 'required|string|in:active,inactive,injured'
        ]);

        Player::create($validated);
        return redirect()->route('admin.players.index')->with('success', 'Player created successfully');
    }

    public function edit(Player $player)
    {
        return view('admin.players.edit', compact('player'));
    }

    public function update(Request $request, Player $player)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|in:QB,RB,WR,TE',
            'team_id' => 'required|exists:teams,id',
            'status' => 'required|string|in:active,inactive,injured'
        ]);

        $player->update($validated);
        return redirect()->route('admin.players.index')->with('success', 'Player updated successfully');
    }

    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->route('admin.players.index')->with('success', 'Player deleted successfully');
    }
}