<?php

namespace App\Policies;

use App\Models\Entry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EntryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Entry $entry): bool
    {
        return $user->id === $entry->user_id;
    }

    public function create(User $user): bool
    {
        return $user->entries()->count() < 4;
    }

    public function update(User $user, Entry $entry): bool
    {
        return $user->id === $entry->user_id 
            && $entry->changes_remaining > 0 
            && $entry->is_active;
    }

    public function delete(User $user, Entry $entry): bool
    {
        return $user->id === $entry->user_id;
    }
}