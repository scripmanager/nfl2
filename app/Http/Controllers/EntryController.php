<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Player;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Services\RosterPositionService;

class EntryController extends Controller
{
    public function index()
    {
        $entries = Entry::where('user_id', auth()->id())
            ->with(['players'])
            ->get();

        return view('entries.index', [
            'entries' => $entries
        ]);
    }

    public function create()
    {
        return view('entries.create');
    }

    public function roster(Entry $entry)
    {
        if ($entry->user_id !== auth()->id()) {
            abort(403);
        }

        return view('entries.roster', [
            'entry' => $entry->load('players.team'),
            'players' => Player::with('team')->get()
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
    
        if ($user->entries()->count() >= 4) {
            return response()->json([
                'message' => 'You cannot create more than 4 entries.'
            ], 422);
        }

        $validated = $request->validate([
            'entry_name' => 'required|string|max:255'
        ]);

        return Entry::create([
            'user_id' => auth()->id(),
            'entry_name' => $validated['entry_name'],
            'changes_remaining' => 2,
            'is_active' => true,
        ]);
    }

    public function addPlayer(Request $request, Entry $entry)
    {
        if ($entry->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            $validated = $request->validate([
                'player_id' => 'required|exists:players,id',
                'roster_position' => 'required|in:QB,RB1,RB2,WR1,WR2,WR3,TE,FLEX'
            ]);
        
            $player = Player::findOrFail($validated['player_id']);
        
            $rosterService = new RosterPositionService();
            $eligiblePlayers = $rosterService->getEligiblePlayersForPosition($validated['roster_position'], $entry->players);
        
            if (!$eligiblePlayers->where('id', $player->id)->exists()) {
                return response()->json([
                    'errors' => [
                        'player_id' => ['Player is not eligible for this roster position.']
                    ]
                ], 422);
            }

            // Check team limit
            $teamPlayerCount = $entry->players()
                ->where('team_id', $player->team_id)
                ->count();

            if ($teamPlayerCount >= 2) {
                return response()->json([
                    'errors' => [
                        'player_id' => ['You cannot have more than 2 players from the same team.']
                    ]
                ], 422);
            }

            // Check position limits and roster composition
            $this->validateRosterComposition($entry, $validated['roster_position']);
       
            $entry->players()->attach($player->id, [
                'roster_position' => $validated['roster_position']
            ]);
         
            return response()->json(['message' => 'Player added successfully']);
             
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function processTransaction(Request $request, Entry $entry)
    {
        $validated = $request->validate([
            'dropped_player_id' => 'required|exists:players,id',
            'added_player_id' => 'required|exists:players,id',
            'position' => 'required|in:QB,RB,WR,TE,FLEX'
        ]);

        if ($entry->changes_remaining <= 0) {
            throw ValidationException::withMessages([
                'transaction' => ['No roster changes remaining for this entry.']
            ]);
        }

        $droppedPlayer = Player::findOrFail($validated['dropped_player_id']);
        $addedPlayer = Player::findOrFail($validated['added_player_id']);

        // Check if player's game has started
        $currentGame = Game::where(function($query) use ($droppedPlayer) {
            $query->where('home_team_id', $droppedPlayer->team_id)
                ->orWhere('away_team_id', $droppedPlayer->team_id);
        })
        ->where('kickoff', '<=', Carbon::now())
        ->first();

        if ($currentGame) {
            throw ValidationException::withMessages([
                'dropped_player_id' => ['Cannot drop a player after their game has started.']
            ]);
        }

        // Check team limit for added player
        $teamPlayerCount = $entry->players()
            ->where('team_id', $addedPlayer->team_id)
            ->where('players.id', '!=', $validated['dropped_player_id'])
            ->count();

        if ($teamPlayerCount >= 2) {
            throw ValidationException::withMessages([
                'added_player_id' => ['You cannot have more than 2 players from the same team.']
            ]);
        }

        // Validate roster composition
        $currentPosition = $entry->players()
            ->where('players.id', $validated['dropped_player_id'])
            ->first()
            ->pivot
            ->position;

        if ($currentPosition !== $validated['position']) {
            $this->validateRosterComposition($entry, $validated['position'], $currentPosition);
        }

        // Process transaction
        $entry->players()->detach($validated['dropped_player_id']);
        $entry->players()->attach($validated['added_player_id'], [
            'position' => $validated['position']
        ]);

        $entry->decrement('changes_remaining');

        return response()->json(['message' => 'Transaction processed successfully']);
    }

    private function validateRosterComposition(Entry $entry, string $newPosition, ?string $removedPosition = null)
    {
        $positions = $entry->players()
            ->where('position', '!=', $removedPosition)
            ->pluck('position')
            ->push($newPosition);

        $qbCount = $positions->filter(fn($pos) => $pos === 'QB')->count();
        $rbCount = $positions->filter(fn($pos) => $pos === 'RB')->count();
        $wrCount = $positions->filter(fn($pos) => $pos === 'WR')->count();
        $teCount = $positions->filter(fn($pos) => $pos === 'TE')->count();

        if ($qbCount > 1) {
            throw ValidationException::withMessages(['position' => ['Only 1 QB allowed']]);
        }
        if ($rbCount > 2) {
            throw ValidationException::withMessages(['position' => ['Maximum 2 RBs allowed']]);
        }
        if ($wrCount > 3) {
            throw ValidationException::withMessages(['position' => ['Maximum 3 WRs allowed']]);
        }
        if ($teCount > 1) {
            throw ValidationException::withMessages(['position' => ['Only 1 TE allowed']]);
        }
    }
}