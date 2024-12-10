<?php

namespace App\Services;

use App\Models\Player;

class RosterPositionService
{
    public function getEligiblePlayersForPosition(string $rosterPosition, $players)
    {
        $positionMappings = [
            'QB' => ['QB'],
            'RB1' => ['RB'],
            'RB2' => ['RB'],
            'WR1' => ['WR'],
            'WR2' => ['WR'],
            'WR3' => ['WR'],
            'TE' => ['TE'],
            'FLEX' => ['RB', 'WR', 'TE']
        ];

        $eligiblePositions = $positionMappings[$rosterPosition] ?? [];
        
        return Player::with('team')
            ->whereHas('team', function($query) {
                $query->where('is_playoff_team', true);
            })
            ->where('is_active', true)
            ->whereIn('position', $eligiblePositions)
            ->whereNotIn('id', is_array($players) ? $players : $players->pluck('id'));
    }
}