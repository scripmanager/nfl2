<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatCorrection extends Model
{
    protected $fillable = [
        'player_id',
        'game_id',
        'stat_type',
        'old_value',
        'new_value',
        'description',
        'admin_id'
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}