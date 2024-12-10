namespace App\Jobs;

class ProcessPointCalculation implements ShouldQueue
{
    protected $calculationId;

    public function __construct($calculationId)
    {
        $this->calculationId = $calculationId;
    }

    public function handle(PointCalculationService $calculationService)
    {
        $calculationService->process($this->calculationId);
    }
}