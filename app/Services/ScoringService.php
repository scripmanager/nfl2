<?php

namespace App\Services;

use App\Models\PlayerStat;

class ScoringService
{
    public function calculatePassingPoints($yards, $touchdowns, $interceptions)
    {
        $points = 0;
        
        // Passing yards: 1 point per 25 yards
        $points += floor($yards / 25);
        
        // Passing yard bonuses
        if ($yards >= 300) $points += 4;
        if ($yards >= 400) $points += 4;
        
        // Passing touchdowns: 6 points each
        $points += ($touchdowns * 6);
        
        // Interceptions: -2 points each
        $points += ($interceptions * -2);
        
        return $points;
    }
    
    public function calculateRushingPoints($yards, $touchdowns)
    {
        $points = 0;
        
        // Rushing yards: 1 point per 10 yards
        $points += floor($yards / 10);
        
        // Rushing yard bonuses
        if ($yards >= 100) $points += 4;
        if ($yards >= 200) $points += 4;
        
        // Rushing touchdowns: 6 points each
        $points += ($touchdowns * 6);
        
        return $points;
    }
    
    public function calculateReceivingPoints($receptions, $yards, $touchdowns)
    {
        $points = 0;
        
        // Receptions: 0.5 points each
        $points += ($receptions * 0.5);
        
        // Receiving yards: 1 point per 10 yards
        $points += floor($yards / 10);
        
        // Receiving yard bonuses
        if ($yards >= 100) $points += 4;
        if ($yards >= 200) $points += 4;
        
        // Receiving touchdowns: 6 points each
        $points += ($touchdowns * 6);
        
        return $points;
    }
    
    public function calculateMiscPoints($twoPointConversions, $fumblesLost, $fumbleTouchdowns)
    {
        $points = 0;
        
        // Two-point conversions: 2 points each
        $points += ($twoPointConversions * 2);
        
        // Fumbles lost: -2 points each
        $points += ($fumblesLost * -2);
        
        // Offensive fumble return TD: 6 points each
        $points += ($fumbleTouchdowns * 6);
        
        return $points;
    }
    public function calculateTotalPoints($stats)
    {
       $total = 0;
       foreach ($stats as $stat) {
           $total += $this->calculatePassingPoints($stat->passing_yards, $stat->passing_tds, $stat->interceptions);
           $total += $this->calculateRushingPoints($stat->rushing_yards, $stat->rushing_tds); 
           $total += $this->calculateReceivingPoints($stat->receptions, $stat->receiving_yards, $stat->receiving_tds);
           $total += $this->calculateMiscPoints($stat->two_point_conversions, $stat->fumbles_lost, $stat->offensive_fumble_return_td);
       }
       return $total;
    }

    public function calculatePointsByPosition($entry)
    {
        $pointsByPosition = [];
        foreach ($entry->players as $player) {
            $position = $player->pivot->roster_position;
            if (!isset($pointsByPosition[$position])) {
                $pointsByPosition[$position] = 0;
            }
            $pointsByPosition[$position] += $this->calculateTotalPoints($player->stats);
        }
        return $pointsByPosition;
    }
    public function calculateGamePoints(Game $game, Player $player): array 
    {
        $stats = $game->playerStats()->where('player_id', $player->id)->first();
        
        if (!$stats) {
            return [
                'points' => 0,
                'breakdown' => []
            ];
        }

        $breakdown = [
            'passing' => $this->calculatePassingPoints($stats->passing_yards, $stats->passing_tds, $stats->interceptions),
            'rushing' => $this->calculateRushingPoints($stats->rushing_yards, $stats->rushing_tds),
            'receiving' => $this->calculateReceivingPoints($stats->receptions, $stats->receiving_yards, $stats->receiving_tds),
            'misc' => $this->calculateMiscPoints($stats->two_point_conversions, $stats->fumbles_lost, $stats->offensive_fumble_return_td)
        ];

        return [
            'points' => array_sum($breakdown),
            'breakdown' => $breakdown
        ];
    }

    public function recalculateEntryGamePoints(Entry $entry, Game $game): array
    {
        $pointsByPlayer = [];
        foreach ($entry->players as $player) {
            $pointsByPlayer[$player->id] = $this->calculateGamePoints($game, $player);
        }
        return $pointsByPlayer;
    }
}
