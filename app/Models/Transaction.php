<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'entry_id',
        'dropped_player_id',
        'added_player_id',
        'roster_position',
        'processed_at',
        'transaction_type',
        'notes'
    ];

    protected $dates = [
        'processed_at'
    ];

    protected $with = ['entry', 'dropped_player', 'added_player'];

    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }

    public function user()
    {
        return $this->entry->user();
    }

    public function dropped_player()
    {
        return $this->belongsTo(Player::class, 'dropped_player');
    }

    public function added_player()
    {
        return $this->belongsTo(Player::class, 'added_player');
    }

    public function scopeForEntry($query, $entryId)
    {
        return $query->where('entry_id', $entryId);
    }

    public function getTransactionDescription()
    {
            return "Dropped {$this->dropped_player->name} ({$this->dropped_player->team->abbreviation}) for {$this->added_player->name} ({$this->added_player->team->abbreviation})";
    }
}