<?php
// database/seeders/PlayerSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlayerSeeder extends Seeder
{
    public function run()
    {
        $players = [
            // Chiefs
            ['name' => 'Patrick Mahomes', 'team_id' => 1, 'position' => 'QB'],
            ['name' => 'Travis Kelce', 'team_id' => 1, 'position' => 'TE'],
            ['name' => 'Isiah Pacheco', 'team_id' => 1, 'position' => 'RB'],
            ['name' => 'Rashee Rice', 'team_id' => 1, 'position' => 'WR'],
            ['name' => 'Marquez Valdes-Scantling', 'team_id' => 1, 'position' => 'WR'],
            ['name' => 'Justin Watson', 'team_id' => 1, 'position' => 'WR'],
            ['name' => 'Clyde Edwards-Helaire', 'team_id' => 1, 'position' => 'RB'],
            
            // Bills
            ['name' => 'Josh Allen', 'team_id' => 2, 'position' => 'QB'],
            ['name' => 'Stefon Diggs', 'team_id' => 2, 'position' => 'WR'],
            ['name' => 'James Cook', 'team_id' => 2, 'position' => 'RB'],
            ['name' => 'Gabe Davis', 'team_id' => 2, 'position' => 'WR'],
            ['name' => 'Dalton Kincaid', 'team_id' => 2, 'position' => 'TE'],
            ['name' => 'Dawson Knox', 'team_id' => 2, 'position' => 'TE'],
            ['name' => 'Khalil Shakir', 'team_id' => 2, 'position' => 'WR'],
            ['name' => 'Latavius Murray', 'team_id' => 2, 'position' => 'RB'],
            
            // Ravens
            ['name' => 'Lamar Jackson', 'team_id' => 3, 'position' => 'QB'],
            ['name' => 'Zay Flowers', 'team_id' => 3, 'position' => 'WR'],
            ['name' => 'Mark Andrews', 'team_id' => 3, 'position' => 'TE'],
            ['name' => 'Isaiah Likely', 'team_id' => 3, 'position' => 'TE'],
            ['name' => 'Odell Beckham Jr', 'team_id' => 3, 'position' => 'WR'],
            ['name' => 'Nelson Agholor', 'team_id' => 3, 'position' => 'WR'],
            ['name' => 'Justice Hill', 'team_id' => 3, 'position' => 'RB'],
            ['name' => 'Gus Edwards', 'team_id' => 3, 'position' => 'RB'],
            
            // 49ers
            ['name' => 'Brock Purdy', 'team_id' => 4, 'position' => 'QB'],
            ['name' => 'Christian McCaffrey', 'team_id' => 4, 'position' => 'RB'],
            ['name' => 'Deebo Samuel', 'team_id' => 4, 'position' => 'WR'],
            ['name' => 'Brandon Aiyuk', 'team_id' => 4, 'position' => 'WR'],
            ['name' => 'George Kittle', 'team_id' => 4, 'position' => 'TE'],
            ['name' => 'Jauan Jennings', 'team_id' => 4, 'position' => 'WR'],
            ['name' => 'Elijah Mitchell', 'team_id' => 4, 'position' => 'RB'],
            ['name' => 'Kyle Juszczyk', 'team_id' => 4, 'position' => 'RB'],
            
            // Cowboys
            ['name' => 'Dak Prescott', 'team_id' => 5, 'position' => 'QB'],
            ['name' => 'CeeDee Lamb', 'team_id' => 5, 'position' => 'WR'],
            ['name' => 'Tony Pollard', 'team_id' => 5, 'position' => 'RB'],
            ['name' => 'Brandin Cooks', 'team_id' => 5, 'position' => 'WR'],
            ['name' => 'Michael Gallup', 'team_id' => 5, 'position' => 'WR'],
            ['name' => 'Jake Ferguson', 'team_id' => 5, 'position' => 'TE'],
            ['name' => 'Rico Dowdle', 'team_id' => 5, 'position' => 'RB'],
            ['name' => 'Luke Schoonmaker', 'team_id' => 5, 'position' => 'TE'],
            
            // Eagles
            ['name' => 'Jalen Hurts', 'team_id' => 6, 'position' => 'QB'],
            ['name' => 'AJ Brown', 'team_id' => 6, 'position' => 'WR'],
            ['name' => 'DeVonta Smith', 'team_id' => 6, 'position' => 'WR'],
            ['name' => 'Dallas Goedert', 'team_id' => 6, 'position' => 'TE'],
            ['name' => 'Kenneth Gainwell', 'team_id' => 6, 'position' => 'RB'],
            ['name' => 'D\'Andre Swift', 'team_id' => 6, 'position' => 'RB'],
            ['name' => 'Julio Jones', 'team_id' => 6, 'position' => 'WR'],
            ['name' => 'Jack Stoll', 'team_id' => 6, 'position' => 'TE'],
            
            // Dolphins
            ['name' => 'Tua Tagovailoa', 'team_id' => 7, 'position' => 'QB'],
            ['name' => 'Tyreek Hill', 'team_id' => 7, 'position' => 'WR'],
            ['name' => 'Raheem Mostert', 'team_id' => 7, 'position' => 'RB'],
            ['name' => 'Jaylen Waddle', 'team_id' => 7, 'position' => 'WR'],
            ['name' => 'Durham Smythe', 'team_id' => 7, 'position' => 'TE'],
            ['name' => 'De\'Von Achane', 'team_id' => 7, 'position' => 'RB'],
            ['name' => 'Braxton Berrios', 'team_id' => 7, 'position' => 'WR'],
            ['name' => 'Julian Hill', 'team_id' => 7, 'position' => 'TE'],
            
            // Lions
            ['name' => 'Jared Goff', 'team_id' => 8, 'position' => 'QB'],
            ['name' => 'Amon-Ra St. Brown', 'team_id' => 8, 'position' => 'WR'],
            ['name' => 'Sam LaPorta', 'team_id' => 8, 'position' => 'TE'],
            ['name' => 'Jahmyr Gibbs', 'team_id' => 8, 'position' => 'RB'],
            ['name' => 'David Montgomery', 'team_id' => 8, 'position' => 'RB'],
            ['name' => 'Josh Reynolds', 'team_id' => 8, 'position' => 'WR'],
            ['name' => 'Jameson Williams', 'team_id' => 8, 'position' => 'WR'],
            ['name' => 'Brock Wright', 'team_id' => 8, 'position' => 'TE']
        ];

        foreach ($players as $player) {
            DB::table('players')->insert([
                'name' => $player['name'],
                'team_id' => $player['team_id'],
                'position' => $player['position'],
                'is_active' => true,    
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}