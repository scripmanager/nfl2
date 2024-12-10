namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionQueue extends Model
{
    protected $table = 'transaction_queue';
    
    protected $fillable = [
        'entry_id',
        'dropped_player_id',
        'added_player_id',
        'roster_position',
        'status',
        'validation_data',
        'failure_reason',
        'processed_at'
    ];

    protected $casts = [
        'validation_data' => 'array',
        'processed_at' => 'datetime'
    ];

    public function entry() {
        return $this->belongsTo(Entry::class);
    }

    public function droppedPlayer() {
        return $this->belongsTo(Player::class, 'dropped_player_id');
    }

    public function addedPlayer() {
        return $this->belongsTo(Player::class, 'added_player_id');
    }
}