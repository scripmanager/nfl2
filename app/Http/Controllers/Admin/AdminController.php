<?php

namespace App\Http\Controllers\Admin;

use App\Models\Player;
use App\Models\Team;
use App\Models\Game;
use App\Models\PlayerStats;
use App\Models\User;
use App\Models\Entry;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
   public function index()
   {
       $latestSync = [
           'players' => Player::latest()->first()?->created_at,
           'teams' => Team::latest()->first()?->created_at,
           'games' => Game::latest()->first()?->created_at,
           'stats' => PlayerStats::latest()->first()?->created_at,
       ];

       return view('admin.dashboard', [
           'totalUsers' => User::count(),
           'totalEntries' => Entry::count(), 
           'activeGames' => Game::where('status', 'active')->count(),
           'recentTransactions' => Transaction::with(['entry.user', 'dropped_player', 'added_player'])->latest()->take(5)->get(),
           'recentStats' => PlayerStats::with(['game', 'player'])->latest()->take(5)->get(),
       ]);
   }
   
   public function bulkStats()
{
    return view('admin.bulk-stats');
}
   public function importStats(Request $request)
   {
       $request->validate([
           'stats_file' => 'required|file|mimes:csv,txt'
       ]);

       try {
           DB::beginTransaction();

           $path = $request->file('stats_file')->getRealPath();
           $data = array_map('str_getcsv', file($path));
           $headers = array_shift($data);

           $mappings = [
               'player_id' => array_search('player_id', $headers),
               'game_id' => array_search('game_id', $headers),
               'passing_yards' => array_search('passing_yards', $headers),
               'passing_tds' => array_search('passing_tds', $headers),
               'interceptions' => array_search('interceptions', $headers),
               'rushing_yards' => array_search('rushing_yards', $headers),
               'rushing_tds' => array_search('rushing_tds', $headers),
               'receptions' => array_search('receptions', $headers),
               'receiving_yards' => array_search('receiving_yards', $headers),
               'receiving_tds' => array_search('receiving_tds', $headers),
               'two_point_conversions' => array_search('two_point_conversions', $headers),
               'fumbles_lost' => array_search('fumbles_lost', $headers),
               'offensive_fumble_recovery_td' => array_search('offensive_fumble_recovery_td', $headers)
           ];

           foreach ($data as $row) {
               if (!isset($row[$mappings['player_id']]) || !isset($row[$mappings['game_id']])) {
                   continue;
               }

               $stats = [
                   'passing_yards' => (int)($row[$mappings['passing_yards']] ?? 0),
                   'passing_tds' => (int)($row[$mappings['passing_tds']] ?? 0),
                   'interceptions' => (int)($row[$mappings['interceptions']] ?? 0),
                   'rushing_yards' => (int)($row[$mappings['rushing_yards']] ?? 0),
                   'rushing_tds' => (int)($row[$mappings['rushing_tds']] ?? 0),
                   'receptions' => (int)($row[$mappings['receptions']] ?? 0),
                   'receiving_yards' => (int)($row[$mappings['receiving_yards']] ?? 0),
                   'receiving_tds' => (int)($row[$mappings['receiving_tds']] ?? 0),
                   'two_point_conversions' => (int)($row[$mappings['two_point_conversions']] ?? 0),
                   'fumbles_lost' => (int)($row[$mappings['fumbles_lost']] ?? 0),
                   'offensive_fumble_recovery_td' => (int)($row[$mappings['offensive_fumble_recovery_td']] ?? 0),
               ];

               PlayerStats::updateOrCreate(
                   [
                       'player_id' => $row[$mappings['player_id']],
                       'game_id' => $row[$mappings['game_id']]
                   ],
                   $stats
               );
           }

           DB::commit();
           return back()->with('success', 'Stats imported successfully');

       } catch (\Exception $e) {
           DB::rollBack();
           Log::error('Stats import failed: ' . $e->getMessage());
           return back()->with('error', 'Failed to import stats. Please check the file format and try again.');
       }
   }

   public function calculatePoints()
   {
       try {
           DB::beginTransaction();

           $stats = PlayerStats::with(['player', 'game'])->get();

           foreach ($stats as $stat) {
               $points = 0;

               // Passing points
               $points += floor($stat->passing_yards / 25);
               if ($stat->passing_yards >= 300) $points += 4;
               if ($stat->passing_yards >= 400) $points += 4;
               $points += $stat->passing_tds * 6;
               $points -= $stat->interceptions * 2;

               // Rushing points
               $points += floor($stat->rushing_yards / 10);
               if ($stat->rushing_yards >= 100) $points += 4;
               if ($stat->rushing_yards >= 200) $points += 4;
               $points += $stat->rushing_tds * 6;

               // Receiving points
               $points += $stat->receptions * 0.5;
               $points += floor($stat->receiving_yards / 10);
               if ($stat->receiving_yards >= 100) $points += 4;
               if ($stat->receiving_yards >= 200) $points += 4;
               $points += $stat->receiving_tds * 6;

               // Miscellaneous points
               $points += $stat->two_point_conversions * 2;
               $points -= $stat->fumbles_lost * 2;
               $points += $stat->offensive_fumble_recovery_td * 6;

               $stat->points = $points;
               $stat->save();
           }

           DB::commit();
           return back()->with('success', 'Points calculated successfully');

       } catch (\Exception $e) {
           DB::rollBack();
           Log::error('Points calculation failed: ' . $e->getMessage());
           return back()->with('error', 'Failed to calculate points');
       }
   }
}