<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerChangeHistory extends Model
{
    protected $table = 'player_change_history';
    
    protected $fillable = [
        'entry_id',
        'player_id',
        'action'
    ];

    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}