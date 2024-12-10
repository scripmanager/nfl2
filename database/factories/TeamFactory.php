<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->city() . ' ' . fake()->word(),
            'abbreviation' => strtoupper(fake()->lexify('???')),
            'external_id' => fake()->unique()->numberBetween(1000, 9999),
        ];
    }
}