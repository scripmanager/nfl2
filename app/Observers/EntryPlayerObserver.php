<?php

namespace App\Observers;

use App\Models\EntryPlayer;
use Illuminate\Support\Facades\DB;

class EntryPlayerObserver
{
    public function deleting(EntryPlayer $entryPlayer)
    {
        DB::table('entry_player_history')->insert([
            'entry_id' => $entryPlayer->entry_id,
            'player_id' => $entryPlayer->player_id,
            'roster_position' => $entryPlayer->roster_position,
            'wildcard_points' => $entryPlayer->wildcard_points,
            'divisional_points' => $entryPlayer->divisional_points,
            'conference_points' => $entryPlayer->conference_points,
            'superbowl_points' => $entryPlayer->superbowl_points,
            'total_points' => $entryPlayer->total_points,
            'removed_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}