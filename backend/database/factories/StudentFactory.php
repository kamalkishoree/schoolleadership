<?php

namespace Database\Factories;

use App\Models\ClassModel;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'class_id' => ClassModel::factory(),
            'name' => fake()->name(),
            'email_or_mobile' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'total_xp' => 0,
            'current_streak' => 0,
            'last_active_date' => null,
        ];
    }
}

