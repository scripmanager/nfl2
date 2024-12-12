<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerChangeHistory extends Model
{
    protected $table = 'entry_player_history';

    protected $fillable = [
        'entry_id',
        'player_id',
        'roster_position',
        'wildcard_points',
        'divisional_points',
        'conference_points',
        'superbowl_points',
        'total_points',
        'removed_at'
    ];

    protected $casts = [
        'wildcard_points' => 'decimal:1',
        'divisional_points' => 'decimal:1',
        'conference_points' => 'decimal:1',
        'superbowl_points' => 'decimal:1',
        'total_points' => 'decimal:1',
        'removed_at' => 'datetime'
    ];
}