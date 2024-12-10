namespace App\Observers;

use App\Models\TransactionQueue;
use App\Events\TransactionProcessed;

class TransactionQueueObserver
{
    public function updated(TransactionQueue $transaction)
    {
        if ($transaction->isDirty('status') && $transaction->status === 'completed') {
            event(new TransactionProcessed($transaction));
        }
    }
}