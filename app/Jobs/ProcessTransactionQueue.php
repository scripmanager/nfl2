namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\TransactionService;

class ProcessTransactionQueue implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $transactionId;

    public function __construct($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    public function handle(TransactionService $transactionService)
    {
        $transactionService->process($this->transactionId);
    }
}