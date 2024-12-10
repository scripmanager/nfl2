<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $teams = Team::orderBy('name')->get();
        return view('admin.teams.index', compact('teams'));
    }

    public function create()
    {
        return view('admin.teams.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'abbreviation' => 'required|max:10|unique:teams',
            'is_playoff_team' => 'boolean'
        ]);

        Team::create($validated);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Team created successfully');
    }

    public function edit(Team $team)
    {
        return view('admin.teams.edit', compact('team'));
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'abbreviation' => 'required|max:10|unique:teams,abbreviation,' . $team->id,
            'is_playoff_team' => 'boolean'
        ]);

        $team->update($validated);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Team updated successfully');
    }

    public function destroy(Team $team)
    {
        $team->delete();
        return redirect()->route('admin.teams.index')
            ->with('success', 'Team deleted successfully');
    }
}