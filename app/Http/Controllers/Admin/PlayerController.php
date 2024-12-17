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
            'status' => 'required|string|in:active,inactive,injured',
            'is_active' => 'required|boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        Player::create($validated);
        return redirect()->route('admin.players.index')->with('success', 'Player created successfully');
    }

    public function edit(Player $player)
    {
        $teams = Team::orderBy('name')->get();
        return view('admin.players.edit', compact('player', 'teams'));
    }

    public function update(Request $request, Player $player)
    {
        \Log::debug('Player Update Request', $request->all());
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|in:QB,RB,WR,TE',
            'team_id' => 'required|exists:teams,id',
            'status' => 'required|string|in:active,inactive,injured'
        ]);
    
        $validated['is_active'] = $request->has('is_active');
        
        \Log::debug('Validated Data', $validated);
        
        try {
            $result = $player->update($validated);
            \Log::debug('Update Result', ['success' => $result]);
            
            return redirect()->route('admin.players.index')
                ->with('success', 'Player updated successfully');
        } catch (\Exception $e) {
            \Log::error('Player Update Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['update_error' => 'Failed to update player: ' . $e->getMessage()]);
        }
    }

    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->route('admin.players.index')->with('success', 'Player deleted successfully');
    }
}