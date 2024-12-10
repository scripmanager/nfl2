namespace App\Services;

class PointCalculationService
{
    protected $scoringService;

    public function __construct(ScoringService $scoringService)
    {
        $this->scoringService = $scoringService;
    }

    public function queueCalculation(Game $game, string $type)
    {
        $affected = $this->getAffectedEntries($game);
        
        $calculation = PointCalculationQueue::create([
            'game_id' => $game->id,
            'type' => $type,
            'status' => 'pending',
            'affected_entries' => $affected,
            'calculation_data' => $this->prepareCalculationData($game)
        ]);

        ProcessPointCalculation::dispatch($calculation->id);
        return $calculation;
    }

    public function process($calculationId)
    {
        $calculation = PointCalculationQueue::findOrFail($calculationId);
        $calculation->update(['status' => 'processing']);

        try {
            foreach ($calculation->affected_entries as $entryId) {
                $this->calculateEntryPoints($entryId, $calculation->game_id);
            }
            $calculation->update(['status' => 'completed', 'processed_at' => now()]);
        } catch (\Exception $e) {
            $this->handleFailedCalculation($calculation, $e->getMessage());
        }
    }

    protected function calculateEntryPoints($entryId, $gameId)
    {
        DB::transaction(function () use ($entryId, $gameId) {
            $entry = Entry::findOrFail($entryId);
            $game = Game::findOrFail($gameId);
            $stats = PlayerStats::where('game_id', $gameId)
                               ->whereIn('player_id', $entry->players->pluck('id'))
                               ->get();

            foreach ($stats as $stat) {
                $points = $this->scoringService->calculatePoints($stat);
                $entry->players()->updateExistingPivot($stat->player_id, [
                    'points_' . strtolower($game->round) => $points
                ]);
            }

            $entry->updateTotalPoints();
        });
    }
}