namespace App\Services;

use App\Models\TransactionQueue;
use App\Models\Entry;
use Illuminate\Support\Facades\DB;
use App\Exceptions\TransactionValidationException;

class TransactionService
{
    protected $rosterLockService;
    
    public function __construct(RosterLockService $rosterLockService)
    {
        $this->rosterLockService = $rosterLockService;
    }

    public function queueTransaction(array $data)
    {
        return DB::transaction(function () use ($data) {
            $transaction = TransactionQueue::create([
                'entry_id' => $data['entry_id'],
                'dropped_player_id' => $data['dropped_player_id'],
                'added_player_id' => $data['added_player_id'],
                'roster_position' => $data['roster_position'],
                'status' => 'pending',
                'validation_data' => $this->prepareValidationData($data)
            ]);

            ProcessTransactionQueue::dispatch($transaction->id);
            return $transaction;
        });
    }

    public function process($transactionId)
    {
        $transaction = TransactionQueue::findOrFail($transactionId);
        $transaction->update(['status' => 'processing']);

        try {
            $this->validateTransaction($transaction);
            $this->executeTransaction($transaction);
            $transaction->update(['status' => 'completed', 'processed_at' => now()]);
        } catch (TransactionValidationException $e) {
            $this->handleFailedTransaction($transaction, $e->getMessage());
        }
    }

    protected function validateTransaction($transaction)
    {
        if (!$this->rosterLockService->canModifyRoster($transaction->entry, $transaction->droppedPlayer)) {
            throw new TransactionValidationException('Cannot modify roster - player is locked');
        }

        // Additional validation checks
        $this->validateRosterPosition($transaction);
        $this->validateTeamLimits($transaction);
        $this->validateTransactionWindow($transaction);
    }

    protected function executeTransaction($transaction)
    {
        DB::transaction(function () use ($transaction) {
            $entry = Entry::findOrFail($transaction->entry_id);
            
            // Update roster
            $entry->players()->detach($transaction->dropped_player_id);
            $entry->players()->attach($transaction->added_player_id, [
                'roster_position' => $transaction->roster_position
            ]);

            // Create transaction record
            $entry->transactions()->create([
                'dropped_player_id' => $transaction->dropped_player_id,
                'added_player_id' => $transaction->added_player_id,
                'roster_position' => $transaction->roster_position
            ]);

            $entry->decrement('changes_remaining');
        });
    }

    protected function handleFailedTransaction($transaction, $reason)
    {
        $transaction->update([
            'status' => 'failed',
            'failure_reason' => $reason,
            'processed_at' => now()
        ]);
    }

    protected $validationService;

public function __construct(
    RosterLockService $rosterLockService,
    TransactionValidationService $validationService
) {
    $this->rosterLockService = $rosterLockService;
    $this->validationService = $validationService;
}

protected function validateTransaction($transaction)
{
    if (!$transaction->entry->canProcessTransaction()) {
        throw new TransactionValidationException('Entry cannot process transactions');
    }

    if (!$this->validationService->validateRosterPosition($transaction)) {
        throw new TransactionValidationException('Invalid roster position');
    }

    if (!$this->validationService->validateTeamLimits($transaction)) {
        throw new TransactionValidationException('Team limit exceeded');
        }
    }
}