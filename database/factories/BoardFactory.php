<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BoardFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
        ];
    }
}
