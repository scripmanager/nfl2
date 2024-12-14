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

    protected $with = ['entry', 'droppedPlayer', 'addedPlayer'];

    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }

    public function user()
    {
        return $this->entry->user();
    }

    public function droppedPlayer()
    {
        return $this->belongsTo(Player::class, 'droppedPlayer');
    }

    public function addedPlayer()
    {
        return $this->belongsTo(Player::class, 'addedPlayer');
    }

    public function scopeForEntry($query, $entryId)
    {
        return $query->where('entry_id', $entryId);
    }

    public function getTransactionDescription()
    {
        return "Dropped {$this->droppedPlayer->name} ({$this->droppedPlayer->team->abbreviation}) for {$this->addedPlayer->name} ({$this->addedPlayer->team->abbreviation})";
    }
}