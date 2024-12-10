<?php
// database/seeders/TeamSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    public function run()
    {
        $teams = [
            ['name' => 'Kansas City Chiefs', 'abbreviation' => 'KC', 'is_playoff_team' => true],
            ['name' => 'Buffalo Bills', 'abbreviation' => 'BUF', 'is_playoff_team' => true],
            ['name' => 'Baltimore Ravens', 'abbreviation' => 'BAL', 'is_playoff_team' => true],
            ['name' => 'San Francisco 49ers', 'abbreviation' => 'SF', 'is_playoff_team' => true],
            ['name' => 'Dallas Cowboys', 'abbreviation' => 'DAL', 'is_playoff_team' => true],
            ['name' => 'Philadelphia Eagles', 'abbreviation' => 'PHI', 'is_playoff_team' => true],
            ['name' => 'Miami Dolphins', 'abbreviation' => 'MIA', 'is_playoff_team' => true],
            ['name' => 'Detroit Lions', 'abbreviation' => 'DET', 'is_playoff_team' => true],
        ];

        foreach ($teams as $team) {
            DB::table('teams')->insert([
                'name' => $team['name'],
                'abbreviation' => $team['abbreviation'],
                'is_playoff_team' => $team['is_playoff_team'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}