<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\DB;

class EntryPlayer extends Pivot
{
    protected $table = 'entry_player';

    public $timestamps = true;

    protected $dates = [
        'removed_at',
        'created_at',
        'updated_at'
    ];

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

    public function markAsRemoved()
    {
        $this->removed_at = now();
        $this->save();
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}