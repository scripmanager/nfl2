<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    public function definition(): array
    {
        return [
            'home_team_id' => Team::factory(),
            'away_team_id' => Team::factory(),
            'round' => fake()->randomElement(['Wild Card', 'Divisional', 'Conference', 'Super Bowl']),
            'kickoff' => fake()->dateTimeBetween('now', '+1 week'),
            'status' => 'scheduled',
            'home_score' => 0,
            'away_score' => 0,
        ];
    }
}