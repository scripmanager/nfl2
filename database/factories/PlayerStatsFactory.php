<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerStatsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'player_id' => Player::factory(),
            'game_id' => Game::factory(),
            'passing_yards' => fake()->numberBetween(0, 400),
            'passing_tds' => fake()->numberBetween(0, 5),
            'interceptions' => fake()->numberBetween(0, 3),
            'rushing_yards' => fake()->numberBetween(0, 150),
            'rushing_tds' => fake()->numberBetween(0, 3),
            'receptions' => fake()->numberBetween(0, 12),
            'receiving_yards' => fake()->numberBetween(0, 200),
            'receiving_tds' => fake()->numberBetween(0, 3),
            'fumbles_lost' => fake()->numberBetween(0, 2),
            'two_point_conversions' => fake()->numberBetween(0, 1),
        ];
    }
}