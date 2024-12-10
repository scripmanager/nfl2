namespace App\Services;

class TransactionValidationService
{
    public function validateRosterPosition($transaction): bool
    {
        $entry = $transaction->entry;
        $positions = $entry->players()
            ->where('id', '!=', $transaction->dropped_player_id)
            ->pluck('roster_position')
            ->push($transaction->roster_position);

        return $this->validatePositionRequirements($positions);
    }

    public function validateTeamLimits($transaction): bool
    {
        $entry = $transaction->entry;
        $teamCounts = $entry->players()
            ->where('id', '!=', $transaction->dropped_player_id)
            ->get()
            ->groupBy('team_id')
            ->map->count();

        return ($teamCounts[$transaction->addedPlayer->team_id] ?? 0) < 2;
    }

    protected function validatePositionRequirements($positions): bool
    {
        $counts = $positions->countBy();
        return $counts->get('QB') === 1 
            && $counts->get('RB1') + $counts->get('RB2') === 2
            && $counts->whereIn('WR1', 'WR2', 'WR3')->sum() === 3
            && $counts->get('TE') === 1
            && $counts->get('FLEX') === 1;
    }
}