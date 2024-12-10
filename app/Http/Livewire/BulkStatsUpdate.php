<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Game;
use App\Models\PlayerStats;
use League\Csv\Reader;


class BulkStatsUpdate extends Component
{
    use WithFileUploads;

    public $games;
    public $statsFile;
    public $importType = 'csv';
    public $previewData = [];
    public $mapping = [];
    public $processing = false;

    protected $rules = [
        'statsFile' => 'required|file|mimes:csv,txt|max:1024',
    ];

    public function mount()
    {
        $this->games = Game::whereIn('status', ['scheduled', 'in_progress'])
            ->with(['homeTeam', 'awayTeam'])
            ->orderBy('kickoff')
            ->get();
    }

    public function updatedStatsFile()
    {
        $this->validate();
    
        $csv = Reader::createFromPath($this->statsFile->path());
        $csv->setHeaderOffset(0);
        $csv->setDelimiter(',');
        // Add these lines to handle the quotes
        $csv->setEnclosure('"');
        $csv->setEscape('\\');
        
        // Preview first 5 rows
        $this->previewData = iterator_to_array($csv->getRecords(), true);
        $this->previewData = array_slice($this->previewData, 0, 5);
    }
    public function import()
    {
        $this->validate();
        $this->processing = true;
    
        try {
            $csv = Reader::createFromPath($this->statsFile->path());
            $csv->setHeaderOffset(0);
            $csv->setDelimiter(','); // Explicitly set the delimiter
            
            \DB::beginTransaction();
            
            foreach ($csv->getRecords() as $record) {
                // Convert array values to integers where needed
                PlayerStats::updateOrCreate(
                    [
                        'game_id' => (int)$record['game_id'],
                        'player_id' => (int)$record['player_id'],
                    ],
                    [
                        'passing_yards' => (int)$record['passing_yards'],
                        'passing_tds' => (int)$record['passing_tds'],
                        'interceptions' => (int)$record['interceptions'],
                        'rushing_yards' => (int)$record['rushing_yards'],
                        'rushing_tds' => (int)$record['rushing_tds'],
                        'receptions' => (int)$record['receptions'],
                        'receiving_yards' => (int)$record['receiving_yards'],
                        'receiving_tds' => (int)$record['receiving_tds'],
                        'two_point_conversions' => (int)$record['two_point_conversions'],
                        'fumbles_lost' => (int)$record['fumbles_lost'],
                        'offensive_fumble_return_td' => (int)$record['offensive_fumble_return_td']
                    ]
                );
            }
    
            \DB::commit();
            session()->flash('success', 'Stats imported successfully');
            $this->dispatch('stats-updated');
            $this->dispatch('close-modal', 'bulk-stats-update');
    
        } catch (\Exception $e) {
            \DB::rollBack();
            session()->flash('error', 'Error importing stats: ' . $e->getMessage());
        }
    
        $this->processing = false;
    }
    public function render()
    {
        return view('livewire.admin.bulk-stats-update');
    }
}