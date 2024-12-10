<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\DB;

class EntryPlayer extends Pivot
{
    protected $table = 'entry_player';

    protected $fillable = [
        'entry_id',
        'player_id',
        'roster_position',
        'wildcard_points',
        'divisional_points',
        'conference_points',
        'superbowl_points',
        'total_points'
    ];

}