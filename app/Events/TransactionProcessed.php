namespace App\Events;

class TransactionProcessed
{
    public $transaction;
    
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }
}