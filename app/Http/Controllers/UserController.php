<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);
        
        $users = User::withCount('entries')->paginate(20);
        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        $entries = $user->entries()->with('players')->get();
        return view('users.show', compact('user', 'entries'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validated();
        
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.show', $user)
            ->with('success', 'User updated successfully');
    }

    public function dashboard()
    {
        $user = auth()->user();
        $entries = $user->entries()
            ->with(['players.team', 'players.stats.game'])
            ->get();

        return view('users.dashboard', compact('user', 'entries'));
    }
}