<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ChallengeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'type' => 'mcq',
            'option_a' => fake()->sentence(),
            'option_b' => fake()->sentence(),
            'option_c' => fake()->sentence(),
            'option_d' => fake()->sentence(),
            'correct_option' => fake()->randomElement(['a', 'b', 'c', 'd']),
            'xp_reward' => fake()->numberBetween(5, 20),
            'active_date' => today(),
            'is_active' => true,
        ];
    }
}

