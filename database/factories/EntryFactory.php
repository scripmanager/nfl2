<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'entry_name' => fake()->words(3, true),
            'changes_remaining' => 2,
            'is_active' => true,
        ];
    }
}