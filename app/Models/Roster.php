<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    protected $fillable = ['entry_id', 'player_id', 'roster_position'];

    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}