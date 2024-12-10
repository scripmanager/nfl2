<?php
// database/seeders/GameSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GameSeeder extends Seeder
{
    public function run()
    {
        $games = [
            // Wild Card Round
            [
                'home_team_id' => 2, // Bills
                'away_team_id' => 7, // Dolphins
                'kickoff' => Carbon::create(2025, 1, 13, 13, 0, 0),
                'round' => 'Wild Card'
            ],
            [
                'home_team_id' => 5, // Cowboys
                'away_team_id' => 8, // Lions
                'kickoff' => Carbon::create(2025, 1, 13, 16, 30, 0),
                'round' => 'Wild Card'
            ],
            
            // Divisional Round
            [
                'home_team_id' => 4, // 49ers
                'away_team_id' => 2, // Bills
                'kickoff' => Carbon::create(2025, 1, 20, 16, 30, 0),
                'round' => 'Divisional'
            ],
            [
                'home_team_id' => 3, // Ravens
                'away_team_id' => 5, // Cowboys
                'kickoff' => Carbon::create(2025, 1, 20, 20, 15, 0),
                'round' => 'Divisional'
            ],
            
            // Conference Championships
            [
                'home_team_id' => 4, // 49ers
                'away_team_id' => 3, // Ravens
                'kickoff' => Carbon::create(2025, 1, 27, 15, 0, 0),
                'round' => 'Conference'
            ],
            
            // Super Bowl
            [
                'home_team_id' => 4, // 49ers
                'away_team_id' => 3, // Ravens
                'kickoff' => Carbon::create(2025, 2, 9, 18, 30, 0),
                'round' => 'Super Bowl'
            ]
        ];

        foreach ($games as $game) {
            DB::table('games')->insert([
                'home_team_id' => $game['home_team_id'],
                'away_team_id' => $game['away_team_id'],
                'kickoff' => $game['kickoff'],
                'round' => $game['round'],
                'status' => 'scheduled',
                'home_score' => 0,
                'away_score' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}