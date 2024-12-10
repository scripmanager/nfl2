<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    public function definition(): array
    {
        $positions = ['QB', 'RB', 'WR', 'TE'];
        
        return [
            'name' => fake()->name(),
            'team_id' => Team::factory(),
            'position' => fake()->randomElement($positions),
            'is_active' => true,
            'external_id' => fake()->unique()->numberBetween(1000, 9999),
        ];
    }
}