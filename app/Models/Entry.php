<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class Entry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'entry_name',
        'changes_remaining',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function playerChangeHistory()
    {
        return $this->hasMany(PlayerChangeHistory::class);
    }

    public function calculateTotalPoints(): float
    {
        $scoringService = new \App\Services\ScoringService();
        return $scoringService->calculateTotalPoints($this->players->flatMap->stats);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'entry_player')
            ->using(EntryPlayer::class)
            ->withPivot('roster_position')
            ->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($entry) {
            \Log::info('Entry updating event fired', ['entry' => $entry]);
        });
        
        static::updated(function ($entry) {
            \Log::info('Entry updated event fired', ['entry' => $entry]);
        });

        Event::listen('eloquent.*', function($event, $data) {
            \Log::info('Eloquent Event: ' . $event, ['data' => $data]);
        });
    }
}
